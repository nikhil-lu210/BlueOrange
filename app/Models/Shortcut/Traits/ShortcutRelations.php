<?php

namespace App\Models\Shortcut\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait ShortcutRelations
{
    /**
     * Get the user for the tasl.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}