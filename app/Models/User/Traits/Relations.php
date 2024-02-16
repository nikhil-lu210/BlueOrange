<?php

namespace App\Models\User\Traits;

use App\Models\Attendance\Attendance;
use App\Models\EmployeeShift\EmployeeShift;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Relations
{    
    /**
     * Get the employee_shifts associated with the user.
     */
    public function employee_shifts(): HasMany
    {
        return $this->hasMany(EmployeeShift::class);
    }

    /**
     * Get currently active employee_shift
     */
    public function getCurrentShiftAttribute()
    {
        return $this->employee_shifts()->where('status', 'active')->latest()->first();
    }

    /**
     * Get the attendances associated with the user.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}