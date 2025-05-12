<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Chatting\Chatting;
use App\Models\Chatting\ChatFileMedia;
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
            $this->messages = Chatting::with(['sender.media', 'receiver.media', 'task', 'files'])->where(function ($query) {
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
        // Check if there's a file or message content
        if (empty($this->newMessage) && !$this->file) {
            // Don't send empty messages
            toast('You cannot send empty message.', 'warning');
            return;
        }

        // Convert newlines to <br> tags for proper display
        $formattedMessage = $this->newMessage;

        // If message is empty but there's a file, set a default message
        if (empty($formattedMessage) && $this->file) {
            $formattedMessage = "Shared a file: " . $this->file->getClientOriginalName();
        } else if ($formattedMessage) {
            // Replace newlines with <br> tags
            $formattedMessage = nl2br($formattedMessage);
        }

        // Create the new message
        $message = [
            'sender_id' => auth()->user()->id,
            'receiver_id' => $this->receiver->id,
            'message' => $formattedMessage,
            'file' => null, // We'll use the chat_file_media table instead
        ];

        // Add reply information if replying to a message
        if ($this->replyToMessageId && $this->replyToMessage) {
            $message['reply_to_id'] = $this->replyToMessageId;
        }

        // Create the chat message
        $chatMessage = Chatting::create($message);

        // Process and store file if any
        if ($this->file) {
            // Generate a unique folder path for this chat
            $folderPath = 'chat_files/' . auth()->user()->userid . '/' . $this->receiver->userid;

            // Create a unique filename
            $fileName = time() . '_' . $this->file->getClientOriginalName();

            // Store the file
            $filePath = $this->file->storeAs($folderPath, $fileName, 'public');

            // Get file extension
            $fileExtension = $this->file->getClientOriginalExtension();

            // Check if it's an image
            $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

            // Create file media record
            ChatFileMedia::create([
                'chatting_id' => $chatMessage->id,
                'uploader_id' => auth()->user()->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                'mime_type' => $this->file->getMimeType(),
                'file_extension' => $fileExtension,
                'file_size' => $this->file->getSize(),
                'original_name' => $this->file->getClientOriginalName(),
                'is_image' => $isImage,
            ]);
        }

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
