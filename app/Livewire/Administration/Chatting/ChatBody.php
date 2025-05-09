<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Chatting\Chatting;
use Illuminate\Support\Facades\Auth;

class ChatBody extends Component
{
    use WithFileUploads;

    public $messages;
    public $receiver;
    public $newMessage;
    public $file;
    public $replyToMessageId = null;
    public $replyToMessage = null;

    protected $listeners = [
        'messageSent' => 'loadMessages'
    ];

    public function mount($user)
    {
        $this->receiver = $user;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        $canInteract = Auth::user()->user_interactions->contains('id', $this->receiver->id);
        if ($canInteract == false) {
            toast('You are not authorised to interact with '.$this->receiver->name.'.','warning');
            return redirect()->route('administration.chatting.index');
        }

        if ($this->receiver) {
            $this->messages = Chatting::with(['sender.media', 'receiver.media', 'task'])->where(function ($query) {
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



    public function updatedReplyToMessageId($value)
    {
        if ($value) {
            $this->replyToMessage = Chatting::with(['sender', 'receiver'])->find($value);
        } else {
            $this->replyToMessage = null;
        }
    }



    public function sendMessage()
    {
        $filePath = null;

        if ($this->file) {
            $fileName = time() . '_' . $this->file->getClientOriginalName();
            $filePath = $this->file->storeAs('chat_files', $fileName, 'public');
        }

        // Convert newlines to <br> tags for proper display
        $formattedMessage = $this->newMessage;
        if ($formattedMessage) {
            // Replace newlines with <br> tags
            $formattedMessage = nl2br($formattedMessage);
        }

        // Create the new message
        $message = [
            'sender_id' => auth()->user()->id,
            'receiver_id' => $this->receiver->id,
            'message' => $formattedMessage,
            'file' => $filePath,
        ];

        // Add reply information if replying to a message
        if ($this->replyToMessageId && $this->replyToMessage) {
            // You could store this in a separate table or as JSON in a column
            // For now, we'll just add it to the message for demonstration
            $message['reply_to_id'] = $this->replyToMessageId;
        }

        Chatting::create($message);

        $this->newMessage = '';
        $this->file = null;
        $this->replyToMessageId = null;
        $this->replyToMessage = null;
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
