<?php

namespace App\Models\Leave\Relations;

use App\Models\Leave\LeaveHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait LeaveAllowedRelations
{
    /**
     * Get the user for the leave_allowed.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave_histories associated with the leave_allowed.
     */
    public function leave_histories(): HasMany
    {
        return $this->hasMany(LeaveHistory::class);
    }
}