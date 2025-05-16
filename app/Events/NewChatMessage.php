<?php

namespace App\Events;

use App\Models\Chatting\Chatting;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewChatMessage
{
    use Dispatchable, SerializesModels;

    public $message;

    /**
     * Create a new event instance.
     */
    public function __construct(Chatting $message)
    {
        $this->message = $message;
    }

    // Broadcasting methods removed
}
