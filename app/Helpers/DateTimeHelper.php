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
