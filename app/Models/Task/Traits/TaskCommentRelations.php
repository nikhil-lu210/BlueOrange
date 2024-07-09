<?php

namespace App\Models\Task\Traits;

use App\Models\Task\Task;
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
}