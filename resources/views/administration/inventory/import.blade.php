@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Import Inventories'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Import Inventories') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Inventory') }}</li>
    <li class="breadcrumb-item active">{{ __('Import Inventories') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('Import Inventories') }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.inventory.create') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        {{ __('Create Inventory') }}
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <h6 class="alert-heading"><i class="ti ti-info-circle me-2"></i>{{ __('Import Instructions') }}</h6>
                    <ul class="mb-0">
                        <li>{{ __('Upload a CSV file with the following columns:') }}</li>
                        <li><strong>category</strong> - {{ __('Product category (will be created if not exists)') }}</li>
                        <li><strong>name</strong> - {{ __('Product name (required)') }}</li>
                        <li><strong>unique_number</strong> - {{ __('Unique identifier (optional)') }}</li>
                        <li><strong>price</strong> - {{ __('Product price (optional, default: 0)') }}</li>
                        <li><strong>description</strong> - {{ __('Product description (optional)') }}</li>
                        <li><strong>usage_for</strong> - {{ __('Usage location/purpose (optional)') }}</li>
                        <li><strong>status</strong> - {{ __('Item status: Available, In Use, Out of Service, Damaged (optional, default: Available)') }}</li>
                        <li><strong>quantity</strong> - {{ __('Number of items to create (optional, default: 1)') }}</li>
                    </ul>
                </div>

                <form action="{{ route('administration.inventory.import.upload') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="import_file" class="form-label">
                                {{ __('Inventories File') }} <sup class="text-dark text-bold">(.csv file only)</sup> <strong class="text-danger">*</strong>
                            </label>
                            <input type="file" id="import_file" name="import_file" value="{{ old('import_file') }}" placeholder="{{ __('Files') }}" class="form-control @error('import_file') is-invalid @enderror" accept=".csv" required/>
                            <small>
                                <span class="text-dark text-bold">{{ __('Note:') }}</span>
                                <span>{{ __('Please select') }} <b class="text-bold text-info">.csv</b> {{ __('file only.') }}</span>
                            </small>
                            <b class="float-end">
                                <a href="{{ asset('import_templates_sample/inventories_import_sample.csv') }}" class="text-primary text-bold">
                                    <span class="tf-icon ti ti-download"></span>
                                    {{ __('Download Formatted Template') }}
                                </a>
                            </b>
                            <br>
                            @error('import_file')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="ti ti-alert-triangle me-2"></i>{{ __('Important Notes') }}</h6>
                        <ul class="mb-0">
                            <li>{{ __('Categories will be automatically created if they don\'t exist (no duplicates)') }}</li>
                            <li>{{ __('Multiple inventory items will be created based on the quantity field') }}</li>
                            <li>{{ __('Unique numbers will be automatically suffixed for multiple items (e.g., MNT-1, MNT-2)') }}</li>
                            <li>{{ __('Existing items with the same unique number and name will be skipped') }}</li>
                            <li>{{ __('Maximum quantity per item is 1000') }}</li>
                        </ul>
                    </div>

                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('{{ __('Sure Want To Reset?') }}');" class="btn btn-outline-danger me-2">{{ __('Reset Form') }}</button>
                        <button type="submit" class="btn btn-primary confirm-form-success">
                            <i class="ti ti-upload ti-xs me-1"></i>
                            {{ __('Upload Inventories') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External JS  --}}
@endsection

@section('custom_script')
    {{--  External JS  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            // File input validation
            $('#import_file').change(function() {
                var file = this.files[0];
                var fileType = file.type;
                var fileName = file.name;
                
                // Check if it's a CSV file
                if (fileType !== 'text/csv' && !fileName.endsWith('.csv')) {
                    alert('{{ __("Please select a valid CSV file.") }}');
                    this.value = '';
                    return false;
                }
                
                // Check file size (max 10MB)
                if (file.size > 10 * 1024 * 1024) {
                    alert('{{ __("File size should not exceed 10MB.") }}');
                    this.value = '';
                    return false;
                }
            });
        });
    </script>
@endsection
