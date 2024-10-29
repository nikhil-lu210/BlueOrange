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
     * Accessor for for_year (Convert to Carbon instance)
     */
    public function getForYearAttribute($value): Carbon
    {
        return Carbon::createFromFormat('Y', $value);
    }

    /**
     * Convert "HH:MM:SS" format to CarbonInterval
     */
    private function convertToCarbonInterval($value): CarbonInterval
    {
        $timeParts = explode(':', $value);

        // Ensure the format is valid (HH:MM:SS)
        if (count($timeParts) === 3) {
            [$hours, $minutes, $seconds] = $timeParts;
            $totalSeconds = ($hours * 3600) + ($minutes * 60) + $seconds;

            return CarbonInterval::seconds($totalSeconds);
        }

        // Handle invalid format gracefully
        return CarbonInterval::hours(0);
    }
}
