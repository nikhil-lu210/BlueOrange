<?php

namespace App\Models\Hiring\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HiringStageEvaluationMutators
{
    /**
     * Set the notes attribute
     */
    protected function notes(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? trim($value) : null
        );
    }

    /**
     * Set the feedback attribute
     */
    protected function feedback(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ? trim($value) : null
        );
    }

    /**
     * Set the rating attribute
     */
    protected function rating(): Attribute
    {
        return Attribute::make(
            set: function ($value) {
                if ($value === null || $value === '') {
                    return null;
                }
                return max(1, min(10, (int) $value));
            }
        );
    }
}
