<?php

namespace App\Models\WorkSchedule\Relations;

use App\Models\User;
use App\Models\WorkScheduleItem\WorkScheduleItem;
use App\Models\EmployeeShift\EmployeeShift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait WorkScheduleRelations
{
    /**
     * Get the user that owns the work schedule.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the employee shift for the work schedule.
     */
    public function employeeShift(): BelongsTo
    {
        return $this->belongsTo(EmployeeShift::class);
    }

    /**
     * Get the work schedule items for the work schedule.
     */
    public function workScheduleItems(): HasMany
    {
        return $this->hasMany(WorkScheduleItem::class);
    }
}
