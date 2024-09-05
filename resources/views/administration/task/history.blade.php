@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Task Details'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-on-scroll/animate-on-scroll.css') }}" />
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
                                    <img src="{{ $history->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle cursor-pointer" width="40" title="{{ $history->user->name }}">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle cursor-pointer" width="40" title="{{ $history->user->name }}">
                                @endif
                            </span>
                            <div class="timeline-event card p-0" data-aos="fade-right">
                                <div class="card-header d-flex justify-content-between align-items-center flex-wrap pb-3 pt-3">
                                    <h6 class="card-title mb-0">{{ $history->user->name }}</h6>
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
                                                <ul class="list-group list-group-flush mt-3">
                                                    @foreach ($history->files as $file)
                                                        <li class="list-group-item d-flex justify-content-between flex-wrap">
                                                            <span class="text-dark fw-bold">{{ $file->original_name }}</span>
                                                            <span>{{ get_file_media_size($file) }}</span>
                                                            <a href="{{ file_media_download($file) }}" target="_blank" title="Click to Download">
                                                                <i class="ti ti-download" style="margin-top: -5px;"></i>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
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
    
    <!-- Page JS -->
    <script src="{{ asset('assets/js/extended-ui-timeline.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
