<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use App\Models\Chatting\Chatting;

class ChatBody extends Component
{
    public $messages;
    public $receiver;
    public $newMessage;

    protected $listeners = ['messageSent' => 'loadMessages'];

    public function mount($user)
    {
        $this->receiver = $user;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if ($this->receiver) {
            $this->messages = Chatting::with(['sender.media', 'receiver.media'])->where(function ($query) {
                    $query->where('sender_id', auth()->user()->id)
                        ->where('receiver_id', $this->receiver->id);
                })
                ->orWhere(function ($query) {
                    $query->where('sender_id', $this->receiver->id)
                        ->where('receiver_id', auth()->user()->id);
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark all messages from the receiver as seen
            Chatting::where('sender_id', $this->receiver->id)
                ->where('receiver_id', auth()->user()->id)
                ->whereNull('seen_at')
                ->update(['seen_at' => now()]);
        }
    }


    public function sendMessage()
    {
        Chatting::create([
            'sender_id' => auth()->user()->id,
            'receiver_id' => $this->receiver->id,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
    }


    public function render()
    {
        return view('livewire.administration.chatting.chat-body', [
            'messages' => $this->messages,
            'newMessage' => $this->newMessage,
            'receiver' => $this->receiver
        ]);
    }
}