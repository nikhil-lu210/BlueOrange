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
        return CarbonInterval::fromString($value);
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
}
