@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Attendance Issues'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    td.not-allowed {
        background: #dbdade;
        color: white !important;
        text-align: center;
        text-transform: uppercase;
        cursor: not-allowed;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Attendance Issues') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance Issues') }}</li>
    <li class="breadcrumb-item active">{{ __('All Attendance Issues') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>Attendance Issues Of</span>
                    <span>of</span>
                    <b>{{ date('F Y') }}</b>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th>Title</th>
                                <th>Issue Date For</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($issues as $key => $issue) 
                                <tr>
                                    <th>#{{ serial($issues, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($issue->user, role: null) !!}
                                    </td>
                                    <td>
                                        <div class="d-block">
                                            <span class="text-truncate text-bold">{{ $issue->title }}</span>
                                            <br>
                                            {!! show_status($issue->status) !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-block">
                                            <span class="text-truncate text-bold">{{ show_date($issue->clock_in_date) }}</span>
                                            <br>
                                            <small class="badge bg-{{ $issue->type === 'Regular' ? 'primary' : 'warning' }}" title="Requested Clock-In Type">{{ $issue->type }}</small>
                                        </div>
                                    </td>
                                    
                                    <td class="text-center">
                                        <a href="{{ route('administration.attendance.issue.show', ['issue' => $issue]) }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="ti ti-info-hexagon"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
    
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
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

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection