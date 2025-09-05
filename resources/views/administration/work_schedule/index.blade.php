@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Work Schedule'))

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
    .work-type-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    .work-type-client { background-color: #28a745; color: white; }
    .work-type-internal { background-color: #007bff; color: white; }
    .work-type-bench { background-color: #ffc107; color: #212529; }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('All Work Schedules') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Work Schedule') }}</li>
    <li class="breadcrumb-item active">{{ __('All Work Schedules') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.work_schedule.index') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="user_id" class="form-label">Select Employee</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>Select Employee</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                        {{ get_employee_name($user) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="weekday" class="form-label">Select Weekday</label>
                            <select name="weekday" id="weekday" class="form-select bootstrap-select w-100 @error('weekday') is-invalid @enderror" data-style="btn-default">
                                <option value="" {{ is_null(request()->weekday) ? 'selected' : '' }}>Select Weekday</option>
                                @foreach ($weekdays as $weekday)
                                    <option value="{{ $weekday }}" {{ $weekday == request()->weekday ? 'selected' : '' }}>{{ $weekday }}</option>
                                @endforeach
                            </select>
                            @error('weekday')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="text" name="start_date" id="start_date" value="{{ request()->start_date }}" class="form-control date-picker" placeholder="YYYY-MM-DD" />
                            @error('start_date')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="text" name="end_date" id="end_date" value="{{ request()->end_date }}" class="form-control date-picker" placeholder="YYYY-MM-DD" />
                            @error('end_date')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->weekday || request()->start_date || request()->end_date)
                            <a href="{{ route('administration.work_schedule.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Schedules
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Work Schedules</h5>

                <div class="card-header-elements ms-auto">
                    @can('User Read')
                        <a href="{{ route('administration.work_schedule.report') }}" class="btn btn-sm btn-info me-2">
                            <span class="tf-icon ti ti-chart-bar ti-xs me-1"></span>
                            View Report
                        </a>
                    @endcan
                    @can('User Create')
                        <a href="{{ route('administration.work_schedule.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Assign Schedule
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Employee</th>
                                <th>Shift Time</th>
                                <th>Work Date</th>
                                <th>Weekday</th>
                                <th>Work Breakdown</th>
                                <th>Total Duration</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workSchedules as $key => $schedule)
                                <tr>
                                    <th>#{{ serial($workSchedules, $key) }}</th>
                                    <td>
                                        <b class="text-dark">{{ get_employee_name($schedule->user) }}</b>
                                        <br>
                                        <small class="text-muted">{{ $schedule->user->email }}</small>
                                    </td>
                                    <td>
                                        <b class="text-dark">{{ $schedule->employeeShift->start_time }} - {{ $schedule->employeeShift->end_time }}</b>
                                        <br>
                                        <small class="text-muted">Total: {{ $schedule->employeeShift->total_time }}</small>
                                    </td>
                                    <td>
                                        <b>{{ show_date($schedule->work_date) }}</b>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $schedule->weekday }}</span>
                                    </td>
                                    <td>
                                        @if ($schedule->workScheduleItems->count() > 0)
                                            @foreach ($schedule->workScheduleItems as $item)
                                                <div class="mb-1">
                                                    <span class="work-type-badge work-type-{{ strtolower($item->work_type) }}">
                                                        {{ $item->work_type }}
                                                    </span>
                                                    <small class="ms-1">{{ $item->work_title }}</small>
                                                    <br>
                                                    <small class="text-muted">{{ $item->start_time }} - {{ $item->end_time }}</small>
                                                </div>
                                            @endforeach
                                        @else
                                            <span class="text-muted">No work items</span>
                                        @endif
                                    </td>
                                    <td>
                                        <b class="text-success">{{ $schedule->formatted_total_duration }}</b>
                                    </td>
                                    <td class="text-center">
                                        @can('User Read')
                                            <a href="{{ route('administration.work_schedule.show', $schedule) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="View Details">
                                                <i class="ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan
                                        @can('User Update')
                                            <a href="{{ route('administration.work_schedule.edit', $schedule) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Schedule">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can('User Delete')
                                            <a href="{{ route('administration.work_schedule.destroy', $schedule) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" data-bs-toggle="tooltip" title="Deactivate Schedule?">
                                                <i class="ti ti-trash"></i>
                                            </a>
                                        @endcan
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

            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
