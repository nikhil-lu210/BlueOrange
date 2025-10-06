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

<form action="{{ route('administration.inventory.update', ['inventory' => $inventory]) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
                            <label for="files" class="form-label">Add New Files</label>
                            <input type="file" accept="image/*" id="files" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Inventory Files') }}" class="form-control @error('files.*') is-invalid @enderror" multiple/>
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
        $(document).ready(function() {
            // Initialize Bootstrap Select
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) {
                    $(this).selectpicker();
                }
            });

            // Initialize Select2 with tagging for categories
            $('#category_id').select2({
                tags: true,
                tokenSeparators: [],
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: 'new:' + term,
                        text: term,
                        newTag: true
                    };
                },
                templateResult: function (data) {
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    if (data.newTag) {
                        $result.append(' <em>(New Category will be created)</em>');
                    }
                    return $result;
                },
                insertTag: function (data, tag) {
                    data.push(tag);
                }
            });

            // Initialize Select2 with tagging for purposes
            $('#usage_for').select2({
                tags: true,
                tokenSeparators: [],
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') {
                        return null;
                    }
                    return {
                        id: 'new:' + term,
                        text: term,
                        newTag: true
                    };
                },
                templateResult: function (data) {
                    var $result = $('<span></span>');
                    $result.text(data.text);
                    if (data.newTag) {
                        $result.append(' <em>(New Purpose will be created)</em>');
                    }
                    return $result;
                },
                insertTag: function (data, tag) {
                    data.push(tag);
                }
            });
        });
    </script>
@endsection
