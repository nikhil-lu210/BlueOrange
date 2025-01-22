<?php

namespace App\Models\Attendance\Accessors;

trait AttendanceAccessors
{
    /**
     * Get the total number of breaks taken for the attendance.
     */
    public function getTotalBreaksTakenAttribute(): int
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->count();
    }

    /**
     * Get the total break time for the attendance.
     */
    public function getTotalBreakTimeAttribute()
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(total_time))) as total_break_time')
            ->value('total_break_time') ?? NULL;
    }

    /**
     * Get the total over break time for the attendance.
     */
    public function getTotalOverBreakAttribute()
    {
        return $this->daily_breaks()
            ->whereNotNull('break_out_at') // Only count completed breaks
            ->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(over_break))) as total_over_break')
            ->value('total_over_break') ?? NULL;
    }
}
