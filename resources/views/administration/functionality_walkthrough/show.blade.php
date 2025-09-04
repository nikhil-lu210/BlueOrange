@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', $walkthrough->title)

@section('css_links')
    {{--  External CSS  --}}

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
        /* File Attachment Styles */
        .custom-image-container,
        .file-thumbnail-container {
            transition: all 0.2s ease;
            border-radius: 4px;
            overflow: hidden;
        }

        .custom-image-container:hover,
        .file-thumbnail-container:hover {
            transform: scale(1.02);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .custom-image-container img {
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .file-thumbnail-container {
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border: 1px solid #e0e0e0;
            border-radius: 6px;
            text-align: center;
            min-width: 80px;
        }

        .file-thumbnail-container:hover {
            background-color: rgba(115, 103, 240, 0.1);
            border-color: #7367f0;
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
    <li class="breadcrumb-item">
        @canany (['Functionality Walkthrough Everything', 'Functionality Walkthrough Create', 'Functionality Walkthrough Update', 'Functionality Walkthrough Delete']) 
            <a href="{{ route('administration.functionality_walkthrough.index') }}">{{ __('All Walkthroughs') }}</a>
        @elsecanany (['Functionality Walkthrough Read']) 
            <a href="{{ route('administration.functionality_walkthrough.my') }}">{{ __('My Walkthroughs') }}</a>
        @endcanany
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
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
                        <button type="button" class="btn btn-sm btn-primary" id="prevStep" disabled>
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
                                    <button type="button" class="nav-link {{ $index === 0 ? 'active bg-label-primary' : '' }}" 
                                            role="tab" data-bs-toggle="tab" 
                                            data-bs-target="#navs-left-step-{{ $index + 1 }}" 
                                            aria-controls="navs-left-step-{{ $index + 1 }}" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                            data-step-index="{{ $index }}">
                                        <div class="d-flex align-items-center">
                                            <div class="step-number-small bg-primary text-white me-2">{{ $index + 1 }}</div>
                                            <div class="text-start">
                                                <div class="fw-medium">Step {{ $index + 1 }}</div>
                                                <small class="text-muted">{{ show_content($step->step_title, 25) }}</small>
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
                                    <div class="pt-0">
                                        <div class="p-4 pb-0 pt-0">
                                            <div class="d-flex align-items-center mb-4">
                                                <div class="step-number bg-primary text-white me-3">{{ $index + 1 }}</div>
                                                <div>
                                                    <h5 class="mb-1">{{ $step->step_title }}</h5>
                                                    <small class="text-muted">Step {{ $index + 1 }} of {{ $walkthrough->steps->count() }}</small>
                                                </div>
                                            </div>
                                            
                                            <div class="step-description mb-1">
                                                {!! $step->step_description !!}
                                            </div>
                                        </div>

                                        @if($step->files->isNotEmpty())
                                            <hr>
                                            <div class="mt-0 p-4 pt-1 pb-0">
                                                <h6 class="mb-3"><i class="ti ti-files me-2"></i>Step Files ({{ $step->files->count() }})</h6>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="d-flex flex-wrap gap-2 mb-3">
                                                            @foreach ($step->files as $stepFile)
                                                                @if (in_array($stepFile->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                                    <div class="custom-image-container" title="Click to view {{ $stepFile->original_name }}">
                                                                        <a href="{{ file_media_download($stepFile) }}" data-lightbox="custom-images-{{ $step->id }}" data-title="{{ $stepFile->original_name }}">
                                                                            <img src="{{ file_media_download($stepFile) }}" alt="{{ $stepFile->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                                        </a>
                                                                    </div>
                                                                @else
                                                                    <div class="file-thumbnail-container" title="Click to Download {{ $stepFile->original_name }}">
                                                                        <a href="{{ file_media_download($stepFile) }}" target="_blank" class="text-decoration-none">
                                                                            <div class="d-flex flex-column align-items-center">
                                                                                <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                                                                <span class="file-name text-center small fw-medium">
                                                                                    {{ show_content($stepFile->original_name, 15) }}
                                                                                </span>
                                                                                <small class="text-muted">{{ strtoupper(pathinfo($stepFile->original_name, PATHINFO_EXTENSION)) }}</small>
                                                                            </div>
                                                                        </a>
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
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
    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                    prevBtn.prop('disabled', true).addClass('disabled cursor-not-allowed btn-label-primary');
                } else {
                    prevBtn.prop('disabled', false).removeClass('disabled cursor-not-allowed btn-label-primary');
                }
                
                // Update next button
                if (currentStepIndex === totalSteps - 1) {
                    nextBtn.prop('disabled', true).addClass('disabled cursor-not-allowed btn-label-primary');
                } else {
                    nextBtn.prop('disabled', false).removeClass('disabled cursor-not-allowed btn-label-primary');
                }
            }
            
            // Navigate to specific step
            function navigateToStep(stepIndex) {
                if (stepIndex >= 0 && stepIndex < totalSteps) {
                    currentStepIndex = stepIndex;
                    
                    // Remove active class from all tabs
                    $('.nav-link').removeClass('active bg-label-primary');
                    $('.tab-pane').removeClass('show active');
                    
                    // Add active class to current step
                    $(`.nav-link[data-step-index="${stepIndex}"]`).addClass('active bg-label-primary');
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
