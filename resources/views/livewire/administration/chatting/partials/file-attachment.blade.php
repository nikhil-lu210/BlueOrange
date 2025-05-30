{{-- Display files from the chat_file_media table --}}
@if ($message->files->count() > 0)
    <div class="d-flex flex-wrap gap-2 pt-1 {{ $isCurrentUser ? 'justify-content-end' : 'justify-content-start' }}">
        @foreach ($message->files as $file)
            @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                <div class="task-image-container">
                    <a href="{{ asset('storage/' . $file->file_path) }}" data-lightbox="task-images" data-title="{{ $file->original_name }}">
                        <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                    </a>
                </div>
            @else
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="file-thumbnail-container">
                    <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                    <span class="file-name text-center small fw-medium" title="{{ $file->original_name }}">
                        {{ show_content($file->original_name, 15) }}
                    </span>
                    <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
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
        <div class="task-image-container">
            <a href="{{ asset('storage/' . $message->file) }}" data-lightbox="task-images" data-title="{{ $fileName }}">
                <img src="{{ asset('storage/' . $message->file) }}" alt="{{ $fileName}}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
            </a>
        </div>
    @else
        <a href="{{ asset('storage/' . $message->file) }}" target="_blank" class="file-thumbnail-container">
            <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
            <span class="file-name text-center small fw-medium" title="{{ $fileName }}">
                {{ show_content($fileName, 15) }}
            </span>
            <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
        </a>
    @endif
@endif
