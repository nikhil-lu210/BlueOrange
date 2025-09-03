<?php

namespace App\Services\Administration\Leave;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveAllowed;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Leave\LeaveAvailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use App\Mail\Administration\Leave\NewLeaveRequestMail;
use App\Mail\Administration\Leave\LeaveRequestStatusUpdateMail;
use App\Notifications\Administration\Leave\LeaveStoreNotification;
use App\Notifications\Administration\Leave\LeaveRequestUpdateNotification;

class LeaveHistoryService
{
    /**
     * Get leave history records with filtering.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getLeaveHistories(Request $request)
    {
        $query = LeaveHistory::with([
            'user.employee',
            'user.media',
            'user.roles',
            'files',
            'reviewer',
            'reviewer.employee',
            'leave_allowed'
        ]);

        // Apply team leader filter if specified
        if ($request->has('team_leader_id') && !is_null($request->team_leader_id)) {
            $teamLeader = User::find($request->team_leader_id);
            if ($teamLeader && method_exists($teamLeader, 'tl_employees')) {
                $employeeIds = $teamLeader->tl_employees->pluck('id');
                $query->whereIn('user_id', $employeeIds);
            }
        }

        // Apply user filter if specified
        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        // Apply month/year filter if specified
        if ($request->has('leave_month_year') && !is_null($request->leave_month_year)) {
            $monthYear = Carbon::parse($request->leave_month_year);
            $query->whereYear('date', $monthYear->year)
                ->whereMonth('date', $monthYear->month);
        } else {
            // Default to current month if no specific filter is applied
            if (!$request->has('filter_leaves')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d')
                ]);
            }
        }

        // Apply date range filter if specified (for backward compatibility)
        if ($request->has('date_from') && !is_null($request->date_from)) {
            $query->where('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !is_null($request->date_to)) {
            $query->where('date', '<=', $request->date_to);
        }

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
        }

        // Apply status filter if specified
        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        }

        return $query;
    }


    /**
     * Store leave history records for the user.
     *
     * @param User $user
     * @param array $data
     * @return void
     */
    public function store(User $user, array $data): void
    {
        // Log the start of leave request creation
        Log::info('Starting leave request creation', [
            'user_id' => $user->id,
            'leave_type' => $data['type'],
            'dates_count' => count($data['leave_days']['date']),
            'has_files' => isset($data['files']) && !empty($data['files']),
            'ip_address' => request()->ip()
        ]);

        DB::transaction(function () use ($user, $data) {
            $createdLeaves = [];

            foreach ($data['leave_days']['date'] as $index => $date) {
                // Format total_leave to hh:mm:ss
                $totalLeave = sprintf(
                    '%02d:%02d:%02d',
                    $data['total_leave']['hour'][$index] ?? 0,
                    $data['total_leave']['min'][$index] ?? 0,
                    $data['total_leave']['sec'][$index] ?? 0
                );

                // Additional validation before creating
                if (!$user->allowed_leave) {
                    throw new Exception('User does not have an active leave policy assigned.');
                }

                if (!$user->active_team_leader) {
                    throw new Exception('User does not have an active team leader for leave approval.');
                }

                // Create leave history entry for each date
                $leaveHistory = $user->leave_histories()->create([
                    'leave_allowed_id' => $user->allowed_leave->id,
                    'date' => $date,
                    'total_leave' => $totalLeave,
                    'type' => $data['type'],
                    'reason' => $data['reason']
                ]);

                $createdLeaves[] = $leaveHistory->id;

                // Check and store associated files if provided in the 'files' key
                if (isset($data['files']) && !empty($data['files'])) {
                    foreach ($data['files'] as $file) {
                        $directory = 'leaves/' . $user->userid;
                        store_file_media($file, $leaveHistory, $directory);
                    }
                }

                // Send Notification to Team Leader
                $user->active_team_leader->notify(new LeaveStoreNotification($leaveHistory, auth()->user()));

                // Send Mail to the Team Leader
                Mail::to($user->active_team_leader->employee->official_email)->send(new NewLeaveRequestMail($leaveHistory, $user->active_team_leader));

                // Log individual leave creation
                Log::info('Leave request created', [
                    'leave_id' => $leaveHistory->id,
                    'user_id' => $user->id,
                    'date' => $date,
                    'total_leave' => $totalLeave,
                    'type' => $data['type'],
                    'team_leader_id' => $user->active_team_leader->id
                ]);
            }

            // Log successful completion
            Log::info('Leave request creation completed successfully', [
                'user_id' => $user->id,
                'created_leaves' => $createdLeaves,
                'total_count' => count($createdLeaves)
            ]);

        }, 5);
    }




    /**
     * Approve a leave request and update the leave balance.
     *
     * @param Request $request
     * @param LeaveHistory $leaveHistory
     * @return void
     * @throws Exception
     */
    public function approve(Request $request, LeaveHistory $leaveHistory): void
    {
        try {
            DB::transaction(function () use ($request, $leaveHistory) {
                $user = $leaveHistory->user;
                $forYear = Carbon::parse($leaveHistory->date)->year;
                $type = $request->type ?? $leaveHistory->type;

                // Get the active leave allowed record
                $activeLeaveAllowed = $this->getActiveLeaveAllowed($user);
                if (!$activeLeaveAllowed) {
                    throw ValidationException::withMessages([
                        'leave_policy' => 'No active leave allowed record found for the user.',
                    ]);
                }

                // Get or create leave available record for the year
                $leaveAvailable = $this->getOrCreateLeaveAvailable($user, $forYear, $activeLeaveAllowed);

                // Validate leave balance before approval
                $this->validateLeaveBalance($leaveAvailable, $leaveHistory, $type);

                // Calculate the leave taken in seconds
                $leaveTakenInSeconds = $leaveHistory->total_leave->total('seconds');

                // Update leave balance based on leave type
                $this->updateLeaveBalance($leaveAvailable, $type, $leaveTakenInSeconds);

                // Save the updated leave available values
                $leaveAvailable->save();

                // Update leave history approval status and details
                $leaveHistory->update([
                    'type' => $type,
                    'status' => 'Approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => Carbon::now(),
                    'is_paid_leave' => $request->input('is_paid_leave') === 'Paid' || $type === 'Earned',
                ]);

                // Send Notification to Leave Applier
                $leaveHistory->user->notify(new LeaveRequestUpdateNotification($leaveHistory, auth()->user()));

                // Send Mail to the Leave Applier by Queue
                Mail::to($leaveHistory->user->employee->official_email)->queue(new LeaveRequestStatusUpdateMail($leaveHistory, auth()->user()));
            });
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'leave_policy' => 'Failed to approve leave: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Get the active leave allowed record for a user.
     *
     * @param User $user
     * @return LeaveAllowed|null
     */
    private function getActiveLeaveAllowed(User $user): ?LeaveAllowed
    {
        return $user->leave_alloweds()
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get or create leave available record for a user and year.
     *
     * @param User $user
     * @param int $forYear
     * @param LeaveAllowed $activeLeaveAllowed
     * @return LeaveAvailable
     */
    private function getOrCreateLeaveAvailable(User $user, int $forYear, LeaveAllowed $activeLeaveAllowed): LeaveAvailable
    {
        $leaveAvailable = LeaveAvailable::where('user_id', $user->id)
            ->where('for_year', $forYear)
            ->first();

        if (!$leaveAvailable) {
            // Create new leave available record with initial balances from active leave allowed
            $leaveAvailable = LeaveAvailable::create([
                'user_id' => $user->id,
                'for_year' => $forYear,
                'earned_leave' => $activeLeaveAllowed->earned_leave,
                'casual_leave' => $activeLeaveAllowed->casual_leave,
                'sick_leave' => $activeLeaveAllowed->sick_leave,
            ]);
        }

        return $leaveAvailable;
    }

    /**
     * Validate leave balance before approval.
     *
     * @param LeaveAvailable $leaveAvailable
     * @param LeaveHistory $leaveHistory
     * @return void
     * @throws Exception
     */
    private function validateLeaveBalance(LeaveAvailable $leaveAvailable, LeaveHistory $leaveHistory, $type): void
    {
        $leaveTakenInSeconds = $leaveHistory->total_leave->total('seconds');
        $currentBalanceInSeconds = 0;

        switch ($type) {
            case 'Earned':
                $currentBalanceInSeconds = $leaveAvailable->earned_leave->total('seconds');
                break;
            case 'Casual':
                $currentBalanceInSeconds = $leaveAvailable->casual_leave->total('seconds');
                break;
            case 'Sick':
                $currentBalanceInSeconds = $leaveAvailable->sick_leave->total('seconds');
                break;
            default:
                throw new Exception('Invalid leave type.');
        }

        if ($currentBalanceInSeconds < $leaveTakenInSeconds) {
            $leaveType = strtolower($leaveHistory->type);
            throw new Exception("Insufficient {$leaveType} leave balance. Available: " .
                $this->formatLeaveTime($currentBalanceInSeconds) .
                ", Requested: " . $this->formatLeaveTime($leaveTakenInSeconds));
        }
    }

    /**
     * Update the leave balance based on leave type and taken leave.
     *
     * @param LeaveAvailable $leaveAvailable
     * @param string $leaveType
     * @param int $leaveTakenInSeconds
     * @return void
     * @throws Exception
     */
    private function updateLeaveBalance(LeaveAvailable $leaveAvailable, string $leaveType, int $leaveTakenInSeconds): void
    {
        switch ($leaveType) {
            case 'Casual':
                $currentLeaveInSeconds = $leaveAvailable->casual_leave->total('seconds');
                $newLeaveInSeconds = $currentLeaveInSeconds - $leaveTakenInSeconds;
                if ($newLeaveInSeconds < 0) {
                    throw new Exception('Insufficient casual leave balance.');
                }
                $leaveAvailable->casual_leave = $this->formatLeaveTime($newLeaveInSeconds);
                break;

            case 'Earned':
                $currentLeaveInSeconds = $leaveAvailable->earned_leave->total('seconds');
                $newLeaveInSeconds = $currentLeaveInSeconds - $leaveTakenInSeconds;
                if ($newLeaveInSeconds < 0) {
                    throw new Exception('Insufficient earned leave balance.');
                }
                $leaveAvailable->earned_leave = $this->formatLeaveTime($newLeaveInSeconds);
                break;

            case 'Sick':
                $currentLeaveInSeconds = $leaveAvailable->sick_leave->total('seconds');
                $newLeaveInSeconds = $currentLeaveInSeconds - $leaveTakenInSeconds;
                if ($newLeaveInSeconds < 0) {
                    throw new Exception('Insufficient sick leave balance.');
                }
                $leaveAvailable->sick_leave = $this->formatLeaveTime($newLeaveInSeconds);
                break;

            default:
                throw new Exception('Invalid leave type.');
        }
    }

    /**
     * Reject a leave request.
     *
     * @param Request $request
     * @param LeaveHistory $leaveHistory
     * @return void
     */
    public function reject(Request $request, LeaveHistory $leaveHistory): void
    {
        DB::transaction(function () use ($request, $leaveHistory) {
            // Update leave history rejection status and details
            $leaveHistory->update([
                'status' => 'Rejected',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => Carbon::now(),
                'reviewer_note' => $request->reviewer_note,
            ]);

            // Send Notification to Leave Applier
            $leaveHistory->user->notify(new LeaveRequestUpdateNotification($leaveHistory, auth()->user()));

            // Send Mail to the Leave Applier by Queue
            Mail::to($leaveHistory->user->employee->official_email)->queue(new LeaveRequestStatusUpdateMail($leaveHistory, auth()->user()));
        });
    }

    /**
     * Cancel an approved leave request and update the leave balance.
     *
     * @param Request $request
     * @param LeaveHistory $leaveHistory
     * @return void
     * @throws Exception
     */
    public function cancel(Request $request, LeaveHistory $leaveHistory): void
    {
        if ($leaveHistory->status !== 'Approved') {
            throw new Exception('Only approved leaves can be canceled.');
        }

        DB::transaction(function () use ($request, $leaveHistory) {
            $user = $leaveHistory->user;
            $forYear = Carbon::parse($leaveHistory->date)->year;

            // Retrieve the leave available record
            $leaveAvailable = LeaveAvailable::where('user_id', $user->id)
                ->where('for_year', $forYear)
                ->firstOrFail();

            // Calculate the leave taken in seconds
            $leaveTakenInSeconds = $leaveHistory->total_leave->total('seconds');

            // Revert the leave balance
            $this->revertLeaveBalance($leaveAvailable, $leaveHistory->type, $leaveTakenInSeconds);

            // Save the updated leave available record
            $leaveAvailable->save();

            // Update leave history status
            $leaveHistory->update([
                'status' => 'Canceled',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => Carbon::now(),
                'reviewer_note' => $request->reviewer_note,
            ]);

            // Send Notification to Leave Applier
            $leaveHistory->user->notify(new LeaveRequestUpdateNotification($leaveHistory, auth()->user()));

            // Send Mail to the Leave Applier by Queue
            Mail::to($leaveHistory->user->employee->official_email)->queue(new LeaveRequestStatusUpdateMail($leaveHistory, auth()->user()));
        });
    }

    /**
     * Revert the leave balance based on leave type and canceled leave.
     *
     * @param LeaveAvailable $leaveAvailable
     * @param string $leaveType
     * @param int $leaveTakenInSeconds
     * @return void
     * @throws Exception
     */
    private function revertLeaveBalance(LeaveAvailable $leaveAvailable, string $leaveType, int $leaveTakenInSeconds): void
    {
        switch ($leaveType) {
            case 'Casual':
                $leaveAvailable->casual_leave = $this->formatLeaveTime(
                    $leaveAvailable->casual_leave->total('seconds') + $leaveTakenInSeconds
                );
                break;

            case 'Earned':
                $leaveAvailable->earned_leave = $this->formatLeaveTime(
                    $leaveAvailable->earned_leave->total('seconds') + $leaveTakenInSeconds
                );
                break;

            case 'Sick':
                $leaveAvailable->sick_leave = $this->formatLeaveTime(
                    $leaveAvailable->sick_leave->total('seconds') + $leaveTakenInSeconds
                );
                break;

            default:
                throw new Exception('Invalid leave type.');
        }
    }

    /**
     * Sync leave available balances for a user.
     * This method can be used to recalculate and sync leave balances.
     *
     * @param User $user
     * @param int|null $year
     * @return void
     */
    public function syncLeaveBalances(User $user, int $year = null): void
    {
        $year = $year ?: now()->year;

        DB::transaction(function () use ($user, $year) {
            // Get active leave allowed
            $activeLeaveAllowed = $this->getActiveLeaveAllowed($user);
            if (!$activeLeaveAllowed) {
                throw new Exception('No active leave allowed record found for the user.');
            }

            // Get or create leave available
            $leaveAvailable = $this->getOrCreateLeaveAvailable($user, $year, $activeLeaveAllowed);

            // Calculate total leave taken based on leave_allowed_id and year
            $totalLeaveTaken = $this->calculateTotalLeaveTaken($user, $activeLeaveAllowed->id, $year);

            // Update leave available with correct balances (prevent negative values)
            $earnedBalance = max(0, $activeLeaveAllowed->earned_leave->total('seconds') - $totalLeaveTaken['earned']);
            $casualBalance = max(0, $activeLeaveAllowed->casual_leave->total('seconds') - $totalLeaveTaken['casual']);
            $sickBalance = max(0, $activeLeaveAllowed->sick_leave->total('seconds') - $totalLeaveTaken['sick']);

            $leaveAvailable->earned_leave = $this->formatLeaveTime($earnedBalance);
            $leaveAvailable->casual_leave = $this->formatLeaveTime($casualBalance);
            $leaveAvailable->sick_leave = $this->formatLeaveTime($sickBalance);

            $leaveAvailable->save();
        });
    }

    /**
     * Calculate total leave taken for a user based on leave_allowed_id and year.
     *
     * @param User $user
     * @param int $leaveAllowedId
     * @param int $year
     * @return array
     */
    private function calculateTotalLeaveTaken(User $user, int $leaveAllowedId, int $year): array
    {
        $approvedLeaves = $user->leave_histories()
            ->where('status', 'Approved')
            ->where('leave_allowed_id', $leaveAllowedId)
            ->whereYear('date', $year)
            ->get();

        $totalTaken = [
            'earned' => 0,
            'casual' => 0,
            'sick' => 0
        ];

        foreach ($approvedLeaves as $leave) {
            $seconds = $leave->total_leave->total('seconds');
            $type = strtolower($leave->type);

            if (isset($totalTaken[$type])) {
                $totalTaken[$type] += $seconds;
            }
        }

        return $totalTaken;
    }

    /**
     * Format leave time from seconds to HH:MM:SS.
     *
     * @param int $totalSeconds
     * @return string
     */
    private function formatLeaveTime(int $totalSeconds): string
    {
        // Handle very small values (less than 1 second) as 00:00:00
        if ($totalSeconds < 1) {
            return '00:00:00';
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}


