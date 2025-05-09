@if (!is_null($message->message))
    <small class="text-muted d-block {{ $isCurrentUser ? 'text-right' : 'text-left' }}">{{ show_time($message->created_at) }}</small>
    <div class="chat-message-text position-relative {{ $isBeingRepliedTo ? 'border-2 border-dark' : '' }}">
        @isset($message->reply_to)
            <blockquote class="reply-quote {{ $isCurrentUser ? 'bg-primary-light' : 'bg-light' }}">
                {!! $message->reply_to->message !!}
            </blockquote>
        @endisset
        <p class="mb-0">{!! $message->message !!}</p>
    </div>
@endif
