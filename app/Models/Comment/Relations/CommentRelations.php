<?php

namespace App\Models\Comment\Relations;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CommentRelations
{
    /**
     * Get the commentable model that the comment belongs to.
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the user that created the comment.
     */
    public function commenter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the files associated with the comment.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
