<?php

namespace App\Models\Leave\Mutators;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait LeaveAvailableMutators
{
    /**
     * Mutator for earned_leave (Convert to hh:mm:ss format string)
     */
    public function setEarnedLeaveAttribute($value): void
    {
        $this->attributes['earned_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for casual_leave (Convert to hh:mm:ss format string)
     */
    public function setCasualLeaveAttribute($value): void
    {
        $this->attributes['casual_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for sick_leave (Convert to hh:mm:ss format string)
     */
    public function setSickLeaveAttribute($value): void
    {
        $this->attributes['sick_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for for_year (Convert to YYYY format)
     */
    public function setForYearAttribute($value): void
    {
        $this->attributes['for_year'] = Carbon::parse($value)->format('Y');
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
