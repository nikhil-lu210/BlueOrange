<?php

namespace App\Models\Comment\Relations;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
}
