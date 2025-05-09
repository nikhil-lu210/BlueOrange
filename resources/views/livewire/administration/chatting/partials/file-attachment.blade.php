@if (!is_null($message->file))
    @php
        $fileExtension = pathinfo($message->file, PATHINFO_EXTENSION);
        $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
        $fileName = pathinfo($message->file, PATHINFO_BASENAME);
    @endphp

    @if ($isImage)
        <div class="chat-message-image mt-1" style="width: 170px;">
            <a href="{{ asset('storage/' . $message->file) }}" class="image-link" target="_blank">
                <img src="{{ asset('storage/' . $message->file) }}" class="img-responsive img-thumbnail">
            </a>
            <small class="d-block text-muted mt-1">{{ $fileName }}</small>
        </div>
    @else
        <a href="{{ asset('storage/' . $message->file) }}" class="chat-message-text card h-100" target="_blank">
            <div class="card-body text-center">
                <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file-download ti-lg"></i></div>
                <p class="mb-0">{{ $fileName }}</p>
            </div>
        </a>
    @endif
@endif
