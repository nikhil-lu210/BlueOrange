<?php

namespace App\Models\User\Traits;

use App\Models\Chatting\ChattingGroup;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ChattingRelations
{
    /**
     * Get the chatting_groups associated with the user.
     */
    public function chatting_groups(): BelongsToMany
    {
        return $this->belongsToMany(ChattingGroup::class)->withTimestamps();
    }
}