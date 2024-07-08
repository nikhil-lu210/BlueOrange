<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\Task\TaskFile;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Get the files associated with the taskHistory.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class)
                    ->whereNotNull('task_history_id')
                    ->whereNull('task_id')
                    ->whereNull('task_comment_id');
    }
}