<?php

namespace App\Models\Announcement\Traits;

use App\Models\User;
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
}