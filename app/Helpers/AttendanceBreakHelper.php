<?php

use Carbon\Carbon;
use App\Models\DailyBreak\DailyBreak;

if (!function_exists('over_break')) {
    
    /**
     * Get the overbreak time in hh:mm:ss format based on the break ID.
     *
     * @param int $breakId The ID of the break.
     * @return string The overbreak time in hh:mm:ss or '00:00:00' if there's no overbreak.
     */
    function over_break($breakId)
    {
        // Retrieve the break record by ID
        $dailyBreak = DailyBreak::find($breakId);
    
        // Check if the break exists
        if (!$dailyBreak || !$dailyBreak->break_out_at) {
            return NULL; // Return NULL if the break does not exist or has no break out time
        }
    
        // Convert the break in and out times to Carbon instances
        $breakIn = Carbon::parse($dailyBreak->break_in_at);
        $breakOut = Carbon::parse($dailyBreak->break_out_at);
    
        // Calculate the actual break duration in seconds
        $breakDuration = $breakOut->diffInSeconds($breakIn);
    
        // Define the maximum allowed time in seconds for each break type
        $maxAllowedTime = $dailyBreak->type === 'Short' ? 20 * 60 : 45 * 60; // 20 minutes for short, 45 minutes for long
    
        // If the break duration exceeds the allowed time, calculate the overbreak
        if ($breakDuration > $maxAllowedTime) {
            $overbreakInSeconds = $breakDuration - $maxAllowedTime;
            return gmdate('H:i:s', $overbreakInSeconds); // Convert seconds to hh:mm:ss format
        }
    
        // If no overbreak, return NULL
        return NULL;
    }    
}

if (!function_exists('total_over_break')) {
    
    /**
     * Get the total overbreak time in hh:mm:ss format for a given attendance.
     *
     * @param mixed $attendance The attendance record.
     * @return string|null The total overbreak time in hh:mm:ss or null if there's no overbreak.
     */
    function total_over_break($attendance)
    {
        // Initialize total overbreak seconds
        $totalOverbreakSeconds = 0;

        // Retrieve all daily breaks associated with the attendance record
        $dailyBreaks = DailyBreak::where('attendance_id', $attendance->id)->get();

        // Iterate through each daily break to calculate the overbreak
        foreach ($dailyBreaks as $dailyBreak) {
            // Calculate the overbreak for each break and add it to the total
            $overbreak = over_break($dailyBreak->id);
            if ($overbreak !== NULL) {
                // Convert the overbreak time from hh:mm:ss to seconds
                list($hours, $minutes, $seconds) = explode(':', $overbreak);
                $totalOverbreakSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
            }
        }

        // If total overbreak is 0, return null
        if ($totalOverbreakSeconds === 0) {
            return NULL;
        }

        // Convert total overbreak seconds to hh:mm:ss format
        return gmdate('H:i:s', $totalOverbreakSeconds);
    }    
}
