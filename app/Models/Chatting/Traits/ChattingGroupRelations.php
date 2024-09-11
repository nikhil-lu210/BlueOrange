<?php

namespace App\Models\Chatting\Traits;

use App\Models\User;
use App\Models\Chatting\GroupChatting;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait ChattingGroupRelations
{    
    /**
     * Get the group_messages for the chatting_group.
     */
    public function group_messages(): HasMany
    {
        return $this->belongsTo(GroupChatting::class);
    }
    
    
    /**
     * Get the creator of the chatting_group.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }


    /**
     * Get the group_users associated with the chatting_group.
     */
    public function group_users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('role')->withTimestamps();
    }
}