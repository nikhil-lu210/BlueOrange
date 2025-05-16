{{-- Display files from the group_chat_file_media table --}}
@if ($message->files->count() > 0)
    <div class="d-flex flex-wrap gap-2 pt-1 {{ $isCurrentUser ? 'justify-content-end' : 'justify-content-start' }}">
        @foreach ($message->files as $file)
            @if ($file->is_image)
                <div class="chat-message-image">
                    <a href="{{ asset('storage/' . $file->file_path) }}" data-lightbox="group-chat-images" data-title="{{ $file->original_name }}">
                        <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail">
                    </a>
                </div>
            @else
                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="file-thumbnail-container">
                    <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                    <span class="file-name text-center small fw-medium" title="{{ $file->original_name }}">
                        {{ show_content($file->original_name, 15) }}
                    </span>
                    <small class="text-muted">{{ strtoupper($file->file_extension) }}</small>
                </a>
            @endif
        @endforeach
    </div>
@endif
