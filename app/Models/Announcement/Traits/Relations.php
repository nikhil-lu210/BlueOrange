<?php

namespace App\Models\Announcement\Traits;

use App\Models\User;
use App\Models\Comment\Comment;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Relations
{
    /**
     * Get the user for the announcement.
     */
    public function announcer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'announcer_id');
    }

    /**
     * Get the comments for the it_ticket
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
}
