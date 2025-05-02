<?php

namespace App\Models\Attendance\Accessors;

trait AttendanceAccessors
{
    /**
     * Get the total number of breaks taken for the attendance.
     * This accessor uses the preloaded daily_breaks relationship when available
     */
    public function getTotalBreaksTakenAttribute(): int
    {
        // If daily_breaks is already loaded, use the collection to avoid a new query
        if ($this->relationLoaded('daily_breaks')) {
            return $this->daily_breaks->where('break_out_at', '!=', null)->count();
        }

        // Otherwise, perform a query
        return $this->daily_breaks()
            ->whereNotNull('break_out_at')
            ->count();
    }

    /**
     * Get the total break time for the attendance.
     * This accessor uses the preloaded daily_breaks relationship when available
     */
    public function getTotalBreakTimeAttribute()
    {
        // If daily_breaks is already loaded, calculate from the collection
        if ($this->relationLoaded('daily_breaks')) {
            $totalSeconds = 0;
            foreach ($this->daily_breaks->where('break_out_at', '!=', null) as $break) {
                if ($break->total_time) {
                    list($hours, $minutes, $seconds) = explode(':', $break->total_time);
                    $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                }
            }

            if ($totalSeconds > 0) {
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }

            return null;
        }

        // Otherwise, perform a query
        return $this->daily_breaks()
            ->whereNotNull('break_out_at')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))) as total_break_time')
            ->value('total_break_time');
    }

    /**
     * Get the total over break time for the attendance.
     * This accessor uses the preloaded daily_breaks relationship when available
     */
    public function getTotalOverBreakAttribute()
    {
        // If daily_breaks is already loaded, calculate from the collection
        if ($this->relationLoaded('daily_breaks')) {
            $totalSeconds = 0;
            foreach ($this->daily_breaks->where('break_out_at', '!=', null) as $break) {
                if ($break->over_break) {
                    list($hours, $minutes, $seconds) = explode(':', $break->over_break);
                    $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
                }
            }

            if ($totalSeconds > 0) {
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }

            return null;
        }

        // Otherwise, perform a query
        return $this->daily_breaks()
            ->whereNotNull('break_out_at')
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(over_break))) as total_over_break')
            ->value('total_over_break');
    }
}
