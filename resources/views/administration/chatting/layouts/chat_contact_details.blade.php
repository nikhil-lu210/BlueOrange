<div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
    <div class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <div class="avatar avatar-xl avatar-online">
            @if ($user->hasMedia('avatar'))
                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle" width="40">
            @else
                <span class="avatar-initial rounded-circle bg-dark border border-1">
                    {{ substr($user->alias_name, 0, 1) }}
                </span>
            @endif
        </div>
        <h6 class="mt-2 mb-0">{{ $user->alias_name }}</h6>
        <span>{{ $user->role->name }}</span>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar d-block" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <div class="my-4">
            <small class="text-muted text-uppercase">Shared Media</small>
            <div class="shared-media-container mt-3" style="max-height: 40vh; overflow-y: auto;">
                @if($sharedFiles->count() > 0)
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($sharedFiles as $file)
                            @if($file->is_image)
                                <div class="task-image-container">
                                    <a href="{{ asset('storage/' . $file->file_path) }}" data-lightbox="shared-images" data-title="{{ $file->original_name }}">
                                        <img src="{{ asset('storage/' . $file->file_path) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 120px; height: 90px; object-fit: cover;">
                                    </a>
                                </div>
                            @else
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="file-thumbnail-container" style="width: 120px; height: 90px; object-fit: cover;">
                                    <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                    <span class="file-name text-center small fw-medium" title="{{ $file->original_name }}">
                                        {{ show_content($file->original_name, 10) }}
                                    </span>
                                    <small class="text-muted">{{ strtoupper($file->file_extension) }}</small>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @else
                    <p class="text-muted small">No shared media found</p>
                @endif
            </div>
        </div>
    </div>
</div>
