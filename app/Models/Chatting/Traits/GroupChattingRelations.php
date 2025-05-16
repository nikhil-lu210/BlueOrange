<?php

namespace App\Models\Chatting\Traits;

use App\Models\Chatting\ChattingGroup;
use App\Models\Chatting\GroupChatFileMedia;
use App\Models\Chatting\GroupChatting;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait GroupChattingRelations
{
    /**
     * Get the group for the message.
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(ChattingGroup::class, 'chatting_group_id');
    }


    /**
     * Get the sender for the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function readByUsers()
    {
        return $this->belongsToMany(User::class, 'group_chat_reads', 'group_chatting_id', 'user_id')
            ->withTimestamps();
    }

    /**
     * Get all files attached to this message
     */
    public function files(): HasMany
    {
        return $this->hasMany(GroupChatFileMedia::class, 'group_chatting_id');
    }

    /**
     * Get the message this message is replying to
     */
    public function reply_to(): BelongsTo
    {
        return $this->belongsTo(GroupChatting::class, 'reply_to_id');
    }

    /**
     * Get all replies to this message
     */
    public function replies(): HasMany
    {
        return $this->hasMany(GroupChatting::class, 'reply_to_id');
    }
}
