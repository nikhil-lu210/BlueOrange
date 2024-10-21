<?php

namespace App\Models\Leave\Traits;

use App\Models\Leave\LeaveAllowed;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait LeaveHistoryRelations
{
    /**
     * Get the user for the leave_history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the leave_allowed associated with the leave_history.
     */
    public function leave_allowed(): BelongsTo
    {
        return $this->belongsTo(LeaveAllowed::class);
    }

    /**
     * Get the reviewer associated with the leave_history.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}