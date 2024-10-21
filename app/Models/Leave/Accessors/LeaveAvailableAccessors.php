<?php

namespace App\Models\Leave\Accessors;

use Carbon\CarbonInterval;
use Illuminate\Support\Carbon;

trait LeaveAvailableAccessors
{
    /**
     * Accessor for earned_leave (Convert to CarbonInterval)
     */
    public function getEarnedLeaveAttribute($value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for casual_leave (Convert to CarbonInterval)
     */
    public function getCasualLeaveAttribute($value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for sick_leave (Convert to CarbonInterval)
     */
    public function getSickLeaveAttribute($value): CarbonInterval
    {
        return CarbonInterval::fromString($value);
    }

    /**
     * Accessor for for_year (Convert to Carbon instance)
     */
    public function getForYearAttribute($value): Carbon
    {
        return Carbon::createFromFormat('Y', $value);
    }
}
