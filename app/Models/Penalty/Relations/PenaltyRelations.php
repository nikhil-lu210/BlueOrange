<?php

namespace App\Models\Penalty\Relations;

use App\Models\User;
use App\Models\Attendance\Attendance;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait PenaltyRelations
{
    /**
     * Get the user (employee) who received the penalty.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the attendance record related to this penalty.
     */
    public function attendance(): BelongsTo
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    /**
     * Get the user who created the penalty.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all files associated with this penalty.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
