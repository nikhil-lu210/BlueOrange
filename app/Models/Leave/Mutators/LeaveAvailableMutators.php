<?php

namespace App\Models\Leave\Mutators;

use Exception;
use Carbon\Carbon;
use Carbon\CarbonInterval;

trait LeaveAvailableMutators
{
    public function setEarnedLeaveAttribute($value): void
    {
        $this->attributes['earned_leave'] = $this->convertToTimeString($value);
    }

    public function setCasualLeaveAttribute($value): void
    {
        $this->attributes['casual_leave'] = $this->convertToTimeString($value);
    }

    public function setSickLeaveAttribute($value): void
    {
        $this->attributes['sick_leave'] = $this->convertToTimeString($value);
    }

    public function setForYearAttribute($value): void
    {
        $this->attributes['for_year'] = is_numeric($value) ? $value : Carbon::parse($value)->format('Y');
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
            dd('Error from LeaveAvailableMutators: '. $e->getMessage());
            // Return original if unprocessable
            return $value;
        }
    }
}
