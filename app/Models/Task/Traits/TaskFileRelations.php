<?php

namespace App\Models\Task\Traits;

use App\Models\Task\Task;
use App\Models\Task\TaskComment;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TaskFileRelations
{
    /**
     * Get the task for the TaskFile.
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    /**
     * Get the comment for the TaskFile.
     */
    public function comment(): BelongsTo
    {
        return $this->belongsTo(TaskComment::class);
    }
}