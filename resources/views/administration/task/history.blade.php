@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Task Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-on-scroll/animate-on-scroll.css') }}" />

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100% !important;
    }
    .timeline.timeline-center .timeline-item.timeline-item-right .timeline-event .timeline-event-time, .timeline.timeline-center .timeline-item:nth-of-type(even):not(.timeline-item-left):not(.timeline-item-right) .timeline-event .timeline-event-time {
        left: -18rem;
    }
    .timeline.timeline-center .timeline-item.timeline-item-left .timeline-event .timeline-event-time, .timeline.timeline-center .timeline-item:nth-of-type(odd):not(.timeline-item-left):not(.timeline-item-right) .timeline-event .timeline-event-time {
        right: -18rem;
    }
    .img-thumbnail {
        padding: 3px;
        border: 3px solid var(--bs-border-color);
        border-radius: 5px;
    }
    .file-thumbnail-container {
        width: 130px;
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
        max-width: 120px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Task History') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    @canany (['Task Create', 'Task Update', 'Task Delete'])
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.index') }}" class="text-bold">{{ __('All Tasks') }}</a>
        </li>
    @else
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.my') }}" class="text-bold">{{ __('My Tasks') }}</a>
        </li>
    @endcanany
    <li class="breadcrumb-item">{{ __('Task Details') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="text-bold">{{ $task->taskid }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Task History') }}</li>
@endsection


@section('content')

<!-- Start row -->
{{-- <div class="row overflow-hidden"> --}}
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ $task->title }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back To Details
                    </a>
                </div>
            </div>
            <div class="card-body bg-label-secondary">
                <ul class="timeline timeline-center mt-5">
                    @foreach ($histories as $key => $history)
                        <li class="timeline-item {{ $loop->last ? 'border-0 pb-0' : '' }}">
                            <span class="timeline-indicator timeline-indicator-primary" data-aos="zoom-in" data-aos-delay="200">
                                @if ($history->user->hasMedia('avatar'))
                                    <img src="{{ $history->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle cursor-pointer" width="40" title="{{ $history->user->alias_name }}">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle cursor-pointer" width="40" title="{{ $history->user->alias_name }}">
                                @endif
                            </span>
                            <div class="timeline-event card p-0" data-aos="fade-right">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap pb-3 pt-3">
                                    <h6 class="card-title mb-0">{{ $history->user->alias_name }}</h6>
                                    <div class="meta">
                                        @if ($history->status === 'Working')
                                            <span class="badge rounded-pill bg-label-primary">Working</span>
                                        @else
                                            <span class="text-bold text-success">{{ $history->progress }}%</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body border-top pt-3">
                                    <p class="mb-2">{!! $history->note !!}</p>
                                    <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
                                        <div class="mb-sm-0 mb-3">
                                            <p class="text-muted mb-1">Started At</p>
                                            <p class="mb-0">{{ show_time($history->started_at) }}</p>
                                        </div>
                                        <div class="mb-sm-0 mb-3">
                                            <p class="text-muted mb-1">Ends At</p>
                                            @if (!is_null($history->ends_at))
                                                <p class="mb-0">{{ show_time($history->ends_at) }}</p>
                                            @else
                                                <p class="mb-0 badge bg-label-primary">{{ $history->status }}</p>
                                            @endif
                                        </div>
                                        <div class="mb-sm-0 mb-3">
                                            <p class="text-muted mb-1">Total Worked</p>
                                            @if (!is_null($history->total_worked))
                                                <p class="mb-0">{{ total_time($history->total_worked) }}</p>
                                            @else
                                                <p class="mb-0 badge bg-label-primary">{{ $history->status }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if ($history->files->count() > 0)
                                        <div class="mt-3">
                                            <button class="btn btn-label-primary btn-xs btn-block" type="button" data-bs-toggle="collapse" data-bs-target="#showFiles{{ $key }}" aria-expanded="false" aria-controls="showFiles{{ $key }}">
                                                <i class="ti ti-files"></i>
                                                Show Files
                                            </button>
                                            <div class="collapse" id="showFiles{{ $key }}">
                                                <div class="d-flex flex-wrap gap-2 pt-1 mb-3 mt-3">
                                                    @foreach ($history->files as $file)
                                                        @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                            <div class="history-image-container" title="Click to view {{ $file->original_name }}">
                                                                <a href="{{ file_media_download($file) }}" data-lightbox="history-images" data-title="{{ $file->original_name }}">
                                                                    <img src="{{ file_media_download($file) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 130px; height: 100px; object-fit: cover;">
                                                                </a>
                                                            </div>
                                                        @else
                                                            <div class="file-thumbnail-container" title="Click to Download {{ $file->original_name }}">
                                                                <a href="{{ file_media_download($file) }}" target="_blank" class="text-decoration-none">
                                                                    <div class="d-flex flex-column align-items-center">
                                                                        <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                                                        <span class="file-name text-center small fw-medium">
                                                                            {{ show_content($file->original_name, 15) }}
                                                                        </span>
                                                                        <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
                                                                    </div>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="timeline-event-time">{{ show_date($history->created_at) }}</div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/animate-on-scroll/animate-on-scroll.js') }}"></script>

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/extended-ui-timeline.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // Lightbox configuration
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Image %1 of %2"
            });
        });
    </script>
@endsection


