<?php

namespace App\Models\Announcement\Traits;

use App\Models\Announcement\AnnouncementComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
     * Get the comments associated with the announcement.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(AnnouncementComment::class);
    }
}