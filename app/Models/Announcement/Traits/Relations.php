<?php

namespace App\Models\Announcement\Traits;

use App\Models\User;
use App\Models\FileMedia\FileMedia;
use App\Models\Announcement\AnnouncementComment;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
     * Get the comments associated with the announcement.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(AnnouncementComment::class);
    }

    /**
     * Get the files associated with the task.
     */
    public function files(): MorphMany
    {
        return $this->morphMany(FileMedia::class, 'fileable');
    }
}
