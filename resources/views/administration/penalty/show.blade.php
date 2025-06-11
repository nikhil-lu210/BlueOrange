@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Penalty Details'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- Lightbox CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/lightbox2/css/lightbox.min.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .penalty-details .info-label {
            font-weight: 600;
            color: #566a7f;
        }
        .penalty-details .info-value {
            color: #32394e;
        }
        .file-item {
            border: 1px solid #e7eaf3;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }
        .file-item:hover {
            border-color: #696cff;
            box-shadow: 0 2px 8px rgba(105, 108, 255, 0.1);
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Penalty Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.penalty.index') }}">{{ __('All Penalties') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Penalty Details') }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Penalty Information') }}</h5>
                <a href="{{ route('administration.penalty.index') }}" class="btn btn-outline-secondary">
                    <i class="ti ti-arrow-left me-1"></i>{{ __('Back to List') }}
                </a>
            </div>
            <div class="card-body penalty-details">
                <div class="row">
                    <!-- Employee Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">{{ __('Employee Information') }}</h6>
                        <div class="d-flex align-items-center mb-3">
                            @if($penalty->user->media->isNotEmpty())
                                <img src="{{ $penalty->user->media->first()->getUrl('thumb_color') }}" 
                                     alt="{{ $penalty->user->name }}" 
                                     class="rounded-circle me-3" 
                                     width="60" height="60">
                            @else
                                <div class="avatar avatar-lg me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary fs-4">
                                        {{ substr($penalty->user->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $penalty->user->name }}</h6>
                                <small class="text-muted">{{ $penalty->user->employee->alias_name ?? 'N/A' }}</small>
                                <br>
                                <small class="text-muted">{{ $penalty->user->employee->official_email ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Penalty Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">{{ __('Penalty Details') }}</h6>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Type:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="badge bg-label-warning">{{ $penalty->type }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Penalty Time:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-value fw-medium">{{ $penalty->total_time_formatted }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Created:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-value">{{ $penalty->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Attendance Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">{{ __('Related Attendance') }}</h6>
                        <div class="row">
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Date:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-value">{{ $penalty->attendance->clock_in_date ?? 'N/A' }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Clock In:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-value">{{ $penalty->attendance->clock_in ? $penalty->attendance->clock_in->format('H:i') : 'N/A' }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Clock Out:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-value">{{ $penalty->attendance->clock_out ? $penalty->attendance->clock_out->format('H:i') : 'Ongoing' }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="info-label">{{ __('Type:') }}</span>
                            </div>
                            <div class="col-6 mb-2">
                                <span class="badge bg-label-info">{{ $penalty->attendance->type ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Creator Information -->
                    <div class="col-md-6 mb-4">
                        <h6 class="text-primary mb-3">{{ __('Created By') }}</h6>
                        <div class="d-flex align-items-center">
                            @if($penalty->creator->media->isNotEmpty())
                                <img src="{{ $penalty->creator->media->first()->getUrl('thumb_color') }}" 
                                     alt="{{ $penalty->creator->name }}" 
                                     class="rounded-circle me-3" 
                                     width="50" height="50">
                            @else
                                <div class="avatar me-3">
                                    <span class="avatar-initial rounded-circle bg-label-secondary">
                                        {{ substr($penalty->creator->name, 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-1">{{ $penalty->creator->name }}</h6>
                                <small class="text-muted">{{ $penalty->creator->employee->alias_name ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="mb-4">
                    <h6 class="text-primary mb-3">{{ __('Reason for Penalty') }}</h6>
                    <div class="bg-light p-3 rounded">
                        <p class="mb-0">{{ $penalty->reason }}</p>
                    </div>
                </div>

                <!-- Penalty Proof Files -->
                @if($penalty->files->count() > 0)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3">{{ __('Penalty Proof Files') }}</h6>
                        <div class="row">
                            @foreach($penalty->files as $file)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="file-item">
                                        @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                            <a href="{{ asset('storage/' . $file->file_path) }}" data-lightbox="penalty-files" data-title="{{ $file->original_name }}">
                                                <img src="{{ asset('storage/' . $file->file_path) }}" 
                                                     alt="{{ $file->original_name }}" 
                                                     class="img-fluid rounded mb-2" 
                                                     style="max-height: 150px; width: 100%; object-fit: cover;">
                                            </a>
                                        @else
                                            <div class="text-center mb-2">
                                                <i class="ti ti-file-text display-4 text-muted"></i>
                                            </div>
                                        @endif
                                        <h6 class="mb-1">{{ $file->original_name }}</h6>
                                        <small class="text-muted">{{ number_format($file->file_size / 1024, 2) }} KB</small>
                                        <div class="mt-2">
                                            <a href="{{ route('administration.file.download', $file) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-download me-1"></i>{{ __('Download') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('vendor_js')
    {{--  External JS  --}}
    <!-- Lightbox JS -->
    <script src="{{ asset('assets/vendor/libs/lightbox2/js/lightbox.min.js') }}"></script>
@endsection

@section('custom_js')
    {{--  External JS  --}}
    <script>
        $(document).ready(function() {
            // Initialize lightbox
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            });
        });
    </script>
@endsection
