<?php

namespace App\Models\Penalty\Accessors;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait PenaltyAccessors
{
    /**
     * Get the penalty time in hours and minutes format (e.g., "2h 30m")
     */
    protected function totalTimeFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $totalMinutes = $this->total_time;
                
                if ($totalMinutes < 60) {
                    return $totalMinutes . 'm';
                }
                
                $hours = intval($totalMinutes / 60);
                $minutes = $totalMinutes % 60;
                
                if ($minutes > 0) {
                    return $hours . 'h ' . $minutes . 'm';
                }
                
                return $hours . 'h';
            }
        );
    }

    /**
     * Get the penalty type with proper formatting
     */
    protected function typeFormatted(): Attribute
    {
        return Attribute::make(
            get: function () {
                return ucwords(str_replace('_', ' ', $this->type));
            }
        );
    }
}
