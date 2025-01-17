@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Import Users'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Import Users') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.create') }}">{{ __('Create New User') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Import Users') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Import Users</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.user.create') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        {{ __('Create User') }}
                    </a>
                </div>
            </div>
            
            <div class="card-body">
                <form action="{{ route('administration.settings.user.import.upload') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="role_id" class="form-label">Select Role <strong class="text-danger">*</strong></label>
                            <select name="role_id" class="select2 form-select @error('role_id') is-invalid @enderror" data-allow-clear="true" required autofocus>
                                <option value="" selected disabled>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" @selected(old('role_id') == $role->id)>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-12">
                            <label for="import_file" class="form-label">{{ __('Users File') }} <strong class="text-danger">*</strong></label>
                            <input type="file" id="import_file" name="import_file" value="{{ old('import_file') }}" placeholder="{{ __('Files') }}" class="form-control @error('import_file') is-invalid @enderror" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required/>
                            <small>
                                <span class="text-dark text-bold">Note:</span>
                                <span>Please select <b class="text-bold text-info">.csv / .xlsx</b> file only.</span>
                            </small>
                            @error('import_file')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('Sure Want To Reset?');" class="btn btn-outline-danger me-2">Reset Form</button>
                        <button type="submit" class="btn btn-primary confirm-form-success">Upload Users</button>
                    </div>
                </form>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // 
        });
    </script>
@endsection
