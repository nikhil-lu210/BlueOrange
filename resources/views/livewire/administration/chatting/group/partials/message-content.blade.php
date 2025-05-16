@if (!is_null($message->message))
    @if ($isCurrentUser)
        <small class="text-muted d-block text-right">
            {{ show_time($message->created_at) }}
        </small>
    @else
        <small class="text-muted d-block text-left">
            {{ show_time($message->created_at) }}
        </small>
    @endif

    {{-- File Attachment --}}
    @include('livewire.administration.chatting.group.partials.file-attachment')

    <div class="chat-message-text position-relative {{ $isBeingRepliedTo ? 'border-2 border-dark' : '' }}">
        @isset($message->reply_to)
            <blockquote class="reply-quote {{ $isCurrentUser ? 'bg-primary-light' : 'bg-light' }}">
                {!! $message->reply_to->message !!}
            </blockquote>
        @endisset
        <p class="mb-0">{!! $message->message !!}</p>
    </div>
    
    @if ($message->readByUsers->count() > 0)
        <small>Seen by: {{ $message->readByUsers->pluck('employee.alias_name')->join(', ') }}</small>
    @endif
@endif
