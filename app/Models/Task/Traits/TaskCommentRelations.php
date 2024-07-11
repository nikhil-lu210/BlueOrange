<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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
     * Get the user for the task comment.
     */
    public function commenter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the files associated with the task_comment.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}