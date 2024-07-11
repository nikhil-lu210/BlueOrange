<?php

use Carbon\Carbon;

if (!function_exists('get_time_only')) {

    /**
     * Get the time only from a timestamp or date string in the format "hh:mm:ss".
     *
     * @param  int|string|null  $datetime
     * @return string
     */
    function get_time_only($datetime = null)
    {
        if ($datetime === null) {
            $timestamp = time(); // Use current timestamp if none is provided
        } else {
            $timestamp = is_numeric($datetime) ? $datetime : strtotime($datetime);
        }

        $time = date('H:i:s', $timestamp);

        return $time;
    }
}



if (!function_exists('get_date_only')) {
    /**
     * Get the date only from a timestamp in the format "Y-m-d".
     *
     * @param  Carbon|int|string|null  $timestamp
     * @return string
     */
    function get_date_only($timestamp = null)
    {
        if ($timestamp === null) {
            $timestamp = time(); // Use current timestamp if none is provided
        } elseif ($timestamp instanceof Carbon) {
            $timestamp = $timestamp->timestamp; // Convert Carbon instance to timestamp
        } elseif (is_string($timestamp)) {
            $timestamp = strtotime($timestamp); // Convert string to timestamp
        }

        $date = date('Y-m-d', $timestamp);

        return $date;
    }
}




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

if (!function_exists('total_time_difference')) {

    /**
     * Get the total time difference between a start time and end time in the format "hh:mm:ss".
     *
     * @param  string  $startTime
     * @param  string  $endTime
     * @return string
     */
    function total_time_difference($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);

        $diff = $endDateTime->diff($startDateTime);

        $hours = $diff->format('%H');
        $minutes = $diff->format('%I');
        $seconds = $diff->format('%S');

        return sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
    }
}

if (!function_exists('get_total_hour')) {

    /**
     * Get the total hours difference between a start time and end time.
     *
     * @param  string  $startTime
     * @param  string  $endTime
     * @return int
     */
    function get_total_hour($startTime, $endTime)
    {
        $startDateTime = Carbon::parse($startTime);
        $endDateTime = Carbon::parse($endTime);

        $diffInHours = $endDateTime->diffInHours($startDateTime);

        return $diffInHours;
    }
}

if (!function_exists('total_day')) {

    /**
     * Calculate total days between $startDate and $endDate.
     * Return total years, months, and days.
     *
     * @param  string  $startDate
     * @param  string|null  $endDate
     * @return string
     */
    function total_day($startDate, $endDate = null)
    {
        // Convert start and end dates to DateTime objects
        $startDate = new DateTime($startDate);
        $endDate = $endDate ? new DateTime($endDate) : new DateTime();

        // Calculate the interval between the two dates
        $interval = $startDate->diff($endDate);

        // Extract years, months, and days from the interval
        $years = $interval->y;
        $months = $interval->m;
        $days = $interval->d;

        // Build the formatted total day string
        $formattedTotalDay = '';

        if ($years > 0) {
            $formattedTotalDay .= "$years " . ($years == 1 ? 'Year' : 'Years') . ' ';
        }

        if ($months > 0) {
            $formattedTotalDay .= "$months " . ($months == 1 ? 'Month' : 'Months') . ' ';
        }

        $formattedTotalDay .= "$days " . ($days == 1 ? 'Day' : 'Days');

        // Return the formatted total day
        return $formattedTotalDay;
    }
}




if (!function_exists('total_time_with_min_hour')) {

    /**
     * Format total time and apply text color based on conditions.
     *
     * @param  string  $totalTime
     * @return string
     */
    function total_time_with_min_hour($totalTime, $minHour = 8)
    {
        // Parse the total time string
        $timeComponents = explode(':', $totalTime);
        $hours = (int)$timeComponents[0];
        $minutes = (int)$timeComponents[1];
        $seconds = (int)$timeComponents[2];

        // Determine text color based on conditions
        $textColor = 'text-success'; // Default color

        if ($hours < $minHour) {
            $textColor = 'text-danger';
        } elseif ($hours > ($minHour + 2)) {
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


if (!function_exists('total_time')) {

    /**
     * Format total time.
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

        // Format the total time based on relevant parts
        if ($hours > 0) {
            $formattedTotalTime = sprintf('%02dh %02dm %02ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            $formattedTotalTime = sprintf('%02dm %02ds', $minutes, $seconds);
        } else {
            $formattedTotalTime = sprintf('%02ds', $seconds);
        }

        // Return the formatted total time
        return $formattedTotalTime;
    }
}
