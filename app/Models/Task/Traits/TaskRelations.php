<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\TaskComment;
use App\Models\Task\TaskHistory;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TaskRelations
{
    /**
     * Get the creator for the tasl.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }


    /**
     * Get the users associated with the task.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('progress')->withTimestamps();
    }


    /**
     * Get the histories associated with the task.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(TaskHistory::class);
    }


    /**
     * Get the comments associated with the task.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class);
    }

    /**
     * Get the files associated with the task.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}