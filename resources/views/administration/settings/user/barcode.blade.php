@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('User Management'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Barcodes') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item active">{{ __('All Barcodes') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>All Barcodes</span>
                </h5>

                @can ('User Create')
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.settings.user.barcode.all.download') }}" target="_blank" class="btn btn-sm btn-dark">
                            <span class="tf-icon ti ti-download ti-xs me-1"></span>
                            Download All Barcodes
                        </a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Employee ID</th>
                            <th>Employee</th>
                            <th class="text-center">Barcode</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $key => $user)
                            <tr>
                                <th>#{{ serial($users, $key) }}</th>
                                <th><b class="text-primary">{{ $user->userid }}</b></th>
                                <td>
                                    {!! show_user_name_and_avatar($user) !!}
                                </td>
                                <td class="text-center">
                                    @if ($user->hasMedia('barcode'))
                                        <img src="{{ $user->getFirstMediaUrl('barcode') }}" alt="{{ $user->employee->alias_name }} BAR-CODE" class="d-block h-auto m-auto" width="300px">
                                    @else
                                        <span class="text-muted">No Barcode Found</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($user->hasMedia('barcode'))
                                        <a href="{{ spatie_media_download($user->getFirstMedia('barcode')) }}" class="btn btn-sm btn-icon btn-dark" data-bs-toggle="tooltip" title="Download {{ $user->employee->alias_name }}'s Barcode">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    @else
                                        <a href="{{ route('administration.settings.user.generate.bar.code', ['user' => $user]) }}" class="btn btn-sm btn-icon btn-primary confirm-success" data-bs-toggle="tooltip" title="Generate {{ $user->employee->alias_name }}'s Barcode">
                                            <i class="ti ti-capture"></i>
                                        </a>
                                    @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection
