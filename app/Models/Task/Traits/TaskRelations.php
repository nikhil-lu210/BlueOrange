<?php

namespace App\Models\Task\Traits;

use App\Models\User;
use App\Models\Task\Task;
use App\Models\Comment\Comment;
use App\Models\Task\TaskHistory;
use App\Models\Chatting\Chatting;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
        return $this->belongsToMany(User::class)
                    ->withPivot(['progress', 'has_understood'])
                    ->withTimestamps();
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
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the files associated with the task.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }

    /**
     * Get the chatting that owns the task.
     */
    public function chatting(): BelongsTo
    {
        return $this->belongsTo(Chatting::class);
    }


    /**
     * Get the sub_tasks associated with the task.
     */
    public function sub_tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_task_id', 'id');
    }

    /**
     * Get the parent_task that owns the task.
     */
    public function parent_task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_task_id', 'id');
    }
}
