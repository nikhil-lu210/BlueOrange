<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use App\Models\Chatting\Chatting;
use App\Models\Chatting\GroupChatting;
use Illuminate\Support\Facades\Auth;

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
        // dd($this->messages);
    }


    public function sendMessage()
    {
        GroupChatting::create([
            'chatting_group_id' => $this->chattingGroup->id,
            'sender_id' => auth()->user()->id,
            'message' => $this->newMessage,
        ]);

        $this->newMessage = '';
        $this->loadMessages();
        $this->dispatch('messageSent');
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
