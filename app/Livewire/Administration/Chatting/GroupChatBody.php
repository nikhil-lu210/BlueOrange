<?php

namespace App\Livewire\Administration\Chatting;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Chatting\GroupChatting;
use App\Models\Chatting\GroupChatFileMedia;

class GroupChatBody extends Component
{
    use WithFileUploads;

    public $messages;
    public $chattingGroup;
    public $newMessage;
    public $file;
    public $replyToMessageId = null;
    public $replyToMessage = null;

    protected $listeners = [
        'messageSent' => 'loadMessages',
        'replyToMessage' => 'setReplyMessage',
        'cancelReply' => 'cancelReply'
    ];

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
        try {
            // Validate that either a message or a file is provided
            if (empty(trim($this->newMessage)) && !$this->file) {
                // Don't send empty messages
                return;
            }

            // Convert newlines to <br> tags for proper display
            $formattedMessage = $this->newMessage;
            if ($formattedMessage) {
                // Replace newlines with <br> tags
                $formattedMessage = nl2br($formattedMessage);
            }

            // Create message data
            $messageData = [
                'chatting_group_id' => $this->chattingGroup->id,
                'sender_id' => auth()->user()->id,
                'message' => $formattedMessage,
            ];

            // Add reply information if replying to a message
            if ($this->replyToMessageId && $this->replyToMessage) {
                $messageData['reply_to_id'] = $this->replyToMessageId;
            }

            // Create the group chat message
            $groupChatMessage = GroupChatting::create($messageData);

            // Process and store file if any
            if ($this->file) {
                // Generate a unique folder path for this group chat
                $folderPath = 'group_chat_files/' . $this->chattingGroup->groupid;

                // Create a unique filename
                $fileName = time() . '_' . $this->file->getClientOriginalName();

                // Store the file
                $filePath = $this->file->storeAs($folderPath, $fileName, 'public');

                // Get file extension
                $fileExtension = $this->file->getClientOriginalExtension();

                // Check if it's an image
                $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);

                // Create file media record
                GroupChatFileMedia::create([
                    'group_chatting_id' => $groupChatMessage->id,
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
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error sending group message: ' . $e->getMessage());

            // If it's a session or token issue, dispatch an event to refresh the token
            if (str_contains($e->getMessage(), 'session') || str_contains($e->getMessage(), 'token') || str_contains($e->getMessage(), '419')) {
                $this->dispatch('sessionExpired');
            } else {
                // Show error message to user
                toast('Error sending message. Please try again.', 'error');
            }
        }
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


    /**
     * Set a message to reply to
     */
    public function setReplyMessage($messageId)
    {
        $this->replyToMessageId = $messageId;
        $this->replyToMessage = GroupChatting::find($messageId);
    }

    /**
     * Cancel replying to a message
     */
    public function cancelReply()
    {
        $this->replyToMessageId = null;
        $this->replyToMessage = null;
    }

    public function render()
    {
        return view('livewire.administration.chatting.group.chat-body', [
            'messages' => $this->messages,
            'newMessage' => $this->newMessage,
            'chattingGroup' => $this->chattingGroup,
            'file' => $this->file,
            'replyToMessageId' => $this->replyToMessageId,
            'replyToMessage' => $this->replyToMessage
        ]);
    }
}
