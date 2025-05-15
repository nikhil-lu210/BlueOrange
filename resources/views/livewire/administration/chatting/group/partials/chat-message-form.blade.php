<div class="chat-history-footer shadow-sm position-relative">
    @if($replyToMessage)
        <div class="reply-to-message">
            <div class="d-flex justify-content-between align-items-center position-relative" style="padding-right: 25px;">
                <div>
                    <small class="text-muted">
                        Replying to
                        <strong>{{ $replyToMessage->sender_id == auth()->id() ? 'Your Message' : $replyToMessage->sender->name }}</strong>
                    </small>
                    <p class="mb-0 reply-message-preview">
                        @if(is_object($replyToMessage) && $replyToMessage->message)
                            {!! $replyToMessage->message !!}
                        @else
                            Message
                        @endif
                    </p>
                    <small class="text-muted">{{ $replyToMessage->created_at->format('d M Y, h:i A') }}</small>
                </div>
                <a href="javascript:void(0);" class="text-bold text-danger reply-close-btn" wire:click="cancelReply">
                    <i class="ti ti-x text-bold"></i>
                </a>
            </div>
        </div>
    @endif

    @if($file)
        <div class="selected-file file-thumbnail-container">
            <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
            <span class="file-name text-center small fw-medium" title="{{ $file->getClientOriginalName() }}">
                {{ show_content($file->getClientOriginalName(), 15) }}
            </span>
            <small class="text-muted">{{ strtoupper(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION)) }}</small>
            <a href="javascript:void(0);" class="text-bold text-danger" style="position: absolute; top: 0; right: 0;" wire:click="$set('file', null)">
                <i class="ti ti-x"></i>
            </a>
        </div>
    @endif

    <form wire:submit.prevent="sendMessage" class="form-send-message d-flex justify-content-between align-items-center" enctype="multipart/form-data">
        @csrf
        <textarea
            wire:model="newMessage"
            class="form-control message-input border-0 me-3 shadow-none"
            placeholder="Type your message here (Shift+Enter for new line)"
            rows="1"
            x-data="{}"
            x-on:keydown.enter="
                if ($event.shiftKey) {
                    // Allow Shift+Enter to create a new line (default behavior)
                } else {
                    // Submit form when Enter is pressed without Shift
                    $event.preventDefault();
                    $wire.sendMessage();
                }
            "
        ></textarea>
        <div class="message-actions d-flex align-items-center">
            <label for="group-attach-doc" class="form-label mb-0 me-2" title="Upload File">
                <i class="ti ti-paperclip ti-sm cursor-pointer"></i>
                <input type="file" id="group-attach-doc" wire:model="file" hidden accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.doc,.docx,.txt,.csv,.zip,.rar" />
            </label>
            <button type="submit" class="btn btn-primary d-flex send-msg-btn">
                <i class="ti ti-send me-md-1 me-0"></i>
                <span class="align-middle d-md-inline-block d-none">Send</span>
            </button>
        </div>
    </form>
</div>
