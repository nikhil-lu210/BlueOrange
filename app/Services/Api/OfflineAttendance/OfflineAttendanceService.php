<?php

namespace App\Services\Api\OfflineAttendance;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Attendance\Attendance;
use App\Models\Weekend\Weekend;
use App\Models\Holiday\Holiday;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Collection;

class OfflineAttendanceService
{
    /**
     * Authorize user for sensitive operations
     */
    public function authorizeUser(string $email, string $password): array
    {
        $user = User::where('email', $email)
            ->where('status', 'Active')
            ->first();

        if (!$user) {
            throw new Exception('User not found or inactive');
        }

        if (!Hash::check($password, $user->password)) {
            throw new Exception('Invalid credentials');
        }

        if (!$user->hasPermissionTo('Attendance Create')) {
            throw new Exception('You do not have permission to perform this action');
        }

        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'permissions' => ['Attendance Create']
        ];
    }

    /**
     * Get user data by userid
     */
    public function getUserByUserid(string $userid): array
    {
        $user = User::with('employee')
            ->where('userid', $userid)
            ->where('status', 'Active')
            ->first();

        if (!$user) {
            throw new Exception('User not found or inactive');
        }

        return [
            'id' => $user->id,
            'userid' => $user->userid,
            'name' => $user->name,
            'alias_name' => $user->employee?->alias_name ?? $user->name,
            'email' => $user->email
        ];
    }

    /**
     * Check user attendance status
     */
    public function checkUserAttendanceStatus(string $userid): array
    {
        $user = User::where('userid', $userid)
            ->where('status', 'Active')
            ->first();

        if (!$user) {
            throw new Exception('User not found or inactive');
        }

        $openAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();

        return [
            'user_id' => $user->id,
            'userid' => $user->userid,
            'has_open_attendance' => !!$openAttendance,
            'open_attendance_id' => $openAttendance?->id,
            'clock_in_time' => $openAttendance?->clock_in,
            'clock_in_date' => $openAttendance?->clock_in_date
        ];
    }

    /**
     * Get all active users
     */
    public function getAllUsers(): Collection
    {
        return User::with('employee')
            ->where('status', 'Active')
            ->select('id', 'userid', 'name', 'email')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'userid' => $user->userid,
                    'name' => $user->name,
                    'alias_name' => $user->employee?->alias_name ?? $user->name,
                    'email' => $user->email
                ];
            });
    }

    /**
     * Sync attendance records
     */
    public function syncAttendances(array $attendances): array
    {
        $syncedCount = 0;
        $errors = [];
        $syncedRecordIds = [];

        // Pre-load users for optimization
        $users = $this->preloadUsers($attendances);
        $userLookup = $users->keyBy('id');
        $useridLookup = $users->keyBy('userid');

        foreach ($attendances as $index => $attendanceData) {
            try {
                $user = $this->getUserFromAttendanceData($attendanceData, $userLookup, $useridLookup);

                if (!$user) {
                    $userId = $attendanceData['user_id'] ?? $attendanceData['userid'] ?? 'unknown';
                    $errors[] = "User not found for user_id: {$userId}";
                    continue;
                }

                $type = $attendanceData['type'] ?? 'Regular';
                $entryDateTime = $this->extractEntryDateTime($attendanceData);

                $this->processEntryRecord($user, $type, $entryDateTime);

                $syncedCount++;
                $syncedRecordIds[] = $index;

            } catch (Exception $e) {
                $userId = $attendanceData['user_id'] ?? $attendanceData['userid'] ?? 'unknown';
                $errors[] = "Error processing attendance for {$userId}: " . $e->getMessage();

                Log::error('Offline Attendance API: Error processing attendance', [
                    'userid' => $userId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return [
            'synced_count' => $syncedCount,
            'total_count' => count($attendances),
            'synced_record_ids' => $syncedRecordIds,
            'errors' => $errors,
            'success' => $syncedCount === count($attendances) && count($errors) === 0
        ];
    }

    /**
     * Pre-load users for optimization
     */
    private function preloadUsers(array $attendances): Collection
    {
        $userIds = collect($attendances)->pluck('user_id')->filter()->unique();
        $userids = collect($attendances)->pluck('userid')->filter()->unique();

        $users = collect();

        if ($userIds->isNotEmpty()) {
            $users = $users->merge(User::whereIn('id', $userIds)->get());
        }

        if ($userids->isNotEmpty()) {
            $users = $users->merge(User::whereIn('userid', $userids)->get());
        }

        return $users;
    }

    /**
     * Get user from attendance data
     */
    private function getUserFromAttendanceData(array $attendanceData, Collection $userLookup, Collection $useridLookup): ?User
    {
        if (isset($attendanceData['user_id'])) {
            return $userLookup->get($attendanceData['user_id']);
        }

        if (isset($attendanceData['userid'])) {
            return $useridLookup->get($attendanceData['userid']);
        }

        return null;
    }

    /**
     * Extract entry datetime from attendance data
     */
    private function extractEntryDateTime(array $attendanceData): Carbon
    {
        if (isset($attendanceData['entry_date_time'])) {
            return Carbon::parse($attendanceData['entry_date_time']);
        }

        // Handle legacy formats
        if (isset($attendanceData['clock_in_date']) && isset($attendanceData['clock_in'])) {
            return Carbon::parse($attendanceData['clock_in']);
        }

        if (isset($attendanceData['clock_out'])) {
            return Carbon::parse($attendanceData['clock_out']);
        }

        if (isset($attendanceData['timestamp'])) {
            return Carbon::parse($attendanceData['timestamp']);
        }

        throw new Exception('Invalid attendance data format - no recognizable datetime field');
    }

    /**
     * Process an entry record
     */
    private function processEntryRecord(User $user, string $type, Carbon $entryDateTime): Attendance
    {
        $activeShift = $this->getUserActiveShift($user);
        $openAttendance = $this->getUserOpenAttendance($user);

        if ($openAttendance) {
            return $this->processClockOut($openAttendance, $entryDateTime, $activeShift);
        } else {
            return $this->processClockIn($user, $type, $entryDateTime, $activeShift);
        }
    }

    /**
     * Get user's active shift
     */
    private function getUserActiveShift(User $user)
    {
        return $user->employee_shifts()
            ->where('status', 'Active')
            ->latest('created_at')
            ->first();
    }

    /**
     * Get user's open attendance
     */
    private function getUserOpenAttendance(User $user): ?Attendance
    {
        return Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->first();
    }

    /**
     * Process clock-in
     */
    private function processClockIn(User $user, string $type, Carbon $entryDateTime, $activeShift): Attendance
    {
        $this->validateClockInRules($user, $type, $entryDateTime);

        return Attendance::create([
            'user_id' => $user->id,
            'employee_shift_id' => $activeShift->id,
            'clock_in_date' => $entryDateTime->toDateString(),
            'clock_in' => $entryDateTime,
            'type' => $type,
            'clockin_medium' => 'Barcode',
            'ip_address' => request()->ip(),
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    /**
     * Validate clock-in business rules
     */
    private function validateClockInRules(User $user, string $type, Carbon $entryDateTime): void
    {
        $entryDate = $entryDateTime->toDateString();

        // Check weekend rule
        if ($this->isWeekend($entryDateTime) && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Weekend. Please clock in as Overtime.');
        }

        // Check holiday rule
        if ($this->isHoliday($entryDate) && $type === 'Regular') {
            throw new Exception('You cannot Regular Clock-In on Holiday. Please clock in as Overtime.');
        }

        // Check existing regular attendance
        $existingRegularAttendance = Attendance::where('user_id', $user->id)
            ->where('clock_in_date', $entryDate)
            ->where('type', 'Regular')
            ->first();

        if ($existingRegularAttendance && $type === 'Regular') {
            if (is_null($existingRegularAttendance->clock_out)) {
                throw new Exception('You already have an open Regular attendance today.');
            } else {
                throw new Exception('You have already clocked in as Regular today.');
            }
        }
    }

    /**
     * Check if date is weekend
     */
    private function isWeekend(Carbon $date): bool
    {
        return Weekend::where('day', $date->format('l'))
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if date is holiday
     */
    private function isHoliday(string $date): bool
    {
        return Holiday::where('date', $date)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Process clock-out
     */
    private function processClockOut(Attendance $openAttendance, Carbon $entryDateTime, $activeShift): Attendance
    {
        // Ensure minimum time between clock-in and clock-out
        $minClockOutTime = $openAttendance->clock_in->copy()->addMinutes(2);
        if ($entryDateTime < $minClockOutTime) {
            $entryDateTime = $minClockOutTime;
        }

        $totalSeconds = $entryDateTime->diffInSeconds($openAttendance->clock_in);
        $formattedTotalTime = gmdate('H:i:s', $totalSeconds);
        $formattedAdjustedTotalTime = $this->calculateAdjustedTime($openAttendance, $activeShift, $totalSeconds);

        $openAttendance->update([
            'clock_out' => $entryDateTime,
            'total_time' => $formattedTotalTime,
            'total_adjusted_time' => $formattedAdjustedTotalTime,
            'clockout_medium' => 'Barcode',
            'updated_at' => now()
        ]);

        return $openAttendance;
    }

    /**
     * Calculate adjusted time based on shift
     */
    private function calculateAdjustedTime(Attendance $attendance, $activeShift, int $totalSeconds): string
    {
        if ($attendance->type === 'Regular' && $activeShift) {
            list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $activeShift->total_time);
            $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;
            $adjustedTotalSeconds = min($totalSeconds, $shiftTotalSeconds);
            return gmdate('H:i:s', $adjustedTotalSeconds);
        }

        return gmdate('H:i:s', $totalSeconds);
    }
}
