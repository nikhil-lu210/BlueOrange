@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Monthly Salary History'))

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
    <b class="text-uppercase">{{ __('Monthly Salary History') }}</b>
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
    <li class="breadcrumb-item active">{{ __('Monthly Salary History') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Monthly Salary History of {{ $user->name }}</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.user.salary.create', ['user' => $user]) }}" class="btn btn-sm btn-primary">
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
                            <th>Base Salary</th>
                            <th>Total Earning</th>
                            <th>Paid At</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($monthly_salaries as $key => $monthlySalary) 
                            <tr>
                                <th>#{{ serial($monthly_salaries, $key) }}</th>
                                <td>
                                    <a href="{{ route('administration.settings.user.salary.show', ['user' => $user, 'salary' => $monthlySalary->salary]) }}" target="_blank" class="text-bold" data-bs-toggle="tooltip" title="{{ spell_number($monthlySalary->salary->total) }}">
                                        <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                        {{ format_number($monthlySalary->salary->total) }}
                                    </a>
                                </td>
                                <td>{{ 'total_earning' }}</td>
                                <td>{{ show_date($monthlySalary->created_at) }}</td>
                                <td>
                                    <span class="badge bg-label-{{ $monthlySalary->status == 'Paid' ? 'success' : 'danger' }}">{{ $monthlySalary->status }}</span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('administration.settings.user.salary.monthly.show', ['user' => $user, 'monthly_salary' => $monthlySalary]) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
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