@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create New Functionality Walkthrough'))

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
        justify-content: between;
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
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create New Functionality Walkthrough') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Functionality Walkthroughs') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Walkthrough') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Functionality Walkthrough</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.functionality_walkthrough.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Walkthroughs
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="walkthroughForm" action="{{ route('administration.functionality_walkthrough.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Walkthrough Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ __('Enter walkthrough title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="assigned_roles" class="form-label">Select Assigned Roles</label>
                            <select name="assigned_roles[]" id="assigned_roles" class="select2 form-select @error('assigned_roles') is-invalid @enderror" data-allow-clear="true" multiple autofocus>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('assigned_roles', [])) ? 'selected' : '' }}>
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

                        <div class="mb-3 col-md-12">
                            <label for="files[]" class="form-label">{{ __('Walkthrough Files') }}</label>
                            <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Walkthrough Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
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
                            <!-- Steps will be added here dynamically -->
                        </div>

                        @error('steps')
                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.functionality_walkthrough.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Create Walkthrough</button>
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

        $(document).ready(function () {
            // Add first step
            addStep();

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

        function addStep() {
            stepCounter++;
            const stepHtml = `
                <div class="step-container" id="step-${stepCounter}">
                    <div class="d-flex justify-content-between align-items-center step-header">
                        <div class="d-flex align-items-center">
                            <div class="step-number">${stepCounter}</div>
                            <h6 class="mb-0">Step ${stepCounter}</h6>
                        </div>
                        <button type="button" class="btn btn-sm btn-icon btn-danger removeStep" data-step-index="${stepCounter}" title="Remove Step ${stepCounter}">
                            <i class="ti ti-trash"></i>
                        </button>
                    </div>

                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="steps[${stepCounter}][step_title]" class="form-label">Step Title <strong class="text-danger">*</strong></label>
                            <input type="text" name="steps[${stepCounter}][step_title]" class="form-control" placeholder="Enter step title" required>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label class="form-label">Step Description <strong class="text-danger">*</strong></label>
                            <div name="steps[${stepCounter}][step_description]" id="step-editor-${stepCounter}" class="step-editor"></div>
                            <textarea class="d-none step-description-input" name="steps[${stepCounter}][step_description]"></textarea>
                        </div>

                        <div class="mb-3 col-md-12">
                            <label for="steps[${stepCounter}][files][]" class="form-label">Step Files</label>
                            <input type="file" name="steps[${stepCounter}][files][]" class="form-control" multiple>
                        </div>
                    </div>
                </div>
            `;

            $('#stepsContainer').append(stepHtml);

            // Initialize Quill editor for this step
            initializeEditor(stepCounter);
        }

        function initializeEditor(stepIndex) {
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

            editors[stepIndex] = editor;
        }

        function updateStepNumbers() {
            $('.step-container').each(function(index) {
                const stepNumber = index + 1;
                $(this).find('.step-number').text(stepNumber);
                $(this).find('h6').text(`Step ${stepNumber}`);
                $(this).attr('id', `step-${stepNumber}`);
            });
        }
    </script>
@endsection
