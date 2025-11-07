@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create New Task'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create New Task') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Tasks') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Task') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Task</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.task.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Tasks
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="taskForm" action="{{ route('administration.task.store') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-8">
                            <label for="parent_task_id" class="form-label">{{ __('Select Parent Task') }}</label>
                            <select name="parent_task_id" id="parent_task_id" class="select2 form-select @error('parent_task_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->parent_task_id) ? 'selected' : '' }}>Select Parent Task</option>
                                @foreach ($tasks as $task)
                                    <option value="{{ $task->id }}" {{ $task->id == request()->parent_task_id ? 'selected' : '' }}>
                                        {{ $task->title }} - ({{ $task->taskid }})
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                <span class="text-dark text-bold">Note:</span> If you select a parent task, the task will be created as a sub-task of the selected parent task.
                            </small>
                            @error('parent_task_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label class="form-label">Deadline</label>
                            <input type="text" name="deadline" value="{{ old('deadline') }}" class="form-control  date-picker" placeholder="YYYY-MM-DD" tabindex="-1"/>
                            <small class="text-muted">
                                <span class="text-dark text-bold">Note:</span> Leave it blank if you want to create an ongoing task.
                            </small>
                            @error('deadline')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="title" class="form-label">{{ __('Title') }} <strong class="text-danger">*</strong></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ __('Title') }}" class="form-control @error('title') is-invalid @enderror" required/>
                            @error('title')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="users" class="form-label">Select Users <strong class="text-danger">*</strong></label>
                            <select name="users[]" id="users" class="select2 form-select @error('users') is-invalid @enderror" data-allow-clear="true" multiple required>
                                <option value="selectAllValues">Select All</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <small class="text-muted" id="usersNote" style="display: none;">
                                <span class="text-dark text-bold">Note:</span> <span id="usersNoteText"></span>
                            </small>
                            @error('users')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="priority" class="form-label">Select Priority <strong class="text-danger">*</strong></label>
                            <div class="row">
                                <div class="col-md mb-md-0 mb-2">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityLow">
                                            <input name="priority" class="form-check-input" type="radio" value="Low" id="priorityLow" checked />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Low</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityAverage">
                                            <input name="priority" class="form-check-input" type="radio" value="Average" id="priorityAverage" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Average</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityMedium">
                                            <input name="priority" class="form-check-input" type="radio" value="Medium" id="priorityMedium" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">Medium</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-basic">
                                        <label class="form-check-label custom-option-content" for="priorityHigh">
                                            <input name="priority" class="form-check-input" type="radio" value="High" id="priorityHigh" />
                                            <span class="custom-option-header pb-0">
                                                <span class="h6 mb-0">High</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Task Description <strong class="text-danger">*</strong></label>
                            <div name="description" id="full-editor">{!! old('description') !!}</div>
                            <textarea class="d-none" name="description" id="description-input">{{ old('description') }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="files[]" class="form-label">{{ __('Task Files') }}</label>
                            
                            <div id="filePreviewContainer" style="display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 15px;"></div>
                            
                            <div id="fileDropZone" class="file-drop-zone" style="border: 2px dashed #ccc; padding: 20px; text-align: center; margin-top: 10px; cursor: pointer; transition: all 0.3s ease;">
                                <div style="margin-bottom: 10px;">
                                    <i class="ti ti-cloud-upload" style="font-size: 2rem; color: #7367f0;"></i>
                                </div>
                                <div style="font-weight: 500; margin-bottom: 5px;">Drag & Drop or Paste Files Here</div>
                                <small style="color: #999;">Or click to browse</small>
                                <br>
                                <span id="fileStatus" style="font-weight: bold; color: green; display: block; margin-top: 10px;"></span>
                            </div>

                            <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Files') }}" class="form-control @error('files[]') is-invalid @enderror" style="display: none;" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.task.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Create Task</button>
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

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });

            // Handle parent task selection
            let parentTaskUsers = [];
            let isParentTaskSelected = false;

            $('#parent_task_id').on('change', function() {
                const parentTaskId = $(this).val();
                const usersSelect = $('#users');
                const usersNote = $('#usersNote');
                const usersNoteText = $('#usersNoteText');

                if (parentTaskId) {
                    // Fetch parent task users
                    $.ajax({
                        url: '{{ route("administration.task.fetch.parent.users", ":id") }}'.replace(':id', parentTaskId),
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            if (response.success && response.users.length > 0) {
                                parentTaskUsers = response.users.map(user => user.id.toString());

                                // Select parent task users
                                usersSelect.val(parentTaskUsers).trigger('change');

                                // Disable the select field
                                usersSelect.prop('disabled', true);

                                // Remove any previous hidden clones
                                $('#taskForm').find('input[name="users[]"].hidden-clone').remove();

                                // Dynamically create hidden inputs for submission
                                parentTaskUsers.forEach(id => {
                                    $('<input>')
                                        .attr('type', 'hidden')
                                        .attr('name', 'users[]')
                                        .attr('value', id)
                                        .addClass('hidden-clone')
                                        .appendTo('#taskForm');
                                });

                                // Show note
                                isParentTaskSelected = true;
                                usersNoteText.text('Users are automatically selected from the parent task and cannot be changed.');
                                usersNote.show();
                            } else {
                                // No users in parent task
                                resetUsersField();
                            }
                        },
                        error: function() {
                            resetUsersField();
                        }
                    });
                } else {
                    // No parent task selected
                    resetUsersField();
                }

                // Helper function to reset users field
                function resetUsersField() {
                    isParentTaskSelected = false;

                    // Enable select
                    usersSelect.prop('disabled', false);

                    // Clear select values
                    usersSelect.val(null).trigger('change');

                    // Remove hidden clones
                    $('#taskForm').find('input[name="users[]"].hidden-clone').remove();

                    // Hide note
                    usersNote.hide();
                }
            });

            // Trigger change on page load if parent task is already selected
            if ($('#parent_task_id').val()) {
                $('#parent_task_id').trigger('change');
            }
        });
    </script>

    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var fullEditor = new Quill("#full-editor", {
                bounds: "#full-editor",
                placeholder: "Ex: Mr. John Doe got promoted as Manager",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old description if validation fails
            @if(old('description'))
                fullEditor.root.innerHTML = {!! json_encode(old('description')) !!};
            @endif

            $('#taskForm').on('submit', function() {
                $('#description-input').val(fullEditor.root.innerHTML);
            });
        });
    </script>

    <script>
        const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB limit
        const initializedDropZones = new Set(); // Track initialized drop zones

        // Helper function to generate image previews
        function generateImagePreviews(previewContainer, files, fileInput) {
            previewContainer.innerHTML = '';
            
            if (!files || files.length === 0) {
                return;
            }

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const fileIndex = i;
                
                // Check if file is an image
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const previewDiv = document.createElement('div');
                        previewDiv.style.cssText = 'position: relative; display: inline-block; margin: 5px;';
                        
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-radius: 6px; border: 2px solid #ddd; box-shadow: 0 2px 4px rgba(0,0,0,0.1);';
                        img.title = file.name;
                        
                        const removeBtn = document.createElement('button');
                        removeBtn.type = 'button';
                        removeBtn.innerHTML = '<i class="ti ti-x" style="font-size: 1rem;"></i>';
                        removeBtn.style.cssText = 'position: absolute; top: -10px; right: -10px; background: #dc3545; color: white; border: none; border-radius: 50%; width: 28px; height: 28px; padding: 0; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 14px; box-shadow: 0 2px 4px rgba(0,0,0,0.2); transition: all 0.2s ease;';
                        removeBtn.title = 'Remove image';
                        
                        removeBtn.addEventListener('mouseover', () => {
                            removeBtn.style.background = '#c82333';
                            removeBtn.style.transform = 'scale(1.1)';
                        });
                        
                        removeBtn.addEventListener('mouseout', () => {
                            removeBtn.style.background = '#dc3545';
                            removeBtn.style.transform = 'scale(1)';
                        });
                        
                        removeBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            // Remove file from input
                            const dataTransfer = new DataTransfer();
                            for (let j = 0; j < fileInput.files.length; j++) {
                                if (j !== fileIndex) {
                                    dataTransfer.items.add(fileInput.files[j]);
                                }
                            }
                            fileInput.files = dataTransfer.files;
                            
                            // Regenerate previews
                            const statusElement = document.getElementById('fileStatus');
                            updateFileStatus(statusElement, fileInput.files);
                            generateImagePreviews(previewContainer, fileInput.files, fileInput);
                        });
                        
                        previewDiv.appendChild(img);
                        previewDiv.appendChild(removeBtn);
                        previewContainer.appendChild(previewDiv);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }

        // Helper function to update file status display
        function updateFileStatus(statusElement, files) {
            if (!files || files.length === 0) {
                statusElement.innerHTML = '';
                return;
            }

            let html = `<i class="ti ti-check-circle" style="color: green;"></i> ${files.length} file(s) selected:<br>`;
            for (let i = 0; i < files.length; i++) {
                const size = (files[i].size / 1024 / 1024).toFixed(2);
                html += `<small>${files[i].name} (${size} MB)</small><br>`;
            }
            statusElement.innerHTML = html;
        }

        // Helper function to validate file
        function validateFile(file) {
            if (file.size > MAX_FILE_SIZE) {
                alert(`File "${file.name}" is too large (max 5MB).`);
                return false;
            }
            return true;
        }

        // Helper function to add files to file input
        function addFilesToInput(fileInput, newFiles) {
            const dataTransfer = new DataTransfer();
            
            // Add existing files first
            for (let i = 0; i < fileInput.files.length; i++) {
                dataTransfer.items.add(fileInput.files[i]);
            }
            
            // Add new files
            for (let i = 0; i < newFiles.length; i++) {
                if (validateFile(newFiles[i])) {
                    dataTransfer.items.add(newFiles[i]);
                }
            }
            
            fileInput.files = dataTransfer.files;
        }

        // Setup file drop zone
        function setupFileDropZone(dropZone, fileInput, statusElement, previewContainer) {
            // Check if already initialized
            if (initializedDropZones.has(dropZone)) {
                return;
            }
            initializedDropZones.add(dropZone);

            // Click to browse
            const clickHandler = () => {
                fileInput.click();
            };
            dropZone.addEventListener('click', clickHandler);

            // File input change
            const changeHandler = () => {
                updateFileStatus(statusElement, fileInput.files);
                if (previewContainer) {
                    generateImagePreviews(previewContainer, fileInput.files, fileInput);
                }
            };
            fileInput.addEventListener('change', changeHandler);

            // Drag over
            const dragoverHandler = (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.backgroundColor = '#e8e4f3';
                dropZone.style.borderColor = '#7367f0';
            };
            dropZone.addEventListener('dragover', dragoverHandler);

            // Drag leave
            const dragleaveHandler = (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.backgroundColor = 'transparent';
                dropZone.style.borderColor = '#ccc';
            };
            dropZone.addEventListener('dragleave', dragleaveHandler);

            // Drop
            const dropHandler = (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropZone.style.backgroundColor = 'transparent';
                dropZone.style.borderColor = '#ccc';
                
                if (e.dataTransfer.files.length) {
                    addFilesToInput(fileInput, e.dataTransfer.files);
                    updateFileStatus(statusElement, fileInput.files);
                    if (previewContainer) {
                        generateImagePreviews(previewContainer, fileInput.files, fileInput);
                    }
                }
            };
            dropZone.addEventListener('drop', dropHandler);
        }

        // Setup paste functionality for a specific form
        function setupPasteForForm(form) {
            const pasteHandler = (e) => {
                const items = (e.clipboardData || e.originalEvent.clipboardData).items;
                const files = [];
                
                for (let i = 0; i < items.length; i++) {
                    if (items[i].kind === 'file') {
                        const file = items[i].getAsFile();
                        if (file) {
                            files.push(file);
                        }
                    }
                }
                
                if (files.length > 0) {
                    e.preventDefault();
                    const fileInput = form.querySelector('input[type="file"]');
                    const statusElement = document.getElementById('fileStatus');
                    const previewContainer = document.getElementById('filePreviewContainer');
                    if (fileInput) {
                        addFilesToInput(fileInput, files);
                        updateFileStatus(statusElement, fileInput.files);
                        if (previewContainer) {
                            generateImagePreviews(previewContainer, fileInput.files, fileInput);
                        }
                    }
                }
            };
            form.addEventListener('paste', pasteHandler);
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Setup main task form
            const mainDropZone = document.getElementById('fileDropZone');
            const mainFileInput = document.getElementById('files[]');
            const mainStatusElement = document.getElementById('fileStatus');
            const mainPreviewContainer = document.getElementById('filePreviewContainer');
            
            if (mainDropZone && mainFileInput) {
                setupFileDropZone(mainDropZone, mainFileInput, mainStatusElement, mainPreviewContainer);
                setupPasteForForm(document.getElementById('taskForm'));
            }
        });
    </script>
@endsection
