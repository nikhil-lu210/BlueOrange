<?php

namespace App\Models\Task\Traits;

use App\Models\Task\Task;
use App\Models\Task\TaskFile;
use App\Models\Task\TaskComment;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TaskCommentRelations
{
    /**
     * Get the task for the TaskComment.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }


    /**
     * Get the files associated with the taskComment.
     */
    public function files(): HasMany
    {
        return $this->hasMany(TaskFile::class)
                    ->whereNotNull('task_comment_id')
                    ->whereNull('task_id')
                    ->whereNull('task_history_id');
    }
}