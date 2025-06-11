<?php

namespace App\Models\Penalty\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PenaltyMutators
{
    /**
     * Set the penalty reason with proper formatting
     */
    protected function reason(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return trim($value);
            }
        );
    }

    /**
     * Ensure total_time is always a positive integer
     */
    protected function totalTime(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                return max(0, intval($value));
            }
        );
    }
}
