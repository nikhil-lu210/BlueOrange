<?php

namespace App\Models\WorkSchedule\Accessors;

use Carbon\Carbon;

trait WorkScheduleAccessors
{
    /**
     * Get the total duration of all work schedule items in minutes.
     */
    public function getTotalDurationAttribute(): int
    {
        return $this->workScheduleItems->sum('duration_minutes');
    }

    /**
     * Get the total duration of all work schedule items in hours.
     */
    public function getTotalDurationHoursAttribute(): float
    {
        return round($this->total_duration / 60, 2);
    }

    /**
     * Get the formatted total duration.
     */
    public function getFormattedTotalDurationAttribute(): string
    {
        $hours = floor($this->total_duration / 60);
        $minutes = $this->total_duration % 60;

        if ($hours > 0) {
            return "{$hours}h {$minutes}m";
        }

        return "{$minutes}m";
    }

    /**
     * Get the work breakdown by type.
     */
    public function getWorkBreakdownAttribute(): array
    {
        return $this->workScheduleItems->groupBy('work_type')->map(function ($items) {
            return [
                'count' => $items->count(),
                'total_duration' => $items->sum('duration_minutes'),
                'formatted_duration' => $this->formatDuration($items->sum('duration_minutes')),
            ];
        })->toArray();
    }

    /**
     * Format duration in minutes to readable format.
     */
    private function formatDuration(int $minutes): string
    {
        $hours = floor($minutes / 60);
        $mins = $minutes % 60;

        if ($hours > 0) {
            return "{$hours}h {$mins}m";
        }

        return "{$mins}m";
    }
}
