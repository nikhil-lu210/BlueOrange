@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Employee Salary History'))

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
    <b class="text-uppercase">{{ __('Employee Salary History') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}">{{ $user->name }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Salary History') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Salary History of {{ $user->name }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="#" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                        Upgrade Salary
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Total Salary</th>
                            <th>Implemented From</th>
                            <th>Implemented To</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($salaries as $key => $salary) 
                            <tr>
                                <th>#{{ serial($salaries, $key) }}</th>
                                <td>
                                    <span class="text-bold">
                                        <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                        {{ $salary->total }}
                                    </span>    
                                </td>
                                <td>{{ show_date($salary->implemented_from) }}</td>
                                <td>
                                    @if ($salary->implemented_to) 
                                        {{ show_date($salary->implemented_to) }}
                                    @else
                                        <span class="badge bg-label-success">Running Salary</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-label-success">{{ $salary->status }}</span>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
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
