<?php

use Carbon\Carbon;

if (!function_exists('show_date')) {

    /**
     * Format a timestamp or date to a custom formatted date.
     *
     * @param  string  $datetime
     * @param  string  $format
     * @return string
     */
    function show_date($datetime, $format = 'jS F, Y (l)')
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format);
    }
}


if (!function_exists('show_time')) {

    /**
     * Format a timestamp or date to a custom formatted time.
     *
     * @param  string  $datetime
     * @return string
     */
    function show_time($datetime)
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format('h:i:s A');
    }
}


if (!function_exists('show_date_time')) {

    /**
     * Format a timestamp or date to a custom formatted date and time.
     *
     * @param  string  $datetime
     * @param  string  $format
     * @return string
     */
    function show_date_time($datetime, $format = 'jS F, Y (l) \a\t h:i:s A')
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->format($format);
    }
}


if (!function_exists('date_time_ago')) {

    /**
     * Get the time difference between a timestamp or date and the current time in a human-readable format.
     *
     * @param  string  $datetime
     * @return string
     */
    function date_time_ago($datetime)
    {
        $carbon = Carbon::parse($datetime);
        return $carbon->diffForHumans();
    }
}



if (!function_exists('total_time')) {

    /**
     * Format total time and apply text color based on conditions.
     *
     * @param  string  $totalTime
     * @return string
     */
    function total_time($totalTime)
    {
        // Parse the total time string
        $timeComponents = explode(':', $totalTime);
        $hours = (int)$timeComponents[0];
        $minutes = (int)$timeComponents[1];
        $seconds = (int)$timeComponents[2];

        // Determine text color based on conditions
        $textColor = 'text-success'; // Default color

        if ($hours < 8) {
            $textColor = 'text-danger';
        } elseif ($hours > 10) {
            $textColor = 'text-warning';
        }

        // Format the total time based on relevant parts
        if ($hours > 0) {
            $formattedTotalTime = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            $formattedTotalTime = sprintf('%02dm %02ds', $minutes, $seconds);
        } else {
            $formattedTotalTime = sprintf('%02ds', $seconds);
        }

        // Return the formatted total time with the appropriate text color
        return "<span class='$textColor'>$formattedTotalTime</span>";
    }
}
