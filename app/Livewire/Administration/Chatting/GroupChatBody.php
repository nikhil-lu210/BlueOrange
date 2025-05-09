<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use App\Models\Chatting\GroupChatting;

class GroupChatBody extends Component
{
    public $messages;
    public $chattingGroup;
    public $newMessage;

    protected $listeners = ['messageSent' => 'loadMessages'];

    public function mount($group)
    {
        $this->chattingGroup = $group;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $isGroupUser = $this->chattingGroup->group_users->contains(auth()->user()->id);
        if ($isGroupUser == false) {
            toast('You are not authorised to view this group: '.$this->chattingGroup->name.'.','warning');
            return redirect()->route('administration.chatting.group.index');
        }

        $this->messages = $this->chattingGroup->group_messages;

        // Mark messages as read
        $this->markMessagesAsRead();
        // dd($this->messages);
    }


    public function sendMessage()
    {
        // Convert newlines to <br> tags for proper display
        $formattedMessage = $this->newMessage;
        if ($formattedMessage) {
            // Replace newlines with <br> tags
            $formattedMessage = nl2br($formattedMessage);
        }

        GroupChatting::create([
            'chatting_group_id' => $this->chattingGroup->id,
            'sender_id' => auth()->user()->id,
            'message' => $formattedMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
    }

    public function markMessagesAsRead()
    {
        $userId = auth()->user()->id;

        foreach ($this->messages as $message) {
            // Check if the user has already read the message
            if (!$message->readByUsers()->where('user_id', $userId)->exists()) {
                $message->readByUsers()->attach($userId, ['read_at' => now()]);
            }
        }
    }


    public function render()
    {
        return view('livewire.administration.chatting.group.chat-body', [
            'messages' => $this->messages,
            'newMessage' => $this->newMessage,
            'chattingGroup' => $this->chattingGroup
        ]);
    }
}
