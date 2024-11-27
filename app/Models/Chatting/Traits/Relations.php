<?php

namespace App\Models\Chatting\Traits;

use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}