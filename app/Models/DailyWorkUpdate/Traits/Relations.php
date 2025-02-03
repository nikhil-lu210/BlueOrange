<?php

namespace App\Models\DailyWorkUpdate\Traits;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Relations
{
    /**
     * Get the user for the daily_work_update.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Get the team_leader for the daily_work_update.
     */
    public function team_leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    /**
     * Get the files associated with the daily_work_update.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}