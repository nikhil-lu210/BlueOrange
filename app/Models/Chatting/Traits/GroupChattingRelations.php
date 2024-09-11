<?php

namespace App\Models\Chatting\Traits;

use App\Models\Chatting\ChattingGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}