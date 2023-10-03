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
