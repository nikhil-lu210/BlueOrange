<?php

namespace App\Services\Administration\Leave;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Facades\DB;
use App\Models\Leave\LeaveAvailable;
use Illuminate\Database\Eloquent\Builder;

class LeaveHistoryService
{
    /**
     * Build the query for retrieving daily breaks.
     *
     * @param Request $request
     * @param int|null $userId
     * @return Builder
     */
    public function getLeavesQuery($request, int $userId = null): Builder
    {
        $query = LeaveHistory::with([
            'user:id,userid,name', 
            'user.media', 
            'user.roles',
        ])
        ->orderByDesc('date')
        ->orderBy('created_at');

        // Apply user ID filter if provided
        if ($userId) {
            $query->whereUserId($userId);
        }

        // Apply user ID filter if request user_id provided
        if ($request->user_id) {
            $query->whereUserId($request->user_id);
        }

        // Handle month/year filtering
        if ($request->has('leave_month_year') && !is_null($request->leave_month_year)) {
            $monthYear = Carbon::createFromFormat('F Y', $request->leave_month_year);
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

        // Apply type filter if specified
        if ($request->has('type') && !is_null($request->type)) {
            $query->where('type', $request->type);
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
        // dd(isset($data['files']), !empty($data['files']));
        DB::transaction(function () use ($user, $data) {
            foreach ($data['leave_days']['date'] as $index => $date) {
                // Format total_leave to hh:mm:ss
                $totalLeave = sprintf(
                    '%02d:%02d:%02d',
                    $data['total_leave']['hour'][$index] ?? 0,
                    $data['total_leave']['min'][$index] ?? 0,
                    $data['total_leave']['sec'][$index] ?? 0
                );

                // Create leave history entry for each date
                $leaveHistory = $user->leave_histories()->create([
                    'leave_allowed_id' => $user->allowed_leave->id,
                    'date' => $date,
                    'total_leave' => $totalLeave,
                    'type' => $data['type'],
                    'reason' => $data['reason']
                ]);

                // Check and store associated files if provided in the 'files' key
                if (isset($data['files']) && !empty($data['files'])) {
                    foreach ($data['files'] as $file) {
                        $directory = 'leaves/' . $user->userid;
                        store_file_media($file, $leaveHistory, $directory);
                    }
                }
            }
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

                // Retrieve or create leave_available for the specified year
                $leaveAvailable = LeaveAvailable::firstOrCreate(
                    [
                        'user_id' => $user->id,
                        'for_year' => $forYear
                    ],
                    [
                        'earned_leave' => $leaveHistory->leave_allowed->earned_leave,
                        'casual_leave' => $leaveHistory->leave_allowed->casual_leave,
                        'sick_leave' => $leaveHistory->leave_allowed->sick_leave,
                    ]
                );

                // Calculate the leave taken in seconds
                $leaveTakenInSeconds = $leaveHistory->total_leave->total('seconds');

                // Update leave balance based on leave type
                $this->updateLeaveBalance($leaveAvailable, $leaveHistory->type, $leaveTakenInSeconds);

                // Save the updated leave available values
                $leaveAvailable->save();

                // Update leave history approval status and details
                $leaveHistory->update([
                    'status' => 'Approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => Carbon::now(),
                    'is_paid_leave' => $request->input('is_paid_leave') === 'Paid',
                ]);
            });
        } catch (Exception $e) {
            throw new Exception('Failed to approve leave: ' . $e->getMessage());
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
     * Format leave time from seconds to HH:MM:SS.
     *
     * @param int $totalSeconds
     * @return string
     */
    private function formatLeaveTime(int $totalSeconds): string
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
