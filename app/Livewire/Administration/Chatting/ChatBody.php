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
        'messageSent' => 'loadMessages',
        'refresh' => 'loadMessages'
    ];

    public function mount($user)
    {
        $this->receiver = $user;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                // Session expired, refresh the page
                $this->dispatch('sessionExpired');
                return;
            }

            // Check if receiver exists
            if (!$this->receiver) {
                \Log::warning('ChatBody: Receiver is null');
                return;
            }

            // Use a more efficient check for user interactions
            $canInteract = $this->checkUserInteraction($this->receiver->id);
            if ($canInteract == false) {
                toast('You are not authorised to interact with '.$this->receiver->name.'.','warning');
                return redirect()->route('administration.chatting.index');
            }

            // Load messages with optimized query
            $this->messages = Chatting::with(['sender.media', 'receiver.media', 'task', 'files'])
                ->where(function ($query) {
                    $query->where(function ($q) {
                        $q->where('sender_id', auth()->user()->id)
                          ->where('receiver_id', $this->receiver->id);
                    })->orWhere(function ($q) {
                        $q->where('sender_id', $this->receiver->id)
                          ->where('receiver_id', auth()->user()->id);
                    });
                })
                ->orderBy('created_at', 'asc')
                ->get();

            // Mark all messages from the receiver as seen (only if there are messages)
            if ($this->messages->isNotEmpty()) {
                Chatting::where('sender_id', $this->receiver->id)
                    ->where('receiver_id', auth()->user()->id)
                    ->whereNull('seen_at')
                    ->update(['seen_at' => now()]);
            }
        } catch (\Exception $e) {
            // Log the error with more context
            \Log::error('Error loading messages: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'receiver_id' => $this->receiver->id ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            // If it's a session or token issue, dispatch an event to refresh the token
            if (str_contains($e->getMessage(), 'session') || str_contains($e->getMessage(), 'token') || str_contains($e->getMessage(), '419')) {
                $this->dispatch('sessionExpired');
            } else {
                // For other errors, set empty messages to prevent further issues
                $this->messages = collect();
            }
        }
    }

    /**
     * Check if user can interact with another user (simplified)
     */
    private function checkUserInteraction($receiverId)
    {
        try {
            // Use a simple, direct query to avoid memory issues
            return \DB::table('user_interactions')
                ->where('user_id', auth()->id())
                ->where('interacted_user_id', $receiverId)
                ->exists() || 
                \DB::table('user_interactions')
                ->where('user_id', $receiverId)
                ->where('interacted_user_id', auth()->id())
                ->exists();
        } catch (\Exception $e) {
            \Log::error('Error checking user interaction: ' . $e->getMessage());
            return false; // Default to false for security
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
        try {
            // Check if user is authenticated
            if (!Auth::check()) {
                // Session expired, refresh the page
                $this->dispatch('sessionExpired');
                return;
            }

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

            // Load messages to update UI
            $this->loadMessages();

            // Dispatch event to update UI
            $this->dispatch('messageSent');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error sending message: ' . $e->getMessage());

            // If it's a session or token issue, dispatch an event to refresh the token
            if (str_contains($e->getMessage(), 'session') || str_contains($e->getMessage(), 'token') || str_contains($e->getMessage(), '419')) {
                $this->dispatch('sessionExpired');
            } else {
                // Show error message to user
                toast('Error sending message. Please try again.', 'error');
            }
        }
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
