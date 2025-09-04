@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', $walkthrough->title)

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    .step-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .step-header {
        background-color: #f8f9fa;
        padding: 15px 20px;
        border-bottom: 1px solid #dee2e6;
    }
    .step-number {
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 5px;
    }
    .step-content {
        padding: 20px;
    }
    .file-item {
        display: flex;
        align-items: center;
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
    }
    .file-icon {
        margin-right: 10px;
        font-size: 20px;
    }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ $walkthrough->title }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthroughs') }}</li>
    <li class="breadcrumb-item active">{{ $walkthrough->title }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ $walkthrough->title }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Walkthroughs
                    </a>
                    @can('Functionality Walkthrough Update')
                        <a href="{{ route('administration.functionality_walkthrough.edit', $walkthrough) }}" class="btn btn-sm btn-outline-primary">
                            <span class="tf-icon ti ti-pencil ti-xs me-1"></span>
                            Edit
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <!-- Walkthrough Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6>Created By:</h6>
                        <div class="d-flex align-items-center">
                            {!! show_user_name_and_avatar($walkthrough->creator, name: null) !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end">
                            <p class="mb-1"><strong>Created:</strong> {{ $walkthrough->created_at->format('F j, Y \a\t g:i A') }}</p>
                            <p class="mb-1"><strong>Last Updated:</strong> {{ $walkthrough->updated_at->format('F j, Y \a\t g:i A') }}</p>
                            <p class="mb-0"><strong>Steps:</strong> {{ $walkthrough->steps->count() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Assigned Roles -->
                <div class="mb-4">
                    <h6>Assigned Roles:</h6>
                    @if($walkthrough->assigned_roles->isNotEmpty())
                        @foreach($walkthrough->assigned_roles as $role)
                            <span class="badge bg-label-primary me-2 mb-2">{{ $role->name }}</span>
                        @endforeach
                    @else
                        <span class="badge bg-label-success">All Users</span>
                    @endif
                </div>

                <!-- Walkthrough Files -->
                @if($walkthrough->files->isNotEmpty())
                    <div class="mb-4">
                        <h6>Walkthrough Files:</h6>
                        <div class="row">
                            @foreach($walkthrough->files as $file)
                                <div class="col-md-6 mb-2">
                                    <div class="file-item">
                                        <i class="ti ti-file file-icon text-primary"></i>
                                        <div class="flex-grow-1">
                                            <div class="fw-medium">{{ $file->file_name }}</div>
                                            <small class="text-muted">{{ get_file_media_size($file) }}</small>
                                        </div>
                                        <a href="{{ file_media_download($file) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Steps -->
                <div class="mb-4">
                    <h6>Walkthrough Steps:</h6>
                    @forelse($walkthrough->steps as $index => $step)
                        <div class="step-card">
                            <div class="step-header">
                                <div class="d-flex align-items-center">
                                    <div class="step-number bg-label-primary border-primary">{{ $index + 1 }}</div>
                                    <h6 class="mb-0">
                                        <span class="text-dark text-bold">Step {{ $index + 1 }}:</span> 
                                        {{ $step->step_title }}
                                    </h6>
                                </div>
                            </div>
                            <div class="step-content">
                                <div class="step-description">
                                    {!! $step->step_description !!}
                                </div>

                                @if($step->files->isNotEmpty())
                                    <div class="mt-3">
                                        <h6 class="mb-2">Step Files:</h6>
                                        <div class="row">
                                            @foreach($step->files as $file)
                                                <div class="col-md-6 mb-2">
                                                    <div class="file-item">
                                                        <i class="ti ti-file file-icon text-primary"></i>
                                                        <div class="flex-grow-1">
                                                            <div class="fw-medium">{{ $file->file_name }}</div>
                                                            <small class="text-muted">{{ get_file_media_size($file) }}</small>
                                                        </div>
                                                        <a href="{{ file_media_download($file) }}" class="btn btn-sm btn-outline-primary">
                                                            <i class="ti ti-download"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <div class="text-muted">
                                <i class="ti ti-file-text ti-3x mb-3"></i>
                                <p>No steps found for this walkthrough.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
