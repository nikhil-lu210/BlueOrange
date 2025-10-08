@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', ___('Import Attendance'))

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
    <b class="text-uppercase">{{ ___('Import Attendance') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ ___('Attendance') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.attendance.create') }}">{{ ___('Assign Attendance') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ ___('Import Attendance') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ ___('Import Attendance') }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.attendance.create') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        {{ ___('Create Attendance') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <form action="{{ route('administration.attendance.import.upload') }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label for="import_file" class="form-label">
                                {{ ___('Attendance File') }} <sup class="text-dark text-bold">({{ ___('.csv file only') }})</sup> <strong class="text-danger">*</strong>
                            </label>
                            <input type="file" id="import_file" name="import_file" value="{{ old('import_file') }}" placeholder="{{ ___('Files') }}" class="form-control @error('import_file') is-invalid @enderror" accept=".csv" required/>
                            <small>
                                <span class="text-dark text-bold">Note:</span>
                                <span>{{ ___('Please select') }} <b class="text-bold text-info">.csv</b> {{ ___('file only') }}.</span>
                            </small>
                            <b class="float-end">
                                <a href="{{ asset('import_templates_sample/attendance_import_sample.csv') }}" class="text-primary text-bold">
                                    <span class="tf-icon ti ti-download"></span>
                                    {{ ___('Download Formatted Template') }}
                                </a>
                            </b>
                            <br>
                            @error('import_file')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <button type="reset" onclick="return confirm('Sure Want To Reset?');" class="btn btn-outline-danger me-2">{{ ___('Reset Form') }}</button>
                        <button type="submit" class="btn btn-primary confirm-form-success">
                            <i class="ti ti-upload ti-xs me-1"></i>
                            {{ ___('Upload Attendances') }}
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
