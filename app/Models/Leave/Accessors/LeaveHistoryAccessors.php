<?php

namespace App\Models\Leave\Accessors;

use Carbon\CarbonInterval;
use Stevebauman\Purify\Facades\Purify;

trait LeaveHistoryAccessors
{
    /**
     * Accessor for total_leave (Convert to CarbonInterval)
     */
    public function getTotalLeaveAttribute($value): CarbonInterval
    {
        return $this->convertToCarbonInterval($value);
    }

    /**
     * Accessor for reason (Sanitize HTML)
     */
    public function getReasonAttribute($value): string
    {
        return Purify::clean($value);
    }

    /**
     * Accessor for reviewer_note (Sanitize HTML)
     */
    public function getReviewerNoteAttribute($value): string
    {
        return Purify::clean($value);
    }

    /**
     * Convert "HH:MM:SS" format to CarbonInterval
     */
    private function convertToCarbonInterval($value): CarbonInterval
    {
        // Ensure the value is a string before processing
        if (!is_string($value) || empty($value)) {
            return CarbonInterval::hours(0);
        }

        $timeParts = explode(':', $value);

        if (count($timeParts) === 3) {
            [$hours, $minutes, $seconds] = $timeParts;

            // Ensure all parts are numeric to prevent invalid data
            if (is_numeric($hours) && is_numeric($minutes) && is_numeric($seconds)) {
                return CarbonInterval::hours((int)$hours)->minutes((int)$minutes)->seconds((int)$seconds);
            }
        }

        // Log an error for debugging if the format is incorrect
        // Log::warning('Invalid total_leave format: ' . $value);

        // Return a default value if the input format is incorrect
        return CarbonInterval::hours(0);
    }
}
