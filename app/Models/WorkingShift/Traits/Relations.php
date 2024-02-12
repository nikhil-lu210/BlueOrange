<?php

namespace App\Models\WorkingShift\Traits;

use App\Models\Attendance\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the shift.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the attendances associated with the shift.
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}