<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait TaskHistoryRelations
{
    /**
     * Get the task for the TaskHistory.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    /**
     * Get the user for the TaskHistory.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the files associated with the task_history.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}