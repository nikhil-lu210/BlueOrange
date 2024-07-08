<?php

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
            'Active' => 'bg-label-success',
            'Inactive' => 'bg-label-danger',
            'Resigned' => 'bg-label-dark',
            'Fired' => 'bg-label-warning',
            default => 'bg-label-primary',
        };

        return sprintf('<span class="badge %s">%s</span>', $badgeClass, htmlspecialchars($status, ENT_QUOTES, 'UTF-8'));
    }
}
