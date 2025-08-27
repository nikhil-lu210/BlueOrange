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
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 15px;
        }

        .inventory-section h6 {
            font-weight: 600;
            margin-bottom: 15px;
            color: #7367f0;
        }

        .common-fields {
            transition: all 0.3s ease;
        }

        .common-fields.hidden {
            display: none;
        }

        .inventory-item {
            border: 1px solid #e0e0e0;
            border-left: 3px solid #7367f0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .inventory-item-header {
            margin-bottom: 15px;
        }

        .form-check-input:checked {
            background-color: #7367f0;
            border-color: #7367f0;
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
                            <label for="name" class="form-label">Inventory Name <strong class="text-danger">*</strong></label>
                            <input type="text" id="name" name="name" value="{{ old('name', request()->name) }}" placeholder="Ex: Samsung 22 Inch Monitor" class="form-control @error('name') is-invalid @enderror" />
                            @error('name')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="quantity" class="form-label">{{ __('Select Quantity') }} <strong class="text-danger">*</strong></label>
                            <select name="quantity" id="quantity" class="form-select bootstrap-select w-100 @error('quantity') is-invalid @enderror"  data-style="btn-default" required>
                                <option value="" {{ is_null(old('quantity', request()->quantity)) ? 'selected' : '' }}>Select Quantity</option>
                                <option value="1" {{ old('quantity', request()->quantity) == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ old('quantity', request()->quantity) == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ old('quantity', request()->quantity) == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ old('quantity', request()->quantity) == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ old('quantity', request()->quantity) == '5' ? 'selected' : '' }}>5</option>
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

                    <!-- Common Fields Section -->
                    <div class="row mb-3 common-fields" id="commonFieldsSection">
                        <div class="col-12">
                            <h6 class="text-primary mb-3">
                                <i class="ti ti-settings me-1"></i>
                                Common Settings
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" value="1" id="common_files" name="common_files" {{ old('common_files', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="common_files">Common File(s)</label>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="form-check form-check-primary">
                                    <input class="form-check-input" type="checkbox" value="1" id="common_description" name="common_description" {{ old('common_description', true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="common_description">Common Description</label>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3 col-md-12" id="commonFilesSection"> {{-- This Will be hidden if common files is unchecked --}}
                            <label for="common_files_input" class="form-label">{{ __('Common Inventory Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="common_files_input" name="common_files[]" value="{{ old('common_files[]') }}" placeholder="{{ __('Inventory Files') }}" class="form-control @error('common_files[]') is-invalid @enderror" multiple/>
                            <small class="text-muted">These files will be applied to all inventory items</small>
                            @error('common_files[]')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12" id="commonDescriptionSection"> {{-- This Will be hidden if common description is unchecked --}}
                            <label for="common_description_input" class="form-label">{{ __('Common Description') }}</label>
                            <textarea name="common_description_input" id="common_description_input" class="form-control @error('common_description_input') is-invalid @enderror" rows="3" placeholder="Ex: This is a common description for all inventory items">{{ old('common_description_input', request()->common_description_input) }}</textarea>
                            <small class="text-muted">This description will be applied to all inventory items</small>
                            @error('common_description_input')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <!-- Dynamic Inventory Items Section -->
                    <div id="inventoryItemsContainer">
                        <!-- Inventory items will be generated here dynamically -->
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

            // Handle quantity change
            $('#quantity').on('change', function() {
                generateInventoryItems();
            });

            // Handle common files checkbox
            $('#common_files').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#commonFilesSection').show();
                } else {
                    $('#commonFilesSection').hide();
                }
                updateInventoryItems();
            });

            // Handle common description checkbox
            $('#common_description').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#commonDescriptionSection').show();
                } else {
                    $('#commonDescriptionSection').hide();
                }
                updateInventoryItems();
            });

            // Generate inventory items based on quantity
            function generateInventoryItems() {
                var quantity = parseInt($('#quantity').val()) || 0;
                var container = $('#inventoryItemsContainer');
                container.empty();

                if (quantity > 0) {
                    for (var i = 1; i <= quantity; i++) {
                        var itemHtml = createInventoryItemHtml(i);
                        container.append(itemHtml);
                    }
                    updateInventoryItems();
                }
            }

            // Create HTML for a single inventory item
            function createInventoryItemHtml(itemNumber) {
                var itemHtml = `
                    <div class="inventory-item" data-item="${itemNumber}">
                        <div class="inventory-item-header">
                            <h6 class="mb-0">
                                <i class="ti ti-hash me-1"></i>
                                Inventory ${String(itemNumber).padStart(2, '0')}
                            </h6>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="unique_number_${itemNumber}" class="form-label">Unique Number <strong class="text-danger">*</strong></label>
                                <input type="text" id="unique_number_${itemNumber}" name="items[${itemNumber}][unique_number]" placeholder="Ex: SMMONITOR${String(itemNumber).padStart(4, '0')}" class="form-control" required/>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="price_${itemNumber}" class="form-label">Price <strong class="text-danger">*</strong></label>
                                <input type="number" min="0" step="0.01" id="price_${itemNumber}" name="items[${itemNumber}][price]" placeholder="Ex: 12500" class="form-control" required/>
                            </div>
                            <div class="col-md-12 mb-3 individual-files-section" style="display: none;">
                                <label for="files_${itemNumber}" class="form-label">{{ __('Individual Inventory Files') }}</label>
                                <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files_${itemNumber}" name="items[${itemNumber}][files][]" class="form-control" multiple/>
                                <small class="text-muted">Individual files for this inventory item</small>
                            </div>
                            <div class="col-md-12 mb-3 individual-description-section" style="display: none;">
                                <label for="description_${itemNumber}" class="form-label">{{ __('Individual Description') }}</label>
                                <textarea name="items[${itemNumber}][description]" id="description_${itemNumber}" class="form-control" rows="3" placeholder="Ex: This is a description specific to this inventory item"></textarea>
                                <small class="text-muted">Individual description for this inventory item</small>
                            </div>
                        </div>
                    </div>
                `;
                return itemHtml;
            }

            // Update inventory items based on common settings
            function updateInventoryItems() {
                var commonFiles = $('#common_files').is(':checked');
                var commonDescription = $('#common_description').is(':checked');

                $('.individual-files-section').each(function() {
                    if (commonFiles) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });

                $('.individual-description-section').each(function() {
                    if (commonDescription) {
                        $(this).hide();
                    } else {
                        $(this).show();
                    }
                });
            }

            // Initialize on page load
            generateInventoryItems();

            // Restore old input values if validation failed
            restoreOldInputValues();
        });

        // Function to restore old input values
        function restoreOldInputValues() {
            // Restore category selection
            @if(old('category_id'))
                $('#category_id').val('{{ old('category_id') }}').trigger('change');
            @endif

            // Restore usage purpose selection
            @if(old('usage_for'))
                $('#usage_for').val('{{ old('usage_for') }}').trigger('change');
            @endif

            // Restore inventory items with old values
            @if(old('items'))
                var oldItems = @json(old('items'));
                restoreInventoryItems(oldItems);
            @endif
        }

        // Function to restore inventory items with old values
        function restoreInventoryItems(oldItems) {
            var container = $('#inventoryItemsContainer');
            container.empty();

            if (oldItems && Object.keys(oldItems).length > 0) {
                Object.keys(oldItems).forEach(function(itemIndex) {
                    var itemData = oldItems[itemIndex];
                    var itemHtml = createInventoryItemHtmlWithValues(parseInt(itemIndex), itemData);
                    container.append(itemHtml);
                });
                updateInventoryItems();
            }
        }

        // Create HTML for inventory item with old values
        function createInventoryItemHtmlWithValues(itemNumber, itemData) {
            var uniqueNumberValue = itemData.unique_number || '';
            var priceValue = itemData.price || '';
            var descriptionValue = itemData.description || '';

            var itemHtml = `
                <div class="inventory-item" data-item="${itemNumber}">
                    <div class="inventory-item-header">
                        <h6 class="mb-0">
                            <i class="ti ti-hash me-1"></i>
                            Inventory ${String(itemNumber).padStart(2, '0')}
                        </h6>
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="unique_number_${itemNumber}" class="form-label">Unique Number</label>
                            <input type="text" id="unique_number_${itemNumber}" name="items[${itemNumber}][unique_number]" value="${uniqueNumberValue}" placeholder="Ex: SMMONITOR${String(itemNumber).padStart(4, '0')}" class="form-control" />
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="price_${itemNumber}" class="form-label">Price</label>
                            <input type="number" min="0" step="0.01" id="price_${itemNumber}" name="items[${itemNumber}][price]" value="${priceValue}" placeholder="Ex: 12500" class="form-control" />
                        </div>
                        <div class="col-md-12 mb-3 individual-files-section" style="display: none;">
                            <label for="files_${itemNumber}" class="form-label">{{ __('Individual Inventory Files') }}</label>
                            <input type="file" accept=".jpg,.jpeg,.png,.pdf,.doc,.docx" id="files_${itemNumber}" name="items[${itemNumber}][files][]" class="form-control" multiple/>
                            <small class="text-muted">Individual files for this inventory item</small>
                        </div>
                        <div class="col-md-12 mb-3 individual-description-section" style="display: none;">
                            <label for="description_${itemNumber}" class="form-label">{{ __('Individual Description') }}</label>
                            <textarea name="items[${itemNumber}][description]" id="description_${itemNumber}" class="form-control" rows="3" placeholder="Ex: This is a description specific to this inventory item">${descriptionValue}</textarea>
                            <small class="text-muted">Individual description for this inventory item</small>
                        </div>
                    </div>
                </div>
            `;
            return itemHtml;
        }
    </script>
@endsection
