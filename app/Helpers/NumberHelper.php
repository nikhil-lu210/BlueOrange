<?php

use Illuminate\Support\Number;

if (!function_exists('format_number')) {

    /**
     * Format a number with locale-specific thousands separator and decimal point.
     *
     * @param int|float|null $number The number to be formatted. If null, returns an empty string.
     * @return string The formatted number as a string.
     */
    function format_number($number)
    {
        return $number === null ? null : Number::format($number);
    }
}
