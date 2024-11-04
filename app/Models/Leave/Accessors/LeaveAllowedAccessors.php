<?php

namespace App\Models\Leave\Accessors;

use Carbon\Carbon;
use Carbon\CarbonInterval;

trait LeaveAllowedAccessors
{
    /**
     * Accessor for implemented_from.
     * Converts the stored string (mm-dd) into a Carbon date object.
     *
     * @param string $value
     * @return Carbon
     */
    public function getImplementedFromAttribute(string $value): Carbon
    {
        return Carbon::createFromFormat('m-d', $value);
    }

    /**
     * Accessor for implemented_to.
     * Converts the stored string (mm-dd) into a Carbon date object.
     *
     * @param string $value
     * @return Carbon
     */
    public function getImplementedToAttribute(string $value): Carbon
    {
        return Carbon::createFromFormat('m-d', $value);
    }

    /**
     * Accessor for earned_leave (Convert to CarbonInterval)
     */
    public function getEarnedLeaveAttribute($value): CarbonInterval
    {
        return $this->convertToCarbonInterval($value);
    }

    /**
     * Accessor for casual_leave (Convert to CarbonInterval)
     */
    public function getCasualLeaveAttribute($value): CarbonInterval
    {
        return $this->convertToCarbonInterval($value);
    }

    /**
     * Accessor for sick_leave (Convert to CarbonInterval)
     */
    public function getSickLeaveAttribute($value): CarbonInterval
    {
        return $this->convertToCarbonInterval($value);
    }

    /**
     * Convert "HH:MM:SS" format to CarbonInterval
     */
    private function convertToCarbonInterval($value): CarbonInterval
    {
        $timeParts = explode(':', $value);

        if (count($timeParts) === 3) {
            [$hours, $minutes, $seconds] = $timeParts;

            return CarbonInterval::hours($hours)->minutes($minutes)->seconds($seconds);
        }

        return CarbonInterval::hours(0);
    }
}
