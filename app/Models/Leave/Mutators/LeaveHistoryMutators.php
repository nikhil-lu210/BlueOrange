<?php

namespace App\Models\Leave\Mutators;

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
        if ($value instanceof CarbonInterval) {
            return $value->format('%H:%I:%S');
        }

        return $value; // Assuming the input is already a valid time string
    }
}
