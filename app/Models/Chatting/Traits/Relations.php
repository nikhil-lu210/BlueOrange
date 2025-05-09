<?php

namespace App\Models\Chatting\Traits;

use App\Models\Task\Task;
use App\Models\User;
use App\Models\Chatting\Chatting;
use App\Models\Chatting\ChatFileMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Relations
{
    /**
     * Get the sender for the message.
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }


    /**
     * Get the receiver for the message.
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }


    /**
     * Get the task associated with the chatting (message)
     */
    public function task(): HasOne
    {
        return $this->hasOne(Task::class);
    }

    /**
     * Get the message this message is replying to
     */
    public function reply_to(): BelongsTo
    {
        return $this->belongsTo(Chatting::class, 'reply_to_id');
    }

    /**
     * Get all replies to this message
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Chatting::class, 'reply_to_id');
    }

    /**
     * Get all files attached to this message
     */
    public function files(): HasMany
    {
        return $this->hasMany(ChatFileMedia::class);
    }
}
