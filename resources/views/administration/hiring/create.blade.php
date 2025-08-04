@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Add New Candidate'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .dropzone {
            border: 2px dashed #d9dee3;
            border-radius: 8px;
            background: #f8f9fa;
            min-height: 120px;
        }
        .dropzone.dz-drag-hover {
            border-color: #696cff;
            background: #f3f4ff;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Add New Candidate') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.index') }}">{{ __('Employee Hiring') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Add Candidate') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Candidate Information') }}</h5>
                <a href="{{ route('administration.hiring.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to List') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.hiring.store') }}" method="POST" enctype="multipart/form-data" id="candidateForm">
                    @csrf

                    <div class="row g-3">
                        <!-- Basic Information -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3">{{ __('Basic Information') }}</h6>
                        </div>

                        <div class="col-md-6">
                            <label for="name" class="form-label">{{ __('Full Name') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="phone" class="form-label">{{ __('Phone Number') }} <span class="text-danger">*</span></label>
                            <input type="tel"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   id="phone"
                                   name="phone"
                                   value="{{ old('phone') }}"
                                   required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="expected_role" class="form-label">{{ __('Expected Role') }} <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('expected_role') is-invalid @enderror"
                                   id="expected_role"
                                   name="expected_role"
                                   value="{{ old('expected_role') }}"
                                   required>
                            @error('expected_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="expected_salary" class="form-label">{{ __('Expected Salary') }}</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number"
                                       class="form-control @error('expected_salary') is-invalid @enderror"
                                       id="expected_salary"
                                       name="expected_salary"
                                       value="{{ old('expected_salary') }}"
                                       step="0.01"
                                       min="0">
                                @error('expected_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      placeholder="{{ __('Any additional notes about the candidate...') }}">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Stage Assignments -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-4">{{ __('Stage Assignments') }}</h6>
                        </div>

                        <!-- Basic Interview Assignment -->
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white">
                                    <h6 class="mb-0 text-white">{{ __('Stage 1: Basic Interview') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="stage1_evaluator" class="form-label">{{ __('Assign Interviewer') }} <span class="text-danger">*</span></label>
                                            <select class="form-select select2 @error('stage1_evaluator') is-invalid @enderror"
                                                    id="stage1_evaluator"
                                                    name="stage1_evaluator"
                                                    required>
                                                <option value="">{{ __('Select Interviewer') }}</option>
                                                @foreach($evaluators as $evaluator)
                                                    <option value="{{ $evaluator->id }}" {{ old('stage1_evaluator') == $evaluator->id ? 'selected' : '' }}>
                                                        {{ $evaluator->name }} ({{ $evaluator->employee->alias_name ?? $evaluator->userid }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('stage1_evaluator')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stage1_scheduled_at" class="form-label">{{ __('Interview Date & Time') }} <span class="text-danger">*</span></label>
                                            <input type="datetime-local"
                                                   class="form-control @error('stage1_scheduled_at') is-invalid @enderror"
                                                   id="stage1_scheduled_at"
                                                   name="stage1_scheduled_at"
                                                   value="{{ old('stage1_scheduled_at') }}"
                                                   required>
                                            @error('stage1_scheduled_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Workshop Assignment -->
                        <div class="col-12">
                            <div class="card border-warning">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">{{ __('Stage 2: Workshop') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="stage2_evaluator" class="form-label">{{ __('Assign Workshop Monitor') }}</label>
                                            <select class="form-select select2 @error('stage2_evaluator') is-invalid @enderror"
                                                    id="stage2_evaluator"
                                                    name="stage2_evaluator">
                                                <option value="">{{ __('Select Workshop Monitor') }}</option>
                                                @foreach($evaluators as $evaluator)
                                                    <option value="{{ $evaluator->id }}" {{ old('stage2_evaluator') == $evaluator->id ? 'selected' : '' }}>
                                                        {{ $evaluator->name }} ({{ $evaluator->employee->alias_name ?? $evaluator->userid }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('stage2_evaluator')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stage2_scheduled_at" class="form-label">{{ __('Workshop Date & Time') }}</label>
                                            <input type="datetime-local"
                                                   class="form-control @error('stage2_scheduled_at') is-invalid @enderror"
                                                   id="stage2_scheduled_at"
                                                   name="stage2_scheduled_at"
                                                   value="{{ old('stage2_scheduled_at') }}">
                                            @error('stage2_scheduled_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Final Interview Assignment -->
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white">
                                    <h6 class="mb-0 text-white">{{ __('Stage 3: Final Interview') }}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="stage3_evaluator" class="form-label">{{ __('Assign Final Interviewer') }}</label>
                                            <select class="form-select select2 @error('stage3_evaluator') is-invalid @enderror"
                                                    id="stage3_evaluator"
                                                    name="stage3_evaluator">
                                                <option value="">{{ __('Select Final Interviewer') }}</option>
                                                @foreach($evaluators as $evaluator)
                                                    <option value="{{ $evaluator->id }}" {{ old('stage3_evaluator') == $evaluator->id ? 'selected' : '' }}>
                                                        {{ $evaluator->name }} ({{ $evaluator->employee->alias_name ?? $evaluator->userid }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('stage3_evaluator')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="stage3_scheduled_at" class="form-label">{{ __('Final Interview Date & Time') }}</label>
                                            <input type="datetime-local"
                                                   class="form-control @error('stage3_scheduled_at') is-invalid @enderror"
                                                   id="stage3_scheduled_at"
                                                   name="stage3_scheduled_at"
                                                   value="{{ old('stage3_scheduled_at') }}">
                                            @error('stage3_scheduled_at')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- File Uploads -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-4">{{ __('Documents') }}</h6>
                        </div>

                        <div class="col-12">
                            <label for="resume" class="form-label">{{ __('Resume') }} <span class="text-danger">*</span></label>
                            <input type="file"
                                   class="form-control @error('resume') is-invalid @enderror"
                                   id="resume"
                                   name="resume"
                                   accept=".pdf,.doc,.docx"
                                   required>
                            <div class="form-text">{{ __('Accepted formats: PDF, DOC, DOCX. Maximum size: 5MB') }}</div>
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="files" class="form-label">{{ __('Additional Documents') }}</label>
                            <div class="dropzone" id="additional-files-dropzone">
                                <div class="dz-message">
                                    <div class="text-center">
                                        <i class="ti ti-cloud-upload display-4 text-muted mb-2"></i>
                                        <h6>{{ __('Drop files here or click to upload') }}</h6>
                                        <small class="text-muted">{{ __('PDF, DOC, DOCX, JPG, JPEG, PNG - Max 5MB each') }}</small>
                                    </div>
                                </div>
                            </div>
                            @error('files.*')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('administration.hiring.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> {{ __('Add Candidate') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/dropzone/multi-file-upload-nikhil.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2({
                placeholder: 'Select an option',
                allowClear: true
            });

            // Initialize Dropzone for additional files
            Dropzone.autoDiscover = false;

            const additionalFilesDropzone = new Dropzone("#additional-files-dropzone", {
                url: "#", // We'll handle this via form submission
                autoProcessQueue: false,
                uploadMultiple: true,
                parallelUploads: 10,
                maxFiles: 10,
                maxFilesize: 5, // MB
                acceptedFiles: ".pdf,.doc,.docx,.jpg,.jpeg,.png",
                addRemoveLinks: true,
                paramName: "files",

                init: function() {
                    const dropzone = this;

                    // Handle form submission
                    $("#candidateForm").on("submit", function(e) {
                        if (dropzone.getQueuedFiles().length > 0) {
                            e.preventDefault();

                            // Add files to form data
                            const formData = new FormData(this);
                            dropzone.getQueuedFiles().forEach(function(file, index) {
                                formData.append('files[]', file);
                            });

                            // Submit form with files
                            fetch(this.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                }
                            }).then(response => {
                                if (response.ok) {
                                    window.location.href = response.url;
                                } else {
                                    response.text().then(text => {
                                        document.body.innerHTML = text;
                                    });
                                }
                            }).catch(error => {
                                console.error('Error:', error);
                                alert('An error occurred while submitting the form.');
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
