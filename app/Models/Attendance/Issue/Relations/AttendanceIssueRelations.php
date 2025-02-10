<?php

namespace App\Models\Attendance\Issue\Relations;

use App\Models\Attendance\Attendance;
use App\Models\User;
use App\Models\EmployeeShift\EmployeeShift;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait AttendanceIssueRelations
{
    /**
     * Get the user for the attendance issue.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the attendance for the attendance issue.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class);
    }
    
    /**
     * Get the updater for the attendance issue.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    /**
     * Get the employee_shift for the attendance.
     */
    public function employee_shift(): BelongsTo
    {
        return $this->belongsTo(EmployeeShift::class);
    }
}