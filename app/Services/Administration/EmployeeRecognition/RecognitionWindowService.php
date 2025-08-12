<?php

declare(strict_types=1);

namespace App\Services\Administration\EmployeeRecognition;

use Illuminate\Support\Carbon;

class RecognitionWindowService
{
    /**
     * Check if the current date is within the recognition submission window
     */
    public function isWithinWindow(?Carbon $date = null): bool
    {
        $date = $date ?: now();
        $day = (int) $date->day;

        $start = (int) config('ers.recognition_window.start_day', 1);
        $end   = (int) config('ers.recognition_window.end_day', 15);

        return $day >= $start && $day <= $end;
    }
    
    /**
     * Get the next window start date
     */
    public function getNextWindowStart(): Carbon
    {
        $now = now();
        $startDay = (int) config('ers.recognition_window.start_day', 1);
        
        // If we're past the start day of this month, get next month's window
        if ($now->day > $startDay) {
            return $now->copy()->addMonth()->startOfMonth()->addDays($startDay - 1);
        }
        
        // Otherwise return this month's window
        return $now->copy()->startOfMonth()->addDays($startDay - 1);
    }
    
    /**
     * Get the current window end date
     */
    public function getCurrentWindowEnd(): Carbon
    {
        $now = now();
        $endDay = (int) config('ers.recognition_window.end_day', 15);
        
        return $now->copy()->startOfMonth()->addDays($endDay - 1);
    }
    
    /**
     * Check if a given month is in the past relative to the current month
     */
    public function isPastMonth(Carbon $month): bool
    {
        return $month->copy()->startOfMonth()->lt(now()->startOfMonth());
    }
    
    /**
     * Check if a given month is in the future relative to the current month
     */
    public function isFutureMonth(Carbon $month): bool
    {
        return $month->copy()->startOfMonth()->gt(now()->startOfMonth());
    }
}