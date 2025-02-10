<?php

use Illuminate\Support\Str;

if (!function_exists('serial')) {
    /**
     * Get the serial number with leading zeros based on the given key.
     *
     * @param \Illuminate\Support\Collection $datas The collection of items.
     * @param int $key The key (index) for which the serial number is generated.
     * @return string The serial number with leading zeros.
     */
    function serial($datas, int $key): string
    {
        $totalItems = $datas->count();
        $totalDigits = strlen((string) $totalItems);
        return str_pad($key + 1, $totalDigits, '0', STR_PAD_LEFT);
    }
}


if (!function_exists('show_content')) {
    /**
     * Truncate a sentence to a specified number of characters and append '...' if truncated.
     *
     * @param  string  $sentence
     * @param  int|null  $totalChar
     * @return string
     */
    function show_content($sentence, $totalChar = null)
    {
        if ($totalChar && strlen($sentence) > $totalChar) {
            return substr($sentence, 0, $totalChar) . '...';
        }

        return $sentence;
    }
}




if (!function_exists('show_status')) {
    /**
     * Display a badge with the appropriate color based on the user's status.
     *
     * @param string $status The user's status.
     * @return string The HTML span element with the badge.
     */
    function show_status(string $status): string
    {
        $badgeClass = match($status) {
            'Pending'   => 'bg-label-info',
            'Active'    => 'bg-label-success',
            'Completed' => 'bg-label-success',
            'Approved'  => 'bg-label-success',
            'Inactive'  => 'bg-label-danger',
            'Canceled'  => 'bg-label-danger',
            'Cancelled' => 'bg-label-danger',
            'Rejected'  => 'bg-label-danger',
            'Resigned'  => 'bg-label-dark',
            'Fired'     => 'bg-label-warning',
            default     => 'bg-label-primary',
        };

        return sprintf('<span class="badge %s">%s</span>', $badgeClass, htmlspecialchars($status, ENT_QUOTES, 'UTF-8'));
    }
}



if (!function_exists('show_plural')) {
    /**
     * Convert a singular word to its plural form.
     *
     * @param string $value The singular word.
     * @return string The plural form of the word.
     */
    function show_plural(string $value): string
    {
        return Str::plural($value);
    }
}
