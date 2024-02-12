<?php

namespace App\Models\Attendance\Traits;

use App\Models\User;
use App\Models\WorkingShift\Shift;
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
     * Get the shift for the attendance.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}