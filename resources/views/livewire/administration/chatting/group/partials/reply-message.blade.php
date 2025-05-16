@if($replyToMessage)
    <div class="reply-message-container mb-2 p-2 border-start border-2 border-primary bg-light rounded-1" style="font-size: 0.85rem;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <small class="text-muted">
                    <i class="ti ti-corner-down-left-double me-1"></i>Replying to
                    @if($replyToMessage->sender_id === auth()->user()->id)
                        yourself
                    @else
                        {{ $replyToMessage->sender->name }}
                    @endif
                </small>
                <p class="mb-0 text-truncate" style="max-width: 250px; opacity: 0.8;">
                    {!! $replyToMessage->message !!}
                </p>
            </div>
            <a href="javascript:void(0);" class="text-danger ms-2" wire:click="cancelReply" style="font-size: 0.8rem;">
                <i class="ti ti-x"></i>
            </a>
        </div>
    </div>
@endif
