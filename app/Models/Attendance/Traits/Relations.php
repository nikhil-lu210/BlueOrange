<?php

namespace App\Models\Attendance\Traits;

use App\Models\User;
use App\Models\DailyBreak\DailyBreak;
use App\Models\EmployeeShift\EmployeeShift;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the attendance.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the qr_clockin_scanner for the attendance.
     */
    public function qr_clockin_scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qr_clockin_scanner_id');
    }
    
    /**
     * Get the qr_clockout_scanner for the attendance.
     */
    public function qr_clockout_scanner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'qr_clockout_scanner_id');
    }
    
    /**
     * Get the employee_shift for the attendance.
     */
    public function employee_shift(): BelongsTo
    {
        return $this->belongsTo(EmployeeShift::class);
    }

    /**
     * Get the daily_breaks associated with the attendance.
     */
    public function daily_breaks(): HasMany
    {
        return $this->hasMany(DailyBreak::class);
    }
}