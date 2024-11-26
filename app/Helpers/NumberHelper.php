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

if (!function_exists('spell_number')) {

    /**
     * Spell a number with given value.
     *
     * @param int|float|null $number The number to be formatted. If null, returns an empty string.
     * @return string The formatted number as a string.
     */
    function spell_number($number)
    {
        return $number === null ? null : Number::spell($number);
    }
}


if (!function_exists('format_currency')) {

    /**
     * Format a number as currency using the Number facade.
     *
     * @param float|null $number The amount to format. If null, returns an empty string.
     * @param string $currencyCode The currency code (e.g., 'BDT', 'USD'). Defaults to 'BDT'.
     * @return string The formatted currency string.
     */
    function format_currency(?float $number, string $currencyCode = 'BDT'): string
    {
        return $number === null ? $currencyCode.' 0.00' : Number::currency($number, $currencyCode);
    }
}
