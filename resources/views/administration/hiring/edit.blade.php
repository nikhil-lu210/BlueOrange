@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit Candidate'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/dropzone/dropzone.css') }}" />
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
    <b class="text-uppercase">{{ __('Edit Candidate') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.index') }}">{{ __('Employee Hiring') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.hiring.show', $hiring_candidate) }}">{{ $hiring_candidate->name }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Edit Candidate Information') }}</h5>
                <a href="{{ route('administration.hiring.show', $hiring_candidate) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="ti ti-arrow-left"></i> {{ __('Back to Details') }}
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.hiring.update', $hiring_candidate) }}" method="POST" enctype="multipart/form-data" id="candidateForm">
                    @csrf
                    @method('PUT')

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
                                   value="{{ old('name', $hiring_candidate->name) }}" 
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
                                   value="{{ old('email', $hiring_candidate->email) }}" 
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
                                   value="{{ old('phone', $hiring_candidate->phone) }}" 
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
                                   value="{{ old('expected_role', $hiring_candidate->expected_role) }}" 
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
                                       value="{{ old('expected_salary', $hiring_candidate->expected_salary) }}" 
                                       step="0.01" 
                                       min="0">
                                @error('expected_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="status" class="form-label">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                @foreach(\App\Models\Hiring\HiringCandidate::getStatuses() as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $hiring_candidate->status) == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="col-12">
                            <label for="notes" class="form-label">{{ __('Notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="{{ __('Any additional notes about the candidate...') }}">{{ old('notes', $hiring_candidate->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- File Uploads -->
                        <div class="col-12">
                            <h6 class="text-primary mb-3 mt-4">{{ __('Additional Documents') }}</h6>
                        </div>

                        <div class="col-12">
                            <label for="files" class="form-label">{{ __('Upload Additional Documents') }}</label>
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

                        <!-- Existing Files -->
                        @if($hiring_candidate->files->count() > 0)
                            <div class="col-12">
                                <h6 class="text-muted mb-3">{{ __('Existing Documents') }}</h6>
                                <div class="row g-2">
                                    @foreach($hiring_candidate->files as $file)
                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <i class="ti ti-file-text me-2"></i>
                                                        <div class="flex-grow-1">
                                                            <a href="{{ Storage::url($file->file_path) }}" target="_blank" class="text-decoration-none">
                                                                {{ $file->file_name }}
                                                            </a>
                                                            @if($file->note)
                                                                <small class="text-muted d-block">{{ $file->note }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submit Buttons -->
                        <div class="col-12">
                            <hr class="my-4">
                            <div class="d-flex justify-content-end gap-3">
                                <a href="{{ route('administration.hiring.show', $hiring_candidate) }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-device-floppy"></i> {{ __('Update Candidate') }}
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
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
