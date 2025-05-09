@if (!is_null($message->message))
    @if ($isCurrentUser)
        <small class="text-muted d-block text-right">
            @if (!is_null($message->seen_at))
                <i class="ti ti-checks ti-xs me-1 text-success" data-bs-toggle="tooltip" title="Seen At: {{ show_time($message->seen_at) }}"></i>
            @else
                <i class="ti ti-check ti-xs me-1"></i>
            @endif
            {{ show_time($message->created_at) }}
        </small>
    @else
        <small class="text-muted d-block text-left">
            {{ show_time($message->created_at) }}
            @if (!is_null($message->seen_at))
                <i class="ti ti-checks ti-xs me-1 text-success" data-bs-toggle="tooltip" title="Seen At: {{ show_time($message->seen_at) }}"></i>
            @else
                <i class="ti ti-check ti-xs me-1"></i>
            @endif
        </small>
    @endif

    <div class="chat-message-text position-relative {{ $isBeingRepliedTo ? 'border-2 border-dark' : '' }}">
        @isset($message->reply_to)
            <blockquote class="reply-quote {{ $isCurrentUser ? 'bg-primary-light' : 'bg-light' }}">
                {!! $message->reply_to->message !!}
            </blockquote>
        @endisset
        <p class="mb-0">{!! $message->message !!}</p>
    </div>
@endif
