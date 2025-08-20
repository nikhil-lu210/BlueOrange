<?php

namespace App\Helpers;

class ColorHelper
{
    /**
     * Adjust brightness of a hex color by a percentage.
     * Positive percent lightens, negative percent darkens.
     *
     * @param string $hex   e.g. "#3788d8" or "3788d8"
     * @param int    $percent -100..100
     * @return string Hex color with leading '#'
     */
    public static function adjustBrightness(string $hex, int $percent): string
    {
        $hex = ltrim(trim($hex), '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        if (strlen($hex) !== 6) {
            return '#' . $hex; // fallback
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $factor = max(-100, min(100, $percent)) / 100.0;

        $r = (int)max(0, min(255, $r + (255 * $factor)));
        $g = (int)max(0, min(255, $g + (255 * $factor)));
        $b = (int)max(0, min(255, $b + (255 * $factor)));

        return sprintf('#%02x%02x%02x', $r, $g, $b);
    }
}

