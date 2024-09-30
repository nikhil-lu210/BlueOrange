<?php

namespace App\Models\EmployeeShift\Traits;

use App\Models\Attendance\Attendance;
use App\Models\User;
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
}