<?php

namespace App\Models\DailyBreak\Traits;

use App\Models\Attendance\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the daily_break.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    
    /**
     * Get the attendance for the daily_break, filtering by type 'Regular'.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class)->where('type', 'Regular');
    }
}