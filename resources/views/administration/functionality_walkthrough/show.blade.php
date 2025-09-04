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
            border-radius: 12px;
            margin-bottom: 20px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .step-card:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .step-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-bottom: none;
        }
        .step-number {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 15px;
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .step-number-small {
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 12px;
            border: 2px solid rgba(255,255,255,0.3);
            box-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }
        .file-item {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #e3e6f0;
            border-radius: 8px;
            margin-bottom: 10px;
            background-color: #f8f9fc;
            transition: all 0.3s ease;
        }
        .file-item:hover {
            background-color: #e3e6f0;
            border-color: #5a6acf;
        }
        .file-icon {
            margin-right: 12px;
            font-size: 24px;
            color: #5a6acf;
        }
        
        .step-description {
            line-height: 1.6;
            color: #495057;
        }
        .step-description h1, .step-description h2, .step-description h3, .step-description h4, .step-description h5, .step-description h6 {
            color: #2c3e50;
            margin-top: 20px;
            margin-bottom: 10px;
        }
        .step-description p {
            margin-bottom: 15px;
        }
        .step-description ul, .step-description ol {
            margin-bottom: 15px;
            padding-left: 20px;
        }
        .step-description blockquote {
            border-left: 4px solid #5a6acf;
            padding-left: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #6c757d;
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
<div class="row justify-content-center">
    <div class="col-md-12">
        <!-- Walkthrough Header -->
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ $walkthrough->title }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Creator" data-bs-placement="bottom">
                                    <i class="ti ti-user"></i>
                                    {{ $walkthrough->creator->alias_name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Created At">
                                    <i class="ti ti-calendar"></i>
                                    {{ show_date_time($walkthrough->created_at) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Total Steps">
                                    <i class="ti ti-list-numbers"></i>
                                    {{ $walkthrough->steps->count() }} Steps
                                </li>
                            </ul>
                            @if ($walkthrough->assigned_roles->isNotEmpty())
                                <ul class="list-inline mb-0 mt-3 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-1">
                                    @foreach ($walkthrough->assigned_roles as $role)
                                        <li class="list-inline-item">
                                            <span class="badge bg-label-primary">{{ $role->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <ul class="list-inline mb-0 mt-3">
                                    <li class="list-inline-item">
                                        <span class="badge bg-label-success">All Users</span>
                                    </li>
                                </ul>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-outline-primary btn-icon rounded-pill" data-bs-toggle="tooltip" title="All Walkthroughs">
                                <i class="ti ti-list"></i>
                            </a>
                            @can ('Functionality Walkthrough Update')
                                <a href="{{ route('administration.functionality_walkthrough.edit', ['functionalityWalkthrough' => $walkthrough]) }}" class="btn btn-primary btn-icon rounded-pill" data-bs-toggle="tooltip" title="Edit Walkthrough">
                                    <i class="ti ti-pencil"></i>
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- Steps Navigation -->
        @if($walkthrough->steps->isNotEmpty())
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="ti ti-list-numbers me-2"></i>Walkthrough Steps</h6>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-sm btn-outline-primary" id="prevStep" disabled>
                            <i class="ti ti-chevron-left me-1"></i>Previous
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" id="nextStep">
                            Next<i class="ti ti-chevron-right ms-1"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="nav-align-left d-flex">
                        <ul class="nav nav-tabs flex-column flex-shrink-0" role="tablist" style="width: 250px; min-width: 250px; margin-left: auto;">
                            @foreach($walkthrough->steps as $index => $step)
                                <li class="nav-item">
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active' : '' }}" 
                                            role="tab" data-bs-toggle="tab" 
                                            data-bs-target="#navs-left-step-{{ $index + 1 }}" 
                                            aria-controls="navs-left-step-{{ $index + 1 }}" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                            data-step-index="{{ $index }}">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number-small bg-primary text-white me-2">{{ $index + 1 }}</div>
                                            <div class="text-start">
                                                <div class="fw-medium">Step {{ $index + 1 }}</div>
                                                <small class="text-muted">{{ Str::limit($step->step_title, 25) }}</small>
                                            </div>
                                        </div>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content flex-grow-1 pt-0">
                            @foreach($walkthrough->steps as $index => $step)
                                <div class="tab-pane fade {{ $index === 0 ? 'show active' : '' }}" 
                                     id="navs-left-step-{{ $index + 1 }}">
                                    <div class="p-4 pt-0">
                                        <div class="d-flex align-items-center mb-4">
                                            <div class="step-number bg-primary text-white me-3">{{ $index + 1 }}</div>
                                            <div>
                                                <h5 class="mb-1">{{ $step->step_title }}</h5>
                                                <small class="text-muted">Step {{ $index + 1 }} of {{ $walkthrough->steps->count() }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="step-description mb-4">
                                            {!! $step->step_description !!}
                                        </div>

                                        @if($step->files->isNotEmpty())
                                            <div class="mt-4">
                                                <h6 class="mb-3"><i class="ti ti-files me-2"></i>Step Files ({{ $step->files->count() }})</h6>
                                                <div class="row">
                                                    @foreach($step->files as $file)
                                                        <div class="col-md-6 mb-3">
                                                            <div class="file-item">
                                                                <i class="ti ti-file file-icon"></i>
                                                                <div class="flex-grow-1">
                                                                    <div class="fw-medium">{{ $file->file_name }}</div>
                                                                    <small class="text-muted">{{ get_file_media_size($file) }}</small>
                                                                </div>
                                                                <a href="{{ file_media_download($file) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Download File">
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="card mb-4">
                <div class="card-body text-center py-5">
                    <i class="ti ti-file-text ti-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No Steps Found</h5>
                    <p class="text-muted">This walkthrough doesn't have any steps yet.</p>
                </div>
            </div>
        @endif
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
        $(document).ready(function() {
            // Initialize tooltips
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            let currentStepIndex = 0;
            const totalSteps = {{ $walkthrough->steps->count() }};
            
            // Update navigation buttons
            function updateNavigationButtons() {
                const prevBtn = $('#prevStep');
                const nextBtn = $('#nextStep');
                
                // Update previous button
                if (currentStepIndex === 0) {
                    prevBtn.prop('disabled', true).addClass('disabled');
                } else {
                    prevBtn.prop('disabled', false).removeClass('disabled');
                }
                
                // Update next button
                if (currentStepIndex === totalSteps - 1) {
                    nextBtn.prop('disabled', true).addClass('disabled');
                } else {
                    nextBtn.prop('disabled', false).removeClass('disabled');
                }
            }
            
            // Navigate to specific step
            function navigateToStep(stepIndex) {
                if (stepIndex >= 0 && stepIndex < totalSteps) {
                    currentStepIndex = stepIndex;
                    
                    // Remove active class from all tabs
                    $('.nav-link').removeClass('active');
                    $('.tab-pane').removeClass('show active');
                    
                    // Add active class to current step
                    $(`.nav-link[data-step-index="${stepIndex}"]`).addClass('active');
                    $(`#navs-left-step-${stepIndex + 1}`).addClass('show active');
                    
                    // Update navigation buttons
                    updateNavigationButtons();
                }
            }
            
            // Previous step button
            $('#prevStep').click(function() {
                if (currentStepIndex > 0) {
                    navigateToStep(currentStepIndex - 1);
                }
            });
            
            // Next step button
            $('#nextStep').click(function() {
                if (currentStepIndex < totalSteps - 1) {
                    navigateToStep(currentStepIndex + 1);
                }
            });
            
            // Step tab click
            $('.nav-link[data-step-index]').click(function() {
                const stepIndex = parseInt($(this).data('step-index'));
                navigateToStep(stepIndex);
            });
            
            // Initialize navigation buttons
            updateNavigationButtons();
            
            // Add file download tracking (optional)
            $('.file-item a[href*="download"]').click(function() {
                const fileName = $(this).closest('.file-item').find('.fw-medium').text();
                console.log('Downloading file:', fileName);
            });
            
            // Keyboard navigation
            $(document).keydown(function(e) {
                if (e.key === 'ArrowLeft' && currentStepIndex > 0) {
                    navigateToStep(currentStepIndex - 1);
                } else if (e.key === 'ArrowRight' && currentStepIndex < totalSteps - 1) {
                    navigateToStep(currentStepIndex + 1);
                }
            });
        });
    </script>
@endsection
