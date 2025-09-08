<?php

namespace App\Models\EmployeeShift\Traits;

use App\Models\User;
use App\Models\Attendance\Attendance;
use App\Models\WorkSchedule\WorkSchedule;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the employee_shift.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances associated with the employee_shift.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get the work schedules associated with the employee_shift.
     */
    public function work_schedules(): HasMany
    {
        return $this->hasMany(WorkSchedule::class);
    }
}
