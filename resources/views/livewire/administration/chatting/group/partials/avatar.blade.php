<div class="avatar avatar-sm">
    @if ($key === 0 || $messages[$key - 1]->sender_id !== $message->sender_id)
        <img src="{{ $imageURL }}" alt="Avatar" class="rounded-circle" title="{{ $message->sender->name }}"/>
    @endif
</div>
