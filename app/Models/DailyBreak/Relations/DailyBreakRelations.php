<?php

namespace App\Models\DailyBreak\Relations;

use App\Models\User;
use App\Models\Attendance\Attendance;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait DailyBreakRelations
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