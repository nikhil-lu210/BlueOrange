@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Edit Inventory'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS */
        .file-preview {
            max-width: 150px;
            max-height: 100px;
            object-fit: cover;
            border-radius: 4px;
        }
        .file-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            margin-bottom: 8px;
        }
        .file-info {
            flex: 1;
        }
        .file-name {
            font-weight: 500;
            margin-bottom: 2px;
        }
        .file-size {
            font-size: 12px;
            color: #6c757d;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Edit Inventory') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Inventory') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.inventory.index') }}">{{ __('All Inventories') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.inventory.show', ['inventory' => $inventory]) }}">{{ __('Inventory Details') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit Inventory') }}</li>
@endsection

@section('content')

<form id="inventoryEditForm" action="{{ route('administration.inventory.update', ['inventory' => $inventory]) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
    @csrf
    @method('PUT')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('Edit Inventory') }}: {{ $inventory->name }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Inventory Name <strong class="text-danger">*</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $inventory->name) }}" placeholder="Ex: Samsung 22 Inch Monitor" class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" min="0" step="0.01" id="price" name="price" value="{{ old('price', $inventory->price) }}" placeholder="Ex: 12500" class="form-control @error('price') is-invalid @enderror" />
                            @error('price')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">{{ __('Category') }} <strong class="text-danger">*</strong></label>
                            <select name="category_id" id="category_id" class="form-select select2-tags @error('category_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new Category" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $inventory->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can type to add a new Category if not found in the list</small>
                            @error('category_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="usage_for" class="form-label">{{ __('Usage Purpose') }} <strong class="text-danger">*</strong></label>
                            <select name="usage_for" id="usage_for" class="form-select select2-tags @error('usage_for') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new Purpose" required>
                                <option value="">{{ __('Select Usage Purpose') }}</option>
                                @foreach ($purposes as $purpose)
                                    <option value="{{ $purpose }}" {{ old('usage_for', $inventory->usage_for) == $purpose ? 'selected' : '' }}>
                                        {{ $purpose }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can type to add a new Purpose if not found in the list</small>
                            @error('usage_for')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-7 mb-3">
                            <label for="unique_number" class="form-label">Unique Number</label>
                            <input type="text" id="unique_number" name="unique_number" value="{{ old('unique_number', $inventory->unique_number) }}" placeholder="Ex: SMMONITOR001" class="form-control @error('unique_number') is-invalid @enderror" />
                            @error('unique_number')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-5 mb-3">
                            <label for="status" class="form-label">Status <strong class="text-danger">*</strong></label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror" data-style="btn-default" required>
                                <option value="" {{ is_null(old('status', $inventory->status)) ? 'selected' : '' }}>Select Status</option>
                                <option value="Available" {{ old('status', $inventory->status) == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="In Use" {{ old('status', $inventory->status) == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="Out of Service" {{ old('status', $inventory->status) == 'Out of Service' ? 'selected' : '' }}>Out of Service</option>
                                <option value="Damaged" {{ old('status', $inventory->status) == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Ex: This is a description for the inventory item">{{ old('description', $inventory->description) }}</textarea>
                            @error('description')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <!-- Existing Files -->
                        @if($inventory->files->count() > 0)
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Existing Files</label>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Preview</th>
                                            <th>File Name</th>
                                            <th>Size</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventory->files as $file)
                                            <tr>
                                                <td class="align-middle">
                                                    @if(in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                        <img src="{{ file_media_download($file) }}" alt="{{ $file->original_name }}" class="img-thumbnail" style="max-width: 80px;">
                                                    @else
                                                        <i class="ti ti-file-download fs-3 text-primary"></i>
                                                    @endif
                                                </td>
                                                <td class="align-middle">{{ $file->original_name }}</td>
                                                <td class="align-middle">{{ get_file_media_size($file) }}</td>
                                                <td class="align-middle">
                                                    <a href="{{ file_media_destroy($file) }}" class="btn btn-sm btn-danger confirm-danger" title="Delete File">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- New Files -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Add New Files</label>

                            <div id="editFilePreviewContainer" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:10px;"></div>
                            <div id="editFileDropZone" class="file-drop-zone" style="border:2px dashed #ccc;padding:16px;text-align:center;cursor:pointer;transition:all .3s ease;">
                                <div style="margin-bottom:8px;"><i class="ti ti-cloud-upload" style="font-size:1.6rem;color:#7367f0;"></i></div>
                                <div style="font-weight:500;margin-bottom:4px;">Drag & Drop or Paste Files Here</div>
                                <small style="color:#999;">Or click to browse</small>
                                <br>
                                <span id="editFileStatus" style="font-weight:bold;color:green;display:block;margin-top:8px;"></span>
                            </div>

                            <input type="file" accept="image/*" id="edit_files_input" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Inventory Files') }}" class="form-control @error('files.*') is-invalid @enderror" style="display:none;" multiple/>
                            <small class="text-muted">Upload <b>Image Files</b> only. You can select multiple files.</small>
                            @error('files.*')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-end gap-2">
                            <a href="{{ route('administration.inventory.show', ['inventory' => $inventory]) }}" class="btn btn-secondary">
                                <i class="ti ti-arrow-left me-1"></i>
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-check me-1"></i>
                                {{ __('Update Inventory') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB
        const initializedDropZones = new Set();

        function generateImagePreviews(previewContainer, files, fileInput) {
            previewContainer.innerHTML = '';
            if (!files || files.length === 0) return;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                const idx = i;
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = e => {
                        const wrap = document.createElement('div');
                        wrap.style.cssText = 'position:relative;display:inline-block;margin:5px;';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.cssText = 'width:100px;height:100px;object-fit:cover;border-radius:6px;border:2px solid #ddd;box-shadow:0 2px 4px rgba(0,0,0,0.1)';
                        const btn = document.createElement('button');
                        btn.type = 'button';
                        btn.innerHTML = '<i class="ti ti-x" style="font-size:1rem;"></i>';
                        btn.style.cssText = 'position:absolute;top:-10px;right:-10px;background:#dc3545;color:#fff;border:none;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;cursor:pointer;';
                        btn.addEventListener('click', (ev) => {
                            ev.preventDefault(); ev.stopPropagation();
                            const dt = new DataTransfer();
                            for (let j = 0; j < fileInput.files.length; j++) if (j !== idx) dt.items.add(fileInput.files[j]);
                            fileInput.files = dt.files;
                            updateFileStatus(document.getElementById('editFileStatus'), fileInput.files);
                            generateImagePreviews(previewContainer, fileInput.files, fileInput);
                        });
                        wrap.appendChild(img); wrap.appendChild(btn); previewContainer.appendChild(wrap);
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
        function updateFileStatus(statusElement, files) {
            if (!files || files.length === 0) { statusElement.innerHTML = ''; return; }
            let html = `<i class="ti ti-check-circle" style="color:green;"></i> ${files.length} file(s) selected:<br>`;
            for (let i = 0; i < files.length; i++) html += `<small>${files[i].name} (${(files[i].size/1024/1024).toFixed(2)} MB)</small><br>`;
            statusElement.innerHTML = html;
        }
        function validateFile(file) { if (file.size > MAX_FILE_SIZE) { alert(`File "${file.name}" is too large (max 5MB).`); return false; } return true; }
        function addFilesToInput(fileInput, newFiles) {
            const dt = new DataTransfer();
            for (let i = 0; i < fileInput.files.length; i++) dt.items.add(fileInput.files[i]);
            for (let i = 0; i < newFiles.length; i++) if (validateFile(newFiles[i])) dt.items.add(newFiles[i]);
            fileInput.files = dt.files;
        }
        function setupFileDropZone(dropZone, fileInput, statusElement, previewContainer) {
            if (initializedDropZones.has(dropZone)) return; initializedDropZones.add(dropZone);
            dropZone.addEventListener('click', () => fileInput.click());
            fileInput.addEventListener('change', () => { updateFileStatus(statusElement, fileInput.files); generateImagePreviews(previewContainer, fileInput.files, fileInput); });
            dropZone.addEventListener('dragover', e => { e.preventDefault(); e.stopPropagation(); dropZone.style.backgroundColor = '#e8e4f3'; dropZone.style.borderColor = '#7367f0'; });
            dropZone.addEventListener('dragleave', e => { e.preventDefault(); e.stopPropagation(); dropZone.style.backgroundColor = 'transparent'; dropZone.style.borderColor = '#ccc'; });
            dropZone.addEventListener('drop', e => {
                e.preventDefault(); e.stopPropagation(); dropZone.style.backgroundColor = 'transparent'; dropZone.style.borderColor = '#ccc';
                if (e.dataTransfer.files.length) { addFilesToInput(fileInput, e.dataTransfer.files); updateFileStatus(statusElement, fileInput.files); generateImagePreviews(previewContainer, fileInput.files, fileInput); }
            });
        }
        function setupPasteForForm(form) {
            form.addEventListener('paste', e => {
                const items = (e.clipboardData || e.originalEvent.clipboardData).items; const files = [];
                for (let i = 0; i < items.length; i++) if (items[i].kind === 'file') { const f = items[i].getAsFile(); if (f) files.push(f); }
                if (files.length > 0) {
                    e.preventDefault();
                    // Always target the edit inputs on this page
                    const input = document.getElementById('edit_files_input');
                    const status = document.getElementById('editFileStatus');
                    const preview = document.getElementById('editFilePreviewContainer');
                    if (input) {
                        addFilesToInput(input, files);
                        if (status) updateFileStatus(status, input.files);
                        if (preview) generateImagePreviews(preview, input.files, input);
                    }
                }
            });
        }

        $(document).ready(function() {
            // Initialize Bootstrap Select
            $('.bootstrap-select').each(function() { if (!$(this).data('bs.select')) { $(this).selectpicker(); } });

            // Initialize Select2 with tagging for categories
            $('#category_id').select2({
                tags: true,
                tokenSeparators: [],
                createTag: function (params) { var term = $.trim(params.term); if (term === '') return null; return { id: 'new:' + term, text: term, newTag: true }; },
                templateResult: function (data) { var $r = $('<span></span>'); $r.text(data.text); if (data.newTag) { $r.append(' <em>(New Category will be created)</em>'); } return $r; },
                insertTag: function (data, tag) { data.push(tag); }
            });

            // Initialize Select2 with tagging for purposes
            $('#usage_for').select2({
                tags: true,
                tokenSeparators: [],
                createTag: function (params) { var term = $.trim(params.term); if (term === '') return null; return { id: 'new:' + term, text: term, newTag: true }; },
                templateResult: function (data) { var $r = $('<span></span>'); $r.text(data.text); if (data.newTag) { $r.append(' <em>(New Purpose will be created)</em>'); } return $r; },
                insertTag: function (data, tag) { data.push(tag); }
            });

            // Setup dropzone + paste for edit form
            const dz = document.getElementById('editFileDropZone');
            const input = document.getElementById('edit_files_input');
            const status = document.getElementById('editFileStatus');
            const preview = document.getElementById('editFilePreviewContainer');
            if (dz && input) {
                setupFileDropZone(dz, input, status, preview);
            }
            // Attach paste to the specific edit form to avoid conflicts
            const editForm = document.getElementById('inventoryEditForm');
            if (editForm) setupPasteForForm(editForm);
        });
    </script>
@endsection
