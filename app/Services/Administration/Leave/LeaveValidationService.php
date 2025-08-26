<?php

namespace App\Services\Administration\Leave;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave\LeaveAvailable;
use App\Models\Leave\LeaveAllowed;

class LeaveValidationService
{
    /**
     * Validate if a user has sufficient leave balance for a specific leave type and duration.
     *
     * @param User $user
     * @param string $leaveType
     * @param string $totalLeave (in HH:MM:SS format)
     * @param string|null $date
     * @return array
     * @throws Exception
     */
    public function validateLeaveBalance(User $user, string $leaveType, string $totalLeave, string $date = null): array
    {
        $date = $date ?: now()->format('Y-m-d');
        $year = Carbon::parse($date)->year;

        // Get active leave allowed
        $activeLeaveAllowed = $this->getActiveLeaveAllowed($user);
        if (!$activeLeaveAllowed) {
            throw new Exception('No active leave allowed record found for the user.');
        }

        // Get or create leave available for the year
        $leaveAvailable = $this->getOrCreateLeaveAvailable($user, $year, $activeLeaveAllowed);

        // Calculate leave duration in seconds
        if ($totalLeave instanceof \Carbon\CarbonInterval) {
            $leaveDurationInSeconds = $totalLeave->total('seconds');
        } else {
            $leaveDurationInSeconds = $this->parseLeaveTime($totalLeave);
        }

        // Get current balance for the leave type
        $currentBalanceInSeconds = $this->getCurrentBalanceForType($leaveAvailable, $leaveType);

        // Check if sufficient balance exists
        $isSufficient = $currentBalanceInSeconds >= $leaveDurationInSeconds;
        $remainingBalance = $currentBalanceInSeconds - $leaveDurationInSeconds;

        return [
            'is_sufficient' => $isSufficient,
            'current_balance' => $this->formatLeaveTime($currentBalanceInSeconds),
            'requested_leave' => $totalLeave,
            'remaining_balance' => $this->formatLeaveTime($remainingBalance),
            'leave_type' => $leaveType,
            'year' => $year,
            'message' => $isSufficient
                ? "Sufficient {$leaveType} leave balance available."
                : "Insufficient {$leaveType} leave balance. Available: " . $this->formatLeaveTime($currentBalanceInSeconds) . ", Requested: {$totalLeave}"
        ];
    }

    /**
     * Get leave balance summary for a user.
     *
     * @param User $user
     * @param int|null $year
     * @return array
     */
    public function getLeaveBalanceSummary(User $user, int $year = null): array
    {
        $year = $year ?: now()->year;

        // Get active leave allowed
        $activeLeaveAllowed = $this->getActiveLeaveAllowed($user);
        if (!$activeLeaveAllowed) {
            return [
                'error' => 'No active leave allowed record found for the user.'
            ];
        }

        // Get leave available for the year
        $leaveAvailable = $this->getOrCreateLeaveAvailable($user, $year, $activeLeaveAllowed);

        return [
            'year' => $year,
            'earned_leave' => [
                'allowed' => $activeLeaveAllowed->earned_leave->format('%H:%I:%S'),
                'available' => $leaveAvailable->earned_leave->format('%H:%I:%S'),
                'used' => $this->formatLeaveTime(
                    max(0, $activeLeaveAllowed->earned_leave->total('seconds') - $leaveAvailable->earned_leave->total('seconds'))
                )
            ],
            'casual_leave' => [
                'allowed' => $activeLeaveAllowed->casual_leave->format('%H:%I:%S'),
                'available' => $leaveAvailable->casual_leave->format('%H:%I:%S'),
                'used' => $this->formatLeaveTime(
                    max(0, $activeLeaveAllowed->casual_leave->total('seconds') - $leaveAvailable->casual_leave->total('seconds'))
                )
            ],
            'sick_leave' => [
                'allowed' => $activeLeaveAllowed->sick_leave->format('%H:%I:%S'),
                'available' => $leaveAvailable->sick_leave->format('%H:%I:%S'),
                'used' => $this->formatLeaveTime(
                    max(0, $activeLeaveAllowed->sick_leave->total('seconds') - $leaveAvailable->sick_leave->total('seconds'))
                )
            ]
        ];
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

        \DB::transaction(function () use ($user, $year) {
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
     * Get current balance for a specific leave type.
     *
     * @param LeaveAvailable $leaveAvailable
     * @param string $leaveType
     * @return int
     * @throws Exception
     */
    private function getCurrentBalanceForType(LeaveAvailable $leaveAvailable, string $leaveType): int
    {
        switch ($leaveType) {
            case 'Earned':
                return $leaveAvailable->earned_leave->total('seconds');
            case 'Casual':
                return $leaveAvailable->casual_leave->total('seconds');
            case 'Sick':
                return $leaveAvailable->sick_leave->total('seconds');
            default:
                throw new Exception('Invalid leave type.');
        }
    }

    /**
     * Parse leave time string to seconds.
     *
     * @param string $timeString (HH:MM:SS format)
     * @return int
     */
    private function parseLeaveTime(string $timeString): int
    {
        $parts = explode(':', $timeString);
        if (count($parts) !== 3) {
            throw new Exception('Invalid time format. Expected HH:MM:SS');
        }

        [$hours, $minutes, $seconds] = array_map('intval', $parts);
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    /**
     * Format seconds to HH:MM:SS string.
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
