@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Announcement Details'))

@section('css_links')
    {{--  External CSS  --}}

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"/>
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100%;
    }
    .img-thumbnail {
        padding: 3px;
        border: 3px solid var(--bs-border-color);
        border-radius: 5px;
    }
    .file-thumbnail-container {
        width: 150px;
        height: 100px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
    }
    .file-thumbnail-container .file-name {
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Announcement Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.announcement.my') }}">{{ __('My Announcements') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Announcement Details') }}</li>
@endsection


@section('content')


<!-- Start row -->
<div class="row">
    {{-- Header Card with Announcer Info --}}
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                {{-- Announcement Title --}}
                <div class="text-center mb-2">
                    <h2 class="mb-0">{{ $announcement->title }}</h2>
                </div>

                <div class="row align-items-center">
                    {{-- Announcer Information --}}
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-3">
                                @if ($announcement->announcer->hasMedia('avatar'))
                                    <img src="{{ $announcement->announcer->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $announcement->announcer->name }}" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ profile_name_pic($announcement->announcer) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1">{{ __('Announced By') }}</small>
                                <h6 class="mb-0">
                                    <i class="ti ti-crown ti-xs me-1"></i>
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $announcement->announcer]) }}" target="_blank" class="text-primary">
                                        {{ $announcement->announcer->alias_name ?? $announcement->announcer->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="ti ti-briefcase ti-xs me-1"></i>{{ $announcement->announcer->role->name ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Announcement Date & Status --}}
                    <div class="col-lg-4 col-md-6 text-center">
                        <span class="badge bg-label-primary text-bold p-2">
                            <i class="ti ti-calendar ti-sm me-1"></i>
                            {{ show_date($announcement->created_at) }}
                        </span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex align-items-center justify-content-lg-end gap-2">
                            @can('Announcement Update')
                                <a href="{{ route('administration.announcement.edit', ['announcement' => $announcement]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('Edit Announcement') }}">
                                    <i class="ti ti-pencil me-1"></i>{{ __('Edit') }}
                            </a>
                        @endcan
                            <button type="button" class="btn btn-sm btn-primary" title="{{ __('View Statistics') }}" data-bs-toggle="modal" data-bs-target="#announcementStatsModal">
                                <i class="ti ti-chart-bar me-1"></i>{{ __('Stats') }}
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" title="{{ __('View Readers') }}" data-bs-toggle="modal" data-bs-target="#showAnnouncementReadersModal">
                                <i class="ti ti-eye me-1"></i>{{ __('Readers') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Statistics --}}
    <div class="col-12">
        <div class="card mb-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-users ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ $announcementStats['totalRecipients'] }} <small>{{ $announcementStats['totalRecipients'] == 1 ? 'Person' : 'People' }}</small></h5>
                                <small class="mb-0 text-muted">{{ __('Total Recipients') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-success p-2 rounded">
                                <i class="ti ti-eye-check ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-success mb-0">{{ $announcementStats['readCount'] }} <small>{{ $announcementStats['readCount'] == 1 ? 'Person' : 'People' }}</small></h5>
                                <small class="mb-0 text-muted">{{ __('Read') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-eye-off ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ $announcementStats['unreadCount'] }} <small>{{ $announcementStats['unreadCount'] == 1 ? 'Person' : 'People' }}</small></h5>
                                <small class="mb-0 text-muted">{{ __('Unread') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-info p-2 rounded">
                                <i class="ti ti-message-circle ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-info mb-0">{{ $announcement->comments->count() }} <small>{{ $announcement->comments->count() == 1 ? 'Comment' : 'Comments' }}</small></h5>
                                <small class="mb-0 text-muted">{{ __('Comments') }}</small>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="col-lg-8 col-md-12">
        {{-- Announcement Content --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-file-text me-2"></i>{{ __('Announcement Details') }}
                </h5>
                <span class="badge bg-label-secondary">{{ $announcement->files->count() }} {{ $announcement->files->count() == 1 ? 'file' : 'files' }}</span>
            </div>
            <div class="card-body">
                <div class="announcement-content">
                {!! $announcement->description !!}
                </div>
            </div>
        </div>

        {{-- Attached Files --}}
        @if ($announcement->files->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-paperclip me-2"></i>{{ __('Attached Files') }} ({{ $announcement->files->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Size') }}</th>
                                    <th>{{ __('Uploaded') }}</th>
                                    <th class="text-center">{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($announcement->files as $file)
                                    <tr>
                                        <td>
                                            @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                <div class="task-image-container">
                                                    <a href="{{ file_media_download($file) }}" data-lightbox="task-images" data-title="{{ $file->original_name }}">
                                                        <img src="{{ file_media_download($file) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                    </a>
                                                </div>
                                            @else
                                                <div class="file-thumbnail-container">
                                                    <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                                    <span class="file-name text-center small fw-medium" title="{{ $file->original_name }}">
                                                        {{ show_content($file->original_name, 15) }}
                                                    </span>
                                                    <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ get_file_media_size($file) }}</td>
                                        <td>{{ date_time_ago($file->created_at) }}</td>
                                        <td class="text-center">
                                        @if ($announcementStats['isAnnouncer'])
                                                <a href="{{ file_media_destroy($file) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" title="Delete {{ $file->original_name }}">
                                                    <i class="ti ti-trash"></i>
                                                </a>
                                            @endif
                                            <a href="{{ file_media_download($file) }}" target="_blank" class="btn btn-icon btn-primary btn-sm waves-effect" title="{{ __('Download') }} {{ $file->original_name }}">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4">
        {{-- Recipients Section --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-user-group me-2"></i>{{ __('Recipients') }}
                </h5>
            </div>
            <div class="card-body">
                @if (is_null($announcement->recipients))
                    <div class="text-center">
                        <i class="ti ti-world fs-1 text-muted mb-3"></i>
                        <p class="text-muted">{{ __('This announcement is visible to') }} <strong>{{ __('all active users') }}</strong></p>
                    </div>
                @else
                    <div class="d-flex flex-wrap gap-1">
                        @foreach ($announcement->recipients as $recipient)
                            <span class="badge bg-label-primary">
                                {{ show_employee_data($recipient, 'alias_name') }}
                            </span>
                        @endforeach
                </div>
            @endif
        </div>
    </div>

        {{-- Comments Section --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-message-circle me-2"></i>{{ __('Comments') }} ({{ $announcement->comments->count() }})
                </h5>
                <button type="button" class="btn btn-sm btn-primary" title="{{ __('Add Comment') }}" data-bs-toggle="collapse" data-bs-target="#commentForm" aria-expanded="false">
                    <i class="ti ti-plus me-1"></i>
                    {{ __('Comment') }}
                </button>
            </div>
            <div class="card-body">
                {{-- Comment Form --}}
                <div class="collapse" id="commentForm">
                    <form action="{{ route('administration.announcement.comment.store', ['announcement' => $announcement]) }}" method="post" class="mb-4">
                        @csrf
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" placeholder="Write your comment here..." required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="ti ti-send me-1"></i>
                            {{ __('Post Comment') }}
                        </button>
                    </form>
                    <hr>
                </div>

                {{-- Comments List --}}
                <div class="comments-section">
                    @forelse ($announcement->comments as $comment)
                        <div class="d-flex mb-3">
                            <div class="avatar avatar-sm me-3">
                                @if ($comment->commenter->hasMedia('avatar'))
                                    <img src="{{ $comment->commenter->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $comment->commenter->name }}" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ profile_name_pic($comment->commenter) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0">{{ $comment->commenter->alias_name ?? $comment->commenter->name }}</h6>
                                    <small class="text-muted">{{ date_time_ago($comment->created_at) }}</small>
                                </div>
                                <p class="mb-0 text-muted">{{ $comment->comment }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4">
                            <i class="ti ti-message-circle-off fs-1 mb-3"></i>
                            <p>{{ __('No comments yet. Be the first to comment!') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

{{-- Announcement Statistics Modal --}}
@include('administration.announcement.modals._announcement_stattistics_modal')

{{-- Announcement Readers Modal --}}
@include('administration.announcement.modals._announcement_readers_modal')

@endsection


@section('script_links')
    {{--  External Javascript Links --}}

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-expand comment form if there's an error
        @if($errors->has('comment'))
            $('#commentForm').collapse('show');
        @endif

        // Add loading states to buttons
        $('form').on('submit', function() {
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="ti ti-loader-2 ti-spin me-1"></i>Processing...');
        });

        // Initialize lightbox with custom settings
        lightbox.option({
            'resizeDuration': 200,
            'wrapAround': true,
            'showImageNumberLabel': true,
            'alwaysShowNavOnTouchDevices': true,
            'albumLabel': 'Image %1 of %2'
        });
    });
    </script>
@endsection
