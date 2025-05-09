{{-- Display files from the chat_file_media table --}}
@if ($message->files->count() > 0)
    <div class="chat-message-files mt-2">
        @foreach ($message->files as $file)
            @if ($file->is_image)
                <div class="chat-message-image mt-1" style="width: 170px;">
                    <a href="{{ asset('storage/' . $file->file_path) }}" class="image-link" target="_blank">
                        <img src="{{ asset('storage/' . $file->file_path) }}" class="img-responsive img-thumbnail">
                    </a>
                    <small class="d-block text-muted mt-1">{{ $file->original_name }}</small>
                </div>
            @else
                <a href="{{ asset('storage/' . $file->file_path) }}" class="chat-message-text card h-100" target="_blank" download="{{ $file->original_name }}">
                    <div class="card-body text-center">
                        <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file-download ti-lg"></i></div>
                        <p class="mb-0">{{ $file->original_name }}</p>
                    </div>
                </a>
            @endif
        @endforeach
    </div>
@endif

{{-- Keep backward compatibility with old file field --}}
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
