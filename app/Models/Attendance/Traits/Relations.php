<?php

namespace App\Models\Attendance\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the user for the coach.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}