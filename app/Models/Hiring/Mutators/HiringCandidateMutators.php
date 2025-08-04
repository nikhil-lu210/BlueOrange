<?php

namespace App\Models\Hiring\Mutators;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HiringCandidateMutators
{
    /**
     * Set the name attribute
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucwords(strtolower(trim($value)))
        );
    }

    /**
     * Set the email attribute
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower(trim($value))
        );
    }

    /**
     * Set the phone attribute
     */
    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => preg_replace('/[^0-9+\-\s]/', '', trim($value))
        );
    }

    /**
     * Set the expected role attribute
     */
    protected function expectedRole(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => ucwords(strtolower(trim($value)))
        );
    }
}
