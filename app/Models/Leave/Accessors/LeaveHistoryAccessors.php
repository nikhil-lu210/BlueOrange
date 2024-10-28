<?php

namespace App\Models\Leave\Accessors;

use Carbon\CarbonInterval;
use Stevebauman\Purify\Facades\Purify;

trait LeaveHistoryAccessors
{
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
