<?php

namespace App\Models\WorkingShift\Traits;

use App\Models\User;
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
}