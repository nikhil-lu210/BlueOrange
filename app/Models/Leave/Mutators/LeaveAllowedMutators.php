<?php

namespace App\Models\Leave\Mutators;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait LeaveAllowedMutators
{
    /**
     * Mutator for earned_leave.
     * Converts the given value (CarbonInterval or hh:mm:ss string) into a formatted time string (hh:mm:ss) and stores it.
     *
     * @param CarbonInterval|string $value
     * @return void
     */
    public function setEarnedLeaveAttribute($value): void
    {
        $this->attributes['earned_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for casual_leave.
     * Converts the given value (CarbonInterval or hh:mm:ss string) into a formatted time string (hh:mm:ss) and stores it.
     *
     * @param CarbonInterval|string $value
     * @return void
     */
    public function setCasualLeaveAttribute($value): void
    {
        $this->attributes['casual_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for sick_leave.
     * Converts the given value (CarbonInterval or hh:mm:ss string) into a formatted time string (hh:mm:ss) and stores it.
     *
     * @param CarbonInterval|string $value
     * @return void
     */
    public function setSickLeaveAttribute($value): void
    {
        $this->attributes['sick_leave'] = $this->convertToTimeString($value);
    }

    /**
     * Mutator for implemented_from.
     * Stores the given value directly without parsing it.
     *
     * @param string $value
     * @return void
     */
    public function setImplementedFromAttribute($value): void
    {
        // Assume $value is already in 'mm-dd' format, so just assign it directly.
        $this->attributes['implemented_from'] = $value;
    }

    /**
     * Mutator for implemented_to.
     * Stores the given value directly without parsing it.
     *
     * @param string $value
     * @return void
     */
    public function setImplementedToAttribute($value): void
    {
        // Assume $value is already in 'mm-dd' format, so just assign it directly.
        $this->attributes['implemented_to'] = $value;
    }


    /**
     * Helper method to convert a time value (CarbonInterval or hh:mm:ss string) into a string format.
     * 
     * @param CarbonInterval|string $value
     * @return string
     */
    private function convertToTimeString($value): string
    {
        if ($value instanceof CarbonInterval) {
            return $value->format('%H:%I:%S');
        }

        // If value is already in hh:mm:ss format, return as is
        return $value;
    }
}
