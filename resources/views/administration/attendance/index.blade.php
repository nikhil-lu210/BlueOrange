@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Attendance'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Attendances') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item active">{{ __('All Attendances') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Attendances</h5>
        
                <div class="card-header-elements ms-auto">
                    @if (!$clockedIn) 
                        <form action="{{ route('administration.attendance.clockin') }}" method="post">
                            @csrf
                            <button type="submit" name="attendance" value="clock_in" class="btn btn-sm btn-success">
                                <span class="tf-icon ti ti-clock-check me-1"></span>
                                Clock In
                            </button>
                        </form>
                    @else
                        <form action="{{ route('administration.attendance.clockout') }}" method="post">
                            @csrf
                            <button type="submit" name="attendance" value="clock_out" class="btn btn-sm btn-danger">
                                <span class="tf-icon ti ti-clock-off me-1"></span>
                                Clock Out
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Date</th>
                            <th>Name</th>
                            <th>Clocked IN</th>
                            <th>Clock Out</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $key => $attendance) 
                            <tr>
                                <th>#{{ serial($attendances, $key) }}</th>
                                <td>{{ show_date($attendance->clock_in_date) }}</td>
                                <td>{{ $attendance->user->name }}</td>
                                <td>{{ show_time($attendance->clock_in) }}</td>
                                <td>
                                    @isset($attendance->clock_out)
                                        {{ show_time($attendance->clock_out) }}
                                    @else
                                        <b class="text-success text-uppercase">Running</b>
                                    @endisset
                                </td>
                                <td>
                                    @isset($attendance->total_time)
                                        {{ $attendance->total_time }}
                                    @else
                                        <b class="text-success text-uppercase">Running</b>
                                    @endisset
                                </td>
                                <td class="text-center">
                                    <a href="#" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Details">
                                        <i class="text-primary ti ti-info-hexagon"></i>
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
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection