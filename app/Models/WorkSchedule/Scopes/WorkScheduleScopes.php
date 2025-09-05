<?php

namespace App\Models\WorkSchedule\Scopes;

use Illuminate\Database\Eloquent\Builder;

trait WorkScheduleScopes
{
    /**
     * Scope a query to only include active work schedules.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include work schedules for a specific user.
     */
    public function scopeForUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include work schedules for a specific weekday.
     */
    public function scopeForWeekday(Builder $query, string $weekday): Builder
    {
        return $query->where('weekday', $weekday);
    }

    /**
     * Scope a query to only include work schedules for a specific date range.
     */
    public function scopeForDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('work_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to include user and employee shift details.
     */
    public function scopeWithDetails(Builder $query): Builder
    {
        return $query->with(['user', 'employeeShift', 'workScheduleItems']);
    }
}
