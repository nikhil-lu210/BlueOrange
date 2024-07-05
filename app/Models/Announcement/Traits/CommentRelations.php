<?php

namespace App\Models\Announcement\Traits;

use App\Models\Announcement\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait CommentRelations
{
    /**
     * Get the announcement for the announcement comment.
     */
    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    
    /**
     * Get the user for the announcement comment.
     */
    public function commenter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'commenter_id');
    }
}