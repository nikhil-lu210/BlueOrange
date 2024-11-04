<?php

namespace App\Models\Leave\Mutators;

use Exception;
use Carbon\CarbonInterval;
use Stevebauman\Purify\Facades\Purify;

trait LeaveHistoryMutators
{
    /**
     * Mutator for total_leave (Convert to hh:mm:ss format string)
     */
    public function setTotalLeaveAttribute($value): void
    {
        $this->attributes['total_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for reason (Sanitize HTML before storing)
     */
    public function setReasonAttribute($value): void
    {
        $this->attributes['reason'] = Purify::clean($value);
    }

    /**
     * Mutator for reviewer_note (Sanitize HTML before storing)
     */
    public function setReviewerNoteAttribute($value): void
    {
        $this->attributes['reviewer_note'] = Purify::clean($value);
    }
    
    
    
    /**
     * Helper method to convert time (CarbonInterval or string) to hh:mm:ss format
     */
    private function convertToTimeString($value): string
    {
        // If the value is already a CarbonInterval, format without cascading to avoid reducing large hour counts
        if ($value instanceof CarbonInterval) {
            return sprintf('%02d:%02d:%02d', $value->hours + ($value->days * 24), $value->minutes, $value->seconds);
        }

        // Handle string format "hh:mm:ss" with large hours
        if (preg_match('/^\d{2,}:\d{2}:\d{2}$/', $value)) {
            return $value;
        }

        try {
            // Attempt to parse the value as a standard interval string if not in hh:mm:ss
            $interval = CarbonInterval::fromString($value);
            return sprintf('%02d:%02d:%02d', $interval->hours + ($interval->days * 24), $interval->minutes, $interval->seconds);
        } catch (Exception $e) {
            dd('Error from LeaveHistoryMutators: '. $e->getMessage());
            // Return original if unprocessable
            return $value;
        }
    }
}
