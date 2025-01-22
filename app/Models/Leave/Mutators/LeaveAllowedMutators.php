<?php

namespace App\Models\Leave\Mutators;

use Exception;
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
            // dd('Error from LeaveAllowedMutators: '. $e->getMessage());
            // Return original if unprocessable
            return $value;
        }
    }
}
