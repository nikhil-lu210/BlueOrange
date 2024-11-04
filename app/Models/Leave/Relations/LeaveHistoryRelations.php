<?php

namespace App\Models\Leave\Relations;

use App\Models\User;
use App\Models\Leave\LeaveAllowed;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    /**
     * Get the files associated with the leave_history.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}