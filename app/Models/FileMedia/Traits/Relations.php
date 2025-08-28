<?php

namespace App\Models\FileMedia\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relations
{
    /**
     * Get the uploader for the file_media.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
