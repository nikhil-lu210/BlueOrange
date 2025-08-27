@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Store Inventory'))

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
        .inventory-section {
            border-left: 3px solid #7367f0;
            padding-left: 15px;
            margin-bottom: 20px;
        }

        .inventory-section h6 {
            font-weight: 600;
            margin-bottom: 15px;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Store Inventory') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.inventory.index') }}">{{ __('All Inventories') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Store Inventory') }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Store New Inventory') }}</h5>
            </div>
            <div class="card-body inventory-form">
                <form action="{{ route('administration.inventory.store') }}" method="POST" enctype="multipart/form-data" id="inventoryForm" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="col-md-9 mb-3">
                            <label for="name" class="form-label">inventory Name <strong class="text-danger">*</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name', request()->name) }}" placeholder="Ex: Samsung 22 Inch Monitor" class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="quantity" class="form-label">{{ __('Select Quantity') }} <strong class="text-danger">*</strong></label>
                            <select name="quantity" id="quantity" class="form-select bootstrap-select w-100 @error('quantity') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" {{ is_null(request()->quantity) ? 'selected' : '' }}>Select Quantity</option>
                                <option value="1" {{ request()->quantity == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request()->quantity == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request()->quantity == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request()->quantity == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ request()->quantity == '5' ? 'selected' : '' }}>5</option>
                            </select>
                            @error('quantity')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="category_id" class="form-label">{{ __('Category') }} <strong class="text-danger">*</strong></label>
                            <select name="category_id" id="category_id" class="form-select select2-tags @error('category_id') is-invalid @enderror" data-allow-clear="true" data-tags="true" data-placeholder="Select or type to add new Category" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="{{ $purpose }}" {{ old('usage_for') == $purpose ? 'selected' : '' }}>
                                        {{ $purpose }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">You can type to add a new Purpose if not found in the list</small>
                            @error('usage_for')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" value="" id="common_files" checked>
                                    <label class="form-check-label" for="common_files">Common File(s)</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" value="" id="common_description" checked>
                                    <label class="form-check-label" for="common_description">Common Description</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12"> {{-- This Will be hidden if common files is unchecked --}}
                            <label for="files[]" class="form-label">{{ __('Inventory Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Inventory Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12"> {{-- This Will be hidden if common description is unchecked --}}
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Ex: This is a description of the inventory">{{ old('description', request()->description) }}</textarea>
                            @error('description')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3 inventory-section">  {{-- This will be visible depends on the quantity --}}
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-hash me-1"></i>
                                Inventory 01
                            </h6>
                        </div>
                        <div class="mb-3 col-md-8">
                            <label for="unique_number" class="form-label">Unique Number</label>
                            <input type="text" id="unique_number" name="unique_number" value="{{ old('unique_number', request()->unique_number) }}" placeholder="Ex: SMMONITOR0001" class="form-control @error('unique_number') is-invalid @enderror" />
                            @error('unique_number')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="price" class="form-label">Price</label>
                            <input type="number" min="0" step="0.01" id="price" name="price" value="{{ old('price', request()->price) }}" placeholder="Ex: 12500" class="form-control @error('price') is-invalid @enderror" />
                            @error('price')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12"> {{-- This Will be hidden if common files is checked --}}
                            <label for="files[]" class="form-label">{{ __('Inventory Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Inventory Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                            @error('files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12"> {{-- This Will be hidden if common description is checked --}}
                            <label for="description" class="form-label">{{ __('Description') }}</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" rows="3" placeholder="Ex: This is a description of the inventory">{{ old('description', request()->description) }}</textarea>
                            @error('description')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-upload me-1"></i>{{ __('Store Inventory') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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
        // Select2 with tagging functionality for categories
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            // Initialize Select2 with tagging for categories
            $('#category_id').select2({
                tags: true,
                tokenSeparators: [], // Remove space and comma separators
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
                    // Only insert if user explicitly selects the tag
                    data.push(tag);
                }
            });

            // Initialize Select2 with tagging for purposes
            $('#usage_for').select2({
                tags: true,
                tokenSeparators: [], // Remove space and comma separators
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
                    // Only insert if user explicitly selects the tag
                    data.push(tag);
                }
            });
        });
    </script>
@endsection
