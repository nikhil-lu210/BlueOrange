@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit Functionality Walkthrough'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    .step-container {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        background-color: #f8f9fa;
    }
    .step-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .step-number {
        background-color: #007bff;
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 10px;
    }
    .existing-file {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-bottom: 5px;
        background-color: white;
    }
    .file-info {
        display: flex;
        align-items: center;
        flex-grow: 1;
    }
    .file-icon {
        margin-right: 10px;
        color: #007bff;
    }
    .file-details {
        flex-grow: 1;
    }
    .file-name {
        font-weight: 500;
        margin-bottom: 2px;
    }
    .file-size {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .file-actions {
        display: flex;
        gap: 5px;
    }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Edit Functionality Walkthrough') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthroughs') }}</li>
    <li class="breadcrumb-item active">{{ __('Edit Walkthrough') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Edit Functionality Walkthrough</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.functionality_walkthrough.show', $walkthrough) }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-eye ti-xs me-1"></span>
                        View Walkthrough
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="walkthroughForm" action="{{ route('administration.functionality_walkthrough.update', $walkthrough) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Walkthrough Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $walkthrough->title) }}" placeholder="{{ __('Enter walkthrough title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-12">
                            <label for="assigned_roles" class="form-label">Select Assigned Roles</label>
                            <select name="assigned_roles[]" id="assigned_roles" class="select2 form-select @error('assigned_roles') is-invalid @enderror" data-allow-clear="true" multiple autofocus>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(collect(old('assigned_roles', $walkthrough->assigned_roles?->pluck('id')->toArray() ?? []))->contains($role->id))>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small><b class="text-primary">Note:</b> If the walkthrough is for all users, then don't select any roles.</small>
                            <br>
                            @error('assigned_roles')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Steps Section -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Walkthrough Steps <strong class="text-danger">*</strong></h6>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addStep">
                                <i class="ti ti-plus me-1"></i>Add Step
                            </button>
                        </div>

                        <div id="stepsContainer">
                            <!-- Existing steps will be loaded here -->
                        </div>

                        @error('steps')
                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.functionality_walkthrough.show', $walkthrough) }}" class="btn btn-outline-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Walkthrough</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        let stepCounter = 0;
        let editors = {};
        let existingSteps = {!! json_encode($walkthrough->steps->map(function($step) {
            return [
                'id' => $step->id,
                'title' => $step->step_title,
                'description' => $step->step_description,
                'files' => $step->files->map(function($file) {
                    return [
                        'id' => $file->id,
                        'name' => $file->file_name,
                        'size' => $file->file_size,
                        'url' => $file->file_path
                    ];
                })
            ];
        })) !!};

        $(document).ready(function () {
            // Load existing steps
            loadExistingSteps();

            // Add step button click
            $('#addStep').click(function() {
                addStep();
            });

            // Remove step button click (delegated event)
            $(document).on('click', '.removeStep', function() {
                const stepIndex = $(this).data('step-index');
                $(`#step-${stepIndex}`).remove();
                updateStepNumbers();
            });

            // Remove existing file
            $(document).on('click', '.remove-existing-file', function() {
                const fileId = $(this).data('file-id');
                const stepIndex = $(this).data('step-index');
                removeExistingFile(fileId, stepIndex);
            });

            // Form submit
            $('#walkthroughForm').on('submit', function() {
                // Update all editor contents
                Object.keys(editors).forEach(function(stepIndex) {
                    const editor = editors[stepIndex];
                    if (editor) {
                        $(`#step-${stepIndex} .step-description-input`).val(editor.root.innerHTML);
                    }
                });

                // Ensure selectAllValues is removed from assigned_roles before submission
                var assignedRolesSelect = $('#assigned_roles');
                var selectedValues = assignedRolesSelect.val() || [];

                if (selectedValues.includes('selectAllValues')) {
                    selectedValues = selectedValues.filter(val => val !== 'selectAllValues');
                    assignedRolesSelect.val(selectedValues);
                }
            });
        });

        function loadExistingSteps() {
            existingSteps.forEach((step, index) => {
                addStep(step.title, step.description, step.files, step.id);
            });
        }

        function addStep(title = '', description = '', files = [], stepId = null) {
            stepCounter++;
            const stepIndex = stepCounter;
            
            // Create existing files HTML
            let existingFilesHtml = '';
            if (files && files.length > 0) {
                existingFilesHtml = '<div class="mb-3"><label class="form-label">Existing Files</label>';
                files.forEach(file => {
                    existingFilesHtml += `
                        <div class="existing-file">
                            <div class="file-info">
                                <i class="ti ti-file file-icon"></i>
                                <div class="file-details">
                                    <div class="file-name">${file.name}</div>
                                    <div class="file-size">${formatFileSize(file.size)}</div>
                                </div>
                            </div>
                            <div class="file-actions">
                                <a href="${file.url}" class="btn btn-sm btn-outline-primary" target="_blank" title="Download">
                                    <i class="ti ti-download"></i>
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-danger remove-existing-file" 
                                        data-file-id="${file.id}" data-step-index="${stepIndex}" title="Remove">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                });
                existingFilesHtml += '</div>';
            }

            const stepHtml = `
                <div class="step-container" id="step-${stepIndex}">
                    <div class="d-flex justify-content-between align-items-center step-header">
                        <div class="d-flex align-items-center">
                            <div class="step-number">${stepIndex}</div>
                            <h6 class="mb-0">Step ${stepIndex}</h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-icon btn-danger removeStep" data-step-index="${stepIndex}" title="Remove Step ${stepIndex}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="steps[${stepIndex}][step_title]" class="form-label">Step Title <strong class="text-danger">*</strong></label>
                            <input type="text" name="steps[${stepIndex}][step_title]" class="form-control" placeholder="Enter step title" value="${title}" required>
                            ${stepId ? `<input type="hidden" name="steps[${stepIndex}][id]" value="${stepId}">` : ''}
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Step Description <strong class="text-danger">*</strong></label>
                            <div name="steps[${stepIndex}][step_description]" id="step-editor-${stepIndex}" class="step-editor"></div>
                            <textarea class="d-none step-description-input" name="steps[${stepIndex}][step_description]">${description}</textarea>
                        </div>

                        ${existingFilesHtml}

                        <div class="mb-3 col-md-12">
                            <label for="steps[${stepIndex}][files][]" class="form-label">Add New Files</label>
                            <input type="file" name="steps[${stepIndex}][files][]" class="form-control" multiple>
                            <small class="text-muted">Select files to add to this step. Existing files will be preserved unless removed above.</small>
                        </div>
                    </div>
                </div>
            `;

            $('#stepsContainer').append(stepHtml);

            // Initialize Quill editor for this step
            initializeEditor(stepIndex, description);
        }

        function initializeEditor(stepIndex, content = '') {
            const fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            const editor = new Quill(`#step-editor-${stepIndex}`, {
                bounds: `#step-editor-${stepIndex}`,
                placeholder: "Enter step description...",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set content if provided
            if (content) {
                editor.root.innerHTML = content;
            }

            editors[stepIndex] = editor;
        }

        function removeExistingFile(fileId, stepIndex) {
            // Add hidden input to mark file for deletion
            const deleteInput = `<input type="hidden" name="steps[${stepIndex}][delete_files][]" value="${fileId}">`;
            $(`#step-${stepIndex}`).append(deleteInput);
            
            // Remove the file from UI
            $(`[data-file-id="${fileId}"]`).closest('.existing-file').remove();
        }

        function updateStepNumbers() {
            $('.step-container').each(function(index) {
                const stepNumber = index + 1;
                $(this).find('.step-number').text(stepNumber);
                $(this).find('h6').text(`Step ${stepNumber}`);
                $(this).attr('id', `step-${stepNumber}`);
                
                // Update all input names and IDs
                $(this).find('input, textarea, select').each(function() {
                    const name = $(this).attr('name');
                    if (name && name.includes('[')) {
                        const newName = name.replace(/steps\[\d+\]/, `steps[${stepNumber}]`);
                        $(this).attr('name', newName);
                    }
                });
            });
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }
    </script>
@endsection
