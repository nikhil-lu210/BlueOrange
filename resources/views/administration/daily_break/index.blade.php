@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Daily Break'))

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
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Daily Breaks') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Break') }}</li>
    <li class="breadcrumb-item active">{{ __('All Daily Breaks') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('administration.daily_break.index') }}" method="get" autocomplete="off">
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
                            <label class="form-label">{{ __('Breaks Of') }}</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="type" class="form-label">{{ __('Select Break Type') }}</label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->type) ? 'selected' : '' }}>{{ __('Select Type') }}</option>
                                <option value="Short" {{ request()->type == 'Short' ? 'selected' : '' }}>{{ __('Short Break') }}</option>
                                <option value="Long" {{ request()->type == 'Long' ? 'selected' : '' }}>{{ __('Long Break') }}</option>
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div> 
                    
                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->created_month_year || request()->type) 
                            <a href="{{ route('administration.daily_break.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_breaks" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Breaks') }}
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
                {{-- $clockinType . 'attendances_backup' . $userName . $monthYear --}}
                <h5 class="mb-0">
                    <span>Daily</span>
                    <span>{{ request()->type ?? request()->type }}</span>
                    <span>Breaks</span>
                    <span>of</span>
                    <span class="text-bold">{{ request()->user_id ? show_user_data(request()->user_id, 'name') : 'All Users' }}</span>
                    <sup>(<b>Month: </b> {{ request()->created_month_year ? request()->created_month_year : date('F Y') }})</sup>
                </h5>
        
                <div class="card-header-elements ms-auto">
                    @if ($dailyBreaks->count() > 0)
                        <a href="{{ route('administration.daily_break.export', [
                            'user_id' => request('user_id'), 
                            'created_month_year' => request('created_month_year'),
                            'type' => request('type'),
                            'filter_breaks' => request('filter_breaks')
                        ]) }}" target="_blank" class="btn btn-sm btn-dark">
                            <span class="tf-icon ti ti-download me-1"></span>
                            {{ __('Download') }}
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Name</th>
                                <th>Break Started</th>
                                <th>Break Stopped</th>
                                <th>Total</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyBreaks as $key => $break) 
                                <tr>
                                    <th>#{{ serial($dailyBreaks, $key) }}</th>
                                    <td>
                                        <b class="text-dark">{{ show_date($break->date) }}</b>
                                        <br>
                                        <small class="text-bold badge bg-{{ $break->type === 'Short' ? 'primary' : 'warning' }}">{{ $break->type }} Break</small>
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($break->user, role: null) !!}
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            <span class="text-bold text-dark">{{ show_time($break->break_in_at) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @isset ($break->break_out_at) 
                                            <span class="text-bold text-dark">{{ show_time($break->break_out_at) }}</span>
                                        @else
                                            <span class="badge bg-label-danger text-bold" title="Break Running">{{ __('Running') }}</span>
                                        @endisset
                                    </td>
                                    <td>
                                        @isset ($break->total_time)
                                            @php
                                                if (is_null($break->over_break)) {
                                                    $color = 'success';
                                                } else {
                                                    $color = 'warning';
                                                }
                                            @endphp
                                            <span class="text-bold text-{{ $color }}">{{ total_time($break->total_time) }}</span>
                                        @else
                                            <span class="badge bg-label-danger text-bold" title="Break Running">{{ __('Running') }}</span>
                                        @endisset
                                        @isset ($break->over_break)
                                            <br>
                                            <small class="text-danger text-bold" title="Total Over Break">
                                                {{ total_time($break->over_break) }}
                                            </small>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @can ('Daily Break Delete') 
                                            <a href="{{ route('administration.daily_break.destroy', ['break' => $break]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Break?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        <a href="{{ route('administration.daily_break.show', ['break' => $break]) }}" class="btn btn-sm btn-icon btn-primary item-edit" data-bs-toggle="tooltip" title="Show Details">
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