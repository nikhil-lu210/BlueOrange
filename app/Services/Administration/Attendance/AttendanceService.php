<?php

namespace App\Services\Administration\Attendance;

use Carbon\Carbon;
use App\Models\Attendance\Attendance;

class AttendanceService
{
    /**
     * Calculate the total worked days for the user.
     */
    public function calculateTotalWorkedDays($user)
    {
        // Get the total distinct worked days from the attendances table
        return Attendance::where('user_id', $user->id)
            ->distinct('clock_in_date') // Get distinct clock_in_date to count unique days
            ->count('clock_in_date');
    }

    /**
     * Calculate total work for a specific type (Regular or Overtime).
     */
    public function calculateTotalWork($user, $type)
    {
        // Fetch all the total_adjusted_time values for type
        $totalTimes = Attendance::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNotNull('clock_out')
            ->pluck('total_adjusted_time'); // Get all the total_adjusted_time values

        // Initialize the total seconds variable
        $totalSeconds = 0;

        // Convert each total_adjusted_time (in HH:MM:SS) to seconds and add it to totalSeconds
        foreach ($totalTimes as $time) {
            $totalSeconds += $this->timeToSeconds($time);
        }

        // Convert total seconds into HH:MM:SS format and return
        return $this->secondsToTimeFormat($totalSeconds);
    }

    /**
     * Calculate total working hours for a specific type and month.
     */
    public function totalWorkingHour($user, $type, $month = null)
    {
        // Set the default month to the current month if not provided
        $month = $month ?: date('Y-m-d');

        // Fetch all the total_adjusted_time values for the given type and month
        $totalTimes = Attendance::where('user_id', $user->id)
            ->where('type', $type)
            ->whereNotNull('clock_out')
            ->whereBetween('clock_in_date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ])
            ->pluck('total_adjusted_time'); // Get all total_adjusted_time values

        // Initialize the total seconds variable
        $totalSeconds = 0;

        // Convert each total_adjusted_time (in HH:MM:SS) to seconds and add to totalSeconds
        foreach ($totalTimes as $time) {
            $totalSeconds += $this->timeToSeconds($time);
        }

        // Convert total seconds into HH:MM:SS format and return
        return $this->secondsToTimeFormat($totalSeconds);
    }


    /**
     * Get user's total working hours for a specific month and type.
     *
     * @param  mixed $user
     * @param  string|null $month
     * @param  string|null $type ('Regular' or 'Overtime', or null for both)
     */
    public function userTotalWorkingHour($user, $type = null, $month = null)
    {
        $attendances = Attendance::where('user_id', $user->id)->whereNotNull('clock_out');

        // Filter by type if provided
        if ($type) {
            $attendances->where('type', $type);
        }

        // Filter by month if provided
        if ($month) {
            $attendances->whereBetween('clock_in_date', [
                Carbon::parse($month)->startOfMonth()->format('Y-m-d'),
                Carbon::parse($month)->endOfMonth()->format('Y-m-d')
            ]);
        }

        // Get all total_adjusted_time values for the filtered attendances
        $totalTimes = $attendances->pluck('total_adjusted_time');

        $totalSeconds = 0;
        foreach ($totalTimes as $time) {
            $totalSeconds += $this->timeToSeconds($time);
        }

        return $this->secondsToTimeFormat($totalSeconds);
    }


    /**
     * Get the user's total break time.
     *
     * @param  mixed $user
     * @param  string|null $month
     * @return string
     */
    public function userTotalBreakTime($user, $month = null)
    {
        // Get attendance IDs for the user and month
        $query = Attendance::where('user_id', $user->id);

        // Filter by month if provided
        if ($month) {
            $query->whereBetween('clock_in_date', [
                Carbon::parse($month)->startOfMonth()->format('Y-m-d'),
                Carbon::parse($month)->endOfMonth()->format('Y-m-d')
            ]);
        }

        $attendanceIds = $query->pluck('id');

        // Use a single query to get the sum of total_time from daily_breaks
        $totalBreakTime = \App\Models\DailyBreak\DailyBreak::whereIn('attendance_id', $attendanceIds)
            ->whereNotNull('break_out_at')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))) as total_break_time')
            ->value('total_break_time');

        if (!$totalBreakTime) {
            return '00:00:00';
        }

        return $totalBreakTime;
    }

    /**
     * Get the user's total overbreak time.
     *
     * @param  mixed $user
     * @param  string|null $month
     * @return string
     */
    public function userTotalOverBreakTime($user, $month = null)
    {
        // Get attendance IDs for the user and month
        $query = Attendance::where('user_id', $user->id);

        // Filter by month if provided
        if ($month) {
            $query->whereBetween('clock_in_date', [
                Carbon::parse($month)->startOfMonth()->format('Y-m-d'),
                Carbon::parse($month)->endOfMonth()->format('Y-m-d')
            ]);
        }

        $attendanceIds = $query->pluck('id');

        // Use a single query to get the sum of over_break from daily_breaks
        $totalOverBreakTime = \App\Models\DailyBreak\DailyBreak::whereIn('attendance_id', $attendanceIds)
            ->whereNotNull('break_out_at')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(over_break))) as total_over_break')
            ->value('total_over_break');

        if (!$totalOverBreakTime) {
            return '00:00:00';
        }

        return $totalOverBreakTime;
    }


    /**
     * Convert time in HH:MM:SS format to seconds.
     */
    private function timeToSeconds($time)
    {
        // Split the time into hours, minutes, and seconds
        list($hours, $minutes, $seconds) = explode(':', $time);

        // Convert the time to seconds
        return ($hours * 3600) + ($minutes * 60) + $seconds;
    }

    /**
     * Convert total seconds to HH:MM:SS format.
     */
    private function secondsToTimeFormat($totalSeconds)
    {
        $hours = floor($totalSeconds / 3600); // Convert total seconds to hours
        $minutes = floor(($totalSeconds % 3600) / 60); // Remaining minutes
        $seconds = $totalSeconds % 60; // Remaining seconds

        // Format the time as HH:MM:SS
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }
}
