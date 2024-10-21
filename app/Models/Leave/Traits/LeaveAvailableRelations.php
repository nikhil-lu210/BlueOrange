<?php

namespace App\Models\Leave\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait LeaveAvailableRelations
{
    /**
     * Get the user for the leave_available.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}