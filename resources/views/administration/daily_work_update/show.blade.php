@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Daily Work Update Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100%;
    }
    .progress-wrapper {
        position: relative;
    }
    .progress-label {
        position: absolute;
        width: 100%;
        text-align: center;
        font-weight: 600;
        line-height: 2rem;
        color: #fff;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    }
    .star-rating i {
        font-size: 1.5rem;
    }
    .timeline-item {
        position: relative;
        padding-left: 2.5rem;
        padding-bottom: 1.5rem;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 8px;
        top: 8px;
        bottom: -8px;
        width: 2px;
        background: #e0e0e0;
    }
    .timeline-item:last-child:before {
        display: none;
    }
    .timeline-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        z-index: 1;
    }
    .file-icon {
        font-size: 2.5rem;
        opacity: 0.8;
    }
    @media print {
        .no-print { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Daily Work Update Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Work Update') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.daily_work_update.my') }}">{{ __('My Daily Work Updates') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Daily Work Update Details') }}</li>
@endsection


@section('content')

@php
    // Determine rating color and status
    $ratingColor = 'secondary';
    $ratingText = 'Pending Review';
    $ratingIcon = 'ti-clock';
    $progressColor = 'primary';

    if ($dailyWorkUpdate->rating) {
        switch ($dailyWorkUpdate->rating) {
            case 1:
                $ratingColor = 'danger';
                $ratingText = 'Needs Improvement';
                $ratingIcon = 'ti-mood-sad';
                break;
            case 2:
                $ratingColor = 'warning';
                $ratingText = 'Below Average';
                $ratingIcon = 'ti-mood-confuzed';
                break;
            case 3:
                $ratingColor = 'info';
                $ratingText = 'Average';
                $ratingIcon = 'ti-mood-neutral';
                break;
            case 4:
                $ratingColor = 'primary';
                $ratingText = 'Good';
                $ratingIcon = 'ti-mood-smile';
                break;
            case 5:
                $ratingColor = 'success';
                $ratingText = 'Excellent';
                $ratingIcon = 'ti-mood-happy';
                break;
        }
    }

    // Progress bar color based on percentage
    if ($dailyWorkUpdate->progress < 30) {
        $progressColor = 'danger';
    } elseif ($dailyWorkUpdate->progress < 60) {
        $progressColor = 'warning';
    } elseif ($dailyWorkUpdate->progress < 80) {
        $progressColor = 'info';
    } else {
        $progressColor = 'success';
    }

    // Check if user is the employee or team leader
    $isEmployee = auth()->id() === $dailyWorkUpdate->user_id;
    $isTeamLeader = auth()->id() === $dailyWorkUpdate->team_leader_id;
@endphp

<!-- Start row -->
<div class="row">
    {{-- Header Card with Employee & Team Leader Info --}}
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    {{-- Employee Information --}}
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-lg me-3">
                                @if ($dailyWorkUpdate->user->hasMedia('avatar'))
                                    <img src="{{ $dailyWorkUpdate->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $dailyWorkUpdate->user->name }}" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                        {{ profile_name_pic($dailyWorkUpdate->user) }}
                                    </span>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <small class="text-muted d-block mb-1">Reporting By</small>
                                <h6 class="mb-0">
                                    <i class="ti ti-user ti-xs me-1"></i>
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyWorkUpdate->user]) }}" target="_blank" class="text-primary">
                                        {{ $dailyWorkUpdate->user->alias_name }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="ti ti-briefcase ti-xs me-1"></i>{{ $dailyWorkUpdate->user->role->name ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>

                    {{-- Work Update Date & Status --}}
                    <div class="col-lg-4 col-md-6 mb-3 mb-lg-0 text-center">
                        <div class="mb-2">
                            <span class="badge bg-label-primary text-bold p-2">
                                <i class="ti ti-calendar ti-sm me-1"></i>
                                {{ show_date($dailyWorkUpdate->date) }}
                            </span>
                        </div>
                        <div>
                            <span class="badge bg-label-{{ $ratingColor }} text-bold p-2">
                                <i class="ti {{ $ratingIcon }} ti-sm me-1"></i>
                                {{ $ratingText }}
                            </span>
                        </div>
                    </div>

                    {{-- Team Leader Information --}}
                    <div class="col-lg-4 col-md-12">
                        <div class="d-flex align-items-center justify-content-lg-end">
                            <div class="flex-grow-1 text-lg-end me-3">
                                <small class="text-muted d-block mb-1">Reporting To</small>
                                <h6 class="mb-0">
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyWorkUpdate->team_leader]) }}" target="_blank" class="text-primary">
                                        {{ $dailyWorkUpdate->team_leader->alias_name }}
                                        <i class="ti ti-crown ti-xs me-1"></i>
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $dailyWorkUpdate->team_leader->role->name ?? 'N/A' }}
                                    <i class="ti ti-briefcase ti-xs me-1"></i>
                                </small>
                            </div>
                            <div class="avatar avatar-lg">
                                @if ($dailyWorkUpdate->team_leader->hasMedia('avatar'))
                                    <img src="{{ $dailyWorkUpdate->team_leader->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $dailyWorkUpdate->team_leader->name }}" class="rounded-circle">
                                @else
                                    <span class="avatar-initial rounded-circle bg-label-success">
                                        {{ profile_name_pic($dailyWorkUpdate->team_leader) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats Cards --}}
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-{{ $progressColor }}">
                            <i class="ti ti-progress ti-md"></i>
                        </span>
                    </div>
                </div>
                <h6 class="mb-1">Progress</h6>
                <div class="progress-wrapper mb-2">
                    <div class="progress" style="height: 2rem;">
                        <div class="progress-bar bg-{{ $progressColor }}" role="progressbar" style="width: {{ $dailyWorkUpdate->progress }}%;" aria-valuenow="{{ $dailyWorkUpdate->progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                        <span class="progress-label">{{ $dailyWorkUpdate->progress }}%</span>
                    </div>
                </div>
                <small class="text-muted">Task Completion</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-{{ $ratingColor }}">
                            <i class="ti {{ $ratingIcon }} ti-md"></i>
                        </span>
                    </div>
                </div>
                <h6 class="mb-1">Rating</h6>
                @if ($dailyWorkUpdate->rating)
                    <div class="star-rating mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="ti ti-star{{ $i <= $dailyWorkUpdate->rating ? '-filled' : '' }} text-{{ $ratingColor }}"></i>
                        @endfor
                    </div>
                    <small class="text-muted">{{ $dailyWorkUpdate->rating }}/5 - {{ $ratingText }}</small>
                @else
                    <h5 class="mb-2 text-muted">Not Rated</h5>
                    <small class="text-muted">Awaiting Review</small>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="ti ti-paperclip ti-md"></i>
                        </span>
                    </div>
                </div>
                <h6 class="mb-1">Attachments</h6>
                <h5 class="mb-2">{{ $dailyWorkUpdate->files->count() }}</h5>
                <small class="text-muted">{{ $dailyWorkUpdate->files->count() > 0 ? 'Files Attached' : 'No Files' }}</small>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div class="avatar flex-shrink-0">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="ti ti-clock ti-md"></i>
                        </span>
                    </div>
                </div>
                <h6 class="mb-1">Submitted</h6>
                <h6 class="mb-1">{{ $dailyWorkUpdate->created_at->format('h:i A') }}</h6>
                <small class="text-muted">{{ $dailyWorkUpdate->created_at->diffForHumans() }}</small>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <div class="col-lg-8 col-md-12">
        {{-- Work Update Description --}}
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-file-description me-2"></i>Work Update Details
                </h5>
                <div class="no-print">
                    @can('Daily Work Update Update')
                        @if (!$dailyWorkUpdate->rating && $isTeamLeader)
                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#markAsReadModal">
                                <i class="ti ti-star me-1"></i>Rate Update
                            </button>
                        @endif
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="work-update-description">
                    {!! $dailyWorkUpdate->work_update !!}
                </div>
            </div>
        </div>

        {{-- Attached Files --}}
        @if ($dailyWorkUpdate->files->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ti ti-paperclip me-2"></i>Attached Files ({{ $dailyWorkUpdate->files->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($dailyWorkUpdate->files as $file)
                            <div class="col-md-6 mb-3">
                                <div class="card card-border-shadow-primary h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-start">
                                            <div class="avatar flex-shrink-0 me-3">
                                                @php
                                                    $extension = strtolower(pathinfo($file->original_name, PATHINFO_EXTENSION));
                                                    $iconClass = 'ti-file';
                                                    $iconColor = 'secondary';

                                                    if (in_array($extension, ['pdf'])) {
                                                        $iconClass = 'ti-file-type-pdf';
                                                        $iconColor = 'danger';
                                                    } elseif (in_array($extension, ['doc', 'docx'])) {
                                                        $iconClass = 'ti-file-type-doc';
                                                        $iconColor = 'primary';
                                                    } elseif (in_array($extension, ['xls', 'xlsx', 'csv'])) {
                                                        $iconClass = 'ti-file-type-xls';
                                                        $iconColor = 'success';
                                                    } elseif (in_array($extension, ['zip', 'rar'])) {
                                                        $iconClass = 'ti-file-zip';
                                                        $iconColor = 'warning';
                                                    } elseif (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                                                        $iconClass = 'ti-photo';
                                                        $iconColor = 'info';
                                                    }
                                                @endphp
                                                <span class="avatar-initial rounded bg-label-{{ $iconColor }}">
                                                    <i class="ti {{ $iconClass }} ti-lg"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1" title="{{ $file->original_name }}">
                                                    {{ Str::limit($file->original_name, 30) }}
                                                </h6>
                                                <small class="text-muted d-block mb-2">
                                                    <i class="ti ti-file-info ti-xs me-1"></i>{{ get_file_media_size($file) }}
                                                </small>
                                                <a href="{{ file_media_download($file) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-download ti-xs me-1"></i>Download
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-lg-4 col-md-12">
        {{-- Notes/Issues Card --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-alert-circle me-2"></i>Notes / Issues
                </h5>
            </div>
            <div class="card-body">
                @if($dailyWorkUpdate->note)
                    <div class="alert alert-warning" role="alert">
                        {!! $dailyWorkUpdate->note !!}
                    </div>
                @else
                    <p class="text-muted text-center py-3">
                        <i class="ti ti-info-circle me-1"></i>No issues or notes reported
                    </p>
                @endif
            </div>
        </div>

        {{-- Team Leader Comment --}}
        @if ($dailyWorkUpdate->comment)
            <div class="card mb-4 border-{{ $ratingColor }}">
                <div class="card-header bg-label-{{ $ratingColor }}">
                    <h5 class="mb-0">
                        <i class="ti ti-message-circle me-2"></i>Team Leader Feedback
                    </h5>
                </div>
                <div class="card-body">
                    {!! $dailyWorkUpdate->comment !!}
                </div>
            </div>
        @elseif ($isEmployee && !$dailyWorkUpdate->rating)
            <div class="card mb-4 border-secondary">
                <div class="card-body text-center py-4">
                    <i class="ti ti-hourglass-empty ti-lg text-secondary mb-2 d-block"></i>
                    <p class="text-muted mb-0">Awaiting team leader's review and feedback</p>
                </div>
            </div>
        @endif

        {{-- Activity Timeline --}}
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-timeline me-2"></i>Activity Timeline
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    {{-- Submitted --}}
                    <div class="timeline-item">
                        <div class="timeline-icon bg-label-primary">
                            <i class="ti ti-send ti-xs"></i>
                        </div>
                        <div>
                            <h6 class="mb-1">Update Submitted</h6>
                            <small class="text-muted">{{ show_date_time($dailyWorkUpdate->created_at) }}</small>
                            <p class="text-muted mb-0 mt-1">
                                <small>By {{ $dailyWorkUpdate->user->alias_name }}</small>
                            </p>
                        </div>
                    </div>

                    {{-- Rated --}}
                    @if ($dailyWorkUpdate->rating)
                        <div class="timeline-item">
                            <div class="timeline-icon bg-label-{{ $ratingColor }}">
                                <i class="ti ti-star ti-xs"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Rated & Reviewed</h6>
                                <small class="text-muted">{{ show_date_time($dailyWorkUpdate->updated_at) }}</small>
                                <p class="text-muted mb-0 mt-1">
                                    <small>By {{ $dailyWorkUpdate->team_leader->alias_name }}</small>
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card no-print">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ti ti-bolt me-2"></i>Quick Actions
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('administration.daily_work_update.my') }}" class="btn btn-outline-primary">
                        <i class="ti ti-arrow-left me-1"></i>Back to My Updates
                    </a>

                    @can('Daily Work Update Update')
                        @if (!$dailyWorkUpdate->rating && $isTeamLeader)
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#markAsReadModal">
                                <i class="ti ti-star me-1"></i>Rate This Update
                            </button>
                        @endif
                    @endcan

                    <button type="button" class="btn btn-outline-secondary" onclick="window.print()">
                        <i class="ti ti-printer me-1"></i>Print Details
                    </button>

                    @can('Daily Work Update Delete')
                        <a href="{{ route('administration.daily_work_update.destroy', ['daily_work_update' => $dailyWorkUpdate]) }}" class="btn btn-outline-danger confirm-danger">
                            <i class="ti ti-trash me-1"></i>Delete Update
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->


{{-- Rate Work Update Modal --}}
@can ('Daily Work Update Update')
    @if (!$dailyWorkUpdate->rating && $dailyWorkUpdate->team_leader_id == auth()->user()->id)
        <div class="modal fade" data-bs-backdrop="static" id="markAsReadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-label-primary">
                        <h4 class="modal-title">
                            <i class="ti ti-star me-2"></i>Rate Work Update
                        </h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <span class="alert-icon text-info me-2">
                                <i class="ti ti-info-circle ti-xs"></i>
                            </span>
                            <span>You are rating <strong>{{ $dailyWorkUpdate->user->alias_name }}'s</strong> work update for <strong>{{ show_date($dailyWorkUpdate->date) }}</strong></span>
                        </div>

                        <form id="workUpdateStatusForm" action="{{ route('administration.daily_work_update.update', ['daily_work_update' => $dailyWorkUpdate]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf

                            {{-- Rating Selection --}}
                            <div class="mb-4">
                                <label for="rating" class="form-label fw-bold">Performance Rating <strong class="text-danger">*</strong></label>
                                <div class="row g-3">
                                    <div class="col-md-12">
                                        <div class="btn-group w-100" role="group" aria-label="Rating options">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @php
                                                    $ratingLabels = [
                                                        1 => ['label' => 'Poor', 'color' => 'danger', 'icon' => 'ti-mood-sad'],
                                                        2 => ['label' => 'Below Average', 'color' => 'warning', 'icon' => 'ti-mood-confuzed'],
                                                        3 => ['label' => 'Average', 'color' => 'info', 'icon' => 'ti-mood-neutral'],
                                                        4 => ['label' => 'Good', 'color' => 'primary', 'icon' => 'ti-mood-smile'],
                                                        5 => ['label' => 'Excellent', 'color' => 'success', 'icon' => 'ti-mood-happy'],
                                                    ];
                                                    $currentRating = $ratingLabels[$i];
                                                @endphp
                                                <input type="radio" class="btn-check" name="rating" id="rating{{ $i }}" value="{{ $i }}" autocomplete="off" required {{ request()->rating == $i ? 'checked' : '' }}>
                                                <label class="btn btn-outline-{{ $currentRating['color'] }}" for="rating{{ $i }}">
                                                    <i class="ti {{ $currentRating['icon'] }} me-1"></i>
                                                    {{ $i }} - {{ $currentRating['label'] }}
                                                </label>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @error('rating')
                                    <div class="text-danger mt-2">
                                        <small><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>

                            {{-- Rating Guide --}}
                            <div class="card bg-label-secondary mb-4">
                                <div class="card-body p-3">
                                    <h6 class="mb-2"><i class="ti ti-info-circle me-1"></i>Rating Guide</h6>
                                    <small class="text-muted">
                                        <strong>1 Star:</strong> Needs significant improvement<br>
                                        <strong>2 Stars:</strong> Below expectations<br>
                                        <strong>3 Stars:</strong> Meets basic expectations<br>
                                        <strong>4 Stars:</strong> Exceeds expectations<br>
                                        <strong>5 Stars:</strong> Outstanding performance
                                    </small>
                                </div>
                            </div>

                            {{-- Comment Section --}}
                            <div class="mb-4">
                                <label class="form-label fw-bold">Feedback & Comments</label>
                                <small class="text-muted d-block mb-2">Provide constructive feedback to help improve future work</small>
                                <div name="comment" id="commentEditor" style="min-height: 150px;">{!! old('comment') !!}</div>
                                <textarea class="d-none" name="comment" id="commentEditorInput">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="text-danger mt-2">
                                        <small><i class="ti ti-alert-circle me-1"></i>{{ $message }}</small>
                                    </div>
                                @enderror
                            </div>

                            {{-- Form Actions --}}
                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-check me-1"></i>Submit Rating & Feedback
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endcan
{{-- /Rate Work Update Modal --}}

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();

            // Quill Editor Configuration
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            // Initialize comment editor only if modal exists
            @if (!$dailyWorkUpdate->rating && auth()->id() === $dailyWorkUpdate->team_leader_id)
                var commentEditor = new Quill("#commentEditor", {
                    bounds: "#commentEditor",
                    placeholder: "Provide detailed feedback to help the employee understand their performance and areas for improvement...",
                    modules: {
                        formula: true,
                        toolbar: fullToolbar,
                    },
                    theme: "snow",
                });

                // Set the editor content to the old comment if validation fails
                @if(old('comment'))
                    commentEditor.root.innerHTML = {!! json_encode(old('comment')) !!};
                @endif

                // On form submit, transfer Quill content to hidden textarea
                $('#workUpdateStatusForm').on('submit', function(e) {
                    var commentContent = commentEditor.root.innerHTML;
                    $('#commentEditorInput').val(commentContent);

                    // Validate that a rating is selected
                    if (!$('input[name="rating"]:checked').length) {
                        e.preventDefault();
                        alert('Please select a rating before submitting.');
                        return false;
                    }
                });

                // Add dynamic feedback based on rating selection
                $('input[name="rating"]').on('change', function() {
                    var rating = $(this).val();
                    var suggestions = {
                        '1': 'Consider providing specific areas that need immediate attention and offer actionable steps for improvement.',
                        '2': 'Highlight what went wrong and suggest concrete ways to improve performance.',
                        '3': 'Point out what was done well and what could be improved to reach the next level.',
                        '4': 'Acknowledge the good work and suggest minor improvements for excellence.',
                        '5': 'Recognize outstanding performance and encourage continued excellence.'
                    };

                    // Optional: Show suggestion toast
                    console.log('Rating ' + rating + ': ' + suggestions[rating]);
                });
            @endif

            // Auto-reopen modal if there are validation errors
            @if ($errors->any())
                $('#markAsReadModal').modal('show');
            @endif

            // Print functionality
            $('.btn-print').on('click', function() {
                window.print();
            });

            // Confirm before delete
            $('.confirm-danger').on('click', function(e) {
                if (!confirm('Are you sure you want to delete this work update? This action cannot be undone.')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
