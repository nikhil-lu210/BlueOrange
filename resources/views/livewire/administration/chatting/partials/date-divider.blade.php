@if ($currentDate !== $messageDate)
    @php
        $currentDate = $messageDate;
    @endphp
    <div class="divider divider-dotted">
        <div class="divider-text">{{ $message->created_at->format('F j, Y') }}</div>
    </div>
@endif
