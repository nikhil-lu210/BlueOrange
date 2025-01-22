<?php

namespace App\Imports\Administration\Attendance;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Attendance;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                $user = $this->getUser($row['employee_id']);

                $clockIn = Carbon::parse($row['clock_in']);
                $clockOut = Carbon::parse($row['clock_out']);
                $clockInDate = Carbon::parse($row['clock_in_date'])->format('Y-m-d');
                $type = $row['type'];

                $totalTime = $this->calculateTotalTime($clockIn, $clockOut);
                $formattedTotalTime = $this->formatTime($totalTime);

                $adjustedTotalTime = $this->calculateAdjustedTotalTime($type, $user, $totalTime);

                $this->storeAttendance($user, $clockInDate, $clockIn, $clockOut, $formattedTotalTime, $adjustedTotalTime, $type);
            });
        }
    }

    /**
     * Get the user by employee_id.
     */
    protected function getUser(string $employeeId): User
    {
        return User::where('userid', (string)$employeeId)->firstOrFail();
    }

    /**
     * Calculate total time in seconds between clock-in and clock-out.
     */
    protected function calculateTotalTime(Carbon $clockIn, Carbon $clockOut): int
    {
        return $clockOut->timestamp - $clockIn->timestamp;
    }

    /**
     * Format time from seconds to HH:MM:SS.
     */
    protected function formatTime(int $totalSeconds): string
    {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    /**
     * Calculate adjusted total time based on attendance type.
     */
    protected function calculateAdjustedTotalTime(string $type, User $user, int $totalTime): string
    {
        if ($type === 'Regular') {
            return $this->adjustForShift($user, $totalTime);
        }

        // For Overtime, return total time without adjustment
        return $this->formatTime($totalTime);
    }

    /**
     * Adjust total time based on employee shift.
     */
    protected function adjustForShift(User $user, int $totalTime): string
    {
        $employeeShift = $user->current_shift;

        if (!$employeeShift) {
            return $this->formatTime($totalTime);
        }

        list($shiftHours, $shiftMinutes, $shiftSeconds) = explode(':', $employeeShift->total_time);
        $shiftTotalSeconds = ($shiftHours * 3600) + ($shiftMinutes * 60) + $shiftSeconds;

        // Adjust total time based on shift duration
        $adjustedTotalSeconds = min($totalTime, $shiftTotalSeconds);

        return $this->formatTime($adjustedTotalSeconds);
    }

    /**
     * Store the attendance record in the database.
     */
    protected function storeAttendance(User $user, string $clockInDate, Carbon $clockIn, Carbon $clockOut, string $formattedTotalTime, string $formattedAdjustedTotalTime, string $type)
    {
        Attendance::create([
            'user_id' => $user->id,
            'employee_shift_id' => $user->current_shift->id,
            'type' => $type,
            'clock_in_date' => $clockInDate,
            'clock_in' => $clockIn->format('Y-m-d H:i:s'),
            'clock_out' => $clockOut->format('Y-m-d H:i:s'),
            'total_time' => $formattedTotalTime,
            'total_adjusted_time' => $formattedAdjustedTotalTime,
        ]);
    }

    /**
     * Define validation rules for the import.
     */
    public function rules(): array
    {
        return [
            '*.employee_id' => 'required|exists:users,userid',
            '*.clock_in_date' => 'required|date',
            '*.clock_in' => 'required|date',
            '*.clock_out' => 'required|date|after:*.clock_in',
            '*.type' => 'required|in:Regular,Overtime',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages()
    {
        return [
            '*.employee_id.exists' => 'The User ID :input does not exist in the database.',
            '*.clock_out.after' => 'The Clock Out time must be after the Clock In time.',
        ];
    }
}
