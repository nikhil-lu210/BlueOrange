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
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    
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
    <b class="text-uppercase">{{ __('All Attendances') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Attendance') }}</li>
    <li class="breadcrumb-item active">{{ __('All Attendances') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('administration.attendance.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="user_id" class="form-label">{{ __('Select Employee') }}</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>{{ __('Select Employee') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                        {{ get_employee_name($user) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-3">
                            <label class="form-label">{{ __('Attendances Of') }}</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="type" class="form-label">{{ __('Select Clockin Type') }}</label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->type) ? 'selected' : '' }}>{{ __('Select Type') }}</option>
                                <option value="Regular" {{ request()->type == 'Regular' ? 'selected' : '' }}>{{ __('Regular') }}</option>
                                <option value="Overtime" {{ request()->type == 'Overtime' ? 'selected' : '' }}>{{ __('Overtime') }}</option>
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->created_month_year || request()->type) 
                            <a href="{{ route('administration.attendance.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_attendance" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Attendances') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>        
    </div>
</div>


@if (request()->user_id)
    @include('administration.attendance.partials._user_stats')
@endif


<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                {{-- $clockinType . 'attendances_backup' . $userName . $monthYear --}}
                <h5 class="mb-0">
                    <span>{{ request()->type ?? request()->type }}</span>
                    <span>Attendances</span>
                    <span>of</span>
                    <span class="text-bold">{{ request()->user_id ? show_user_data(request()->user_id, 'name') : 'All Users' }}</span>
                    <sup>(<b>Month: </b> {{ request()->created_month_year ? request()->created_month_year : date('F Y') }})</sup>
                </h5>
        
                <div class="card-header-elements ms-auto">
                    @if ($attendances->count() > 0)
                        <a href="{{ route('administration.attendance.export', [
                            'user_id' => request('user_id'), 
                            'created_month_year' => request('created_month_year'),
                            'type' => request('type'),
                            'filter_attendance' => request('filter_attendance')
                        ]) }}" target="_blank" class="btn btn-sm btn-dark">
                            <span class="tf-icon ti ti-download me-1"></span>
                            {{ __('Download') }}
                        </a>
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
                            <th class="text-center">Breaks</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($attendances as $key => $attendance) 
                            <tr>
                                <th>#{{ serial($attendances, $key) }}</th>
                                <td>
                                    <span class="text-truncate">{{ show_date($attendance->clock_in_date) }}</span>
                                    <br>
                                    <small class="text-bold text-{{ $attendance->type === 'Regular' ? 'success' : 'warning' }}">{{ $attendance->type }}</small>
                                </td>
                                <td>
                                    {!! show_user_name_and_avatar($attendance->user, role: null) !!}
                                </td>
                                <td>
                                    <div class="d-grid">
                                        @php
                                            if (get_time_only($attendance->clock_in) > $attendance->employee_shift->start_time){
                                                $clockInColor = 'text-danger';
                                            } else {
                                                $clockInColor = 'text-success';
                                            }
                                        @endphp
                                        <span class="text-truncate text-bold {{ $clockInColor }}">{{ show_time($attendance->clock_in) }}</span>
                                        <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="left" title="Shift Start Time">{{ show_time($attendance->employee_shift->start_time) }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-grid">
                                        @isset($attendance->clock_out)
                                            @php
                                                if (get_time_only($attendance->clock_out) < $attendance->employee_shift->end_time){
                                                    $clockOutColor = 'text-danger';
                                                } else {
                                                    $clockOutColor = 'text-success';
                                                }
                                            @endphp
                                            <span class="text-truncate text-bold {{ $clockOutColor }}">{{ show_time($attendance->clock_out) }}</span>
                                        @else
                                            <b class="text-success text-uppercase">Running</b>
                                        @endisset
                                        <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="right" title="Shift End Time">{{ show_time($attendance->employee_shift->end_time) }}</small>
                                    </div>
                                </td>
                                <td class="text-center {{ $attendance->type == 'Overtime' ? 'not-allowed' : '' }}">
                                    @if ($attendance->type == 'Regular') 
                                        <div class="d-grid">
                                            <b class="text-truncate">
                                                <span class="text-warning" title="Total Break Time">
                                                    {{ total_time($attendance->total_break_time) }}
                                                </span>
                                                @isset ($attendance->total_over_break) 
                                                    <small class="text-danger" title="Total Over Break">
                                                        ({{ total_time($attendance->total_over_break) }})
                                                    </small>
                                                @endisset
                                            </b>
                                            <small class="text-truncate text-muted">
                                                Breaks Taken: {{ $attendance->total_breaks_taken }}
                                            </small>
                                        </div>
                                    @else 
                                        <b class="text-muted">No Break</b>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-grid">
                                        @if ($attendance->type == 'Regular') 
                                            @isset($attendance->total_adjusted_time)
                                                @php
                                                    $totalWorkingHour = get_total_hour($attendance->employee_shift->start_time, $attendance->employee_shift->end_time);
                                                @endphp
                                                <b title="Adjusted Total Time">
                                                    {!! total_time_with_min_hour($attendance->total_adjusted_time, $totalWorkingHour) !!}
                                                </b>
                                            @else
                                                <b class="text-success text-uppercase">Running</b>
                                            @endisset
                                            <small class="text-truncate text-muted" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Total Working Time">{{ $attendance->total_time }}</small>
                                        @else 
                                            <b class="text-warning">
                                                {{ total_time($attendance->total_time) }}
                                            </b>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('administration.attendance.show', ['attendance' => $attendance]) }}" class="btn btn-sm btn-icon item-edit" data-bs-toggle="tooltip" title="Show Details">
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

    <script src="{{asset('assets/js/form-layouts.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    
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