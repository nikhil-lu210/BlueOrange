@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('My or My Employees Daily Work Update'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    
    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('My or My Employees Daily Work Update') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Work Update') }}</li>
    <li class="breadcrumb-item active">{{ __('My or My Employees Daily Work Update') }}</li>
@endsection


@section('content')

<!-- Start row -->
@if (auth()->user()->tl_employees_daily_work_updates->count() > 0) 
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('administration.daily_work_update.my') }}" method="get" autocomplete="off">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="mb-3 col-md-7">
                                <label for="user_id" class="form-label">Select Employee</label>
                                <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                    <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>Select Employee</option>
                                    @foreach ($roles as $role)
                                        <optgroup label="{{ $role->name }}">
                                            @foreach ($role->users as $user)
                                                <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                                    {{ get_employee_name($user) }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                @error('user_id')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            
                            <div class="mb-3 col-md-5">
                                <label class="form-label">Work Updates Of</label>
                                <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                                @error('created_month_year')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>                        
                        </div>
                        
                        <div class="col-md-12 text-end">
                            @if (request()->user_id || request()->created_month_year) 
                                <a href="{{ route('administration.daily_work_update.my') }}" class="btn btn-danger confirm-warning">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    Reset Filters
                                </a>
                            @endif
                            <button type="submit" name="filter_work_updates" value="true" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                Filter Work Updates
                            </button>
                        </div>
                    </div>
                </div>
            </form>        
        </div>
    </div>
@endif

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>My or My Employees Work Updates</span>
                    @if (request()->created_month_year) 
                        <sup>(<b>Month: </b> {{ request()->created_month_year }})</sup>
                    @endif
                </h5>
        
                <div class="card-header-elements ms-auto">
                    @can ('Daily Work Update Create') 
                        <a href="{{ route('administration.daily_work_update.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Submit Work Update
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
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Team Leader</th>
                                <th>Submitted At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyWorkUpdates as $key => $dailyUpdate) 
                                <tr class="@if (is_null($dailyUpdate->rating)) bg-label-danger @endif">
                                    <th>#{{ serial($dailyWorkUpdates, $key) }}</th>
                                    <td>
                                        <b>{{ show_date($dailyUpdate->date) }}</b>
                                        <br>
                                        @if (!is_null($dailyUpdate->rating))
                                            @php
                                                switch ($dailyUpdate->rating) {
                                                    case '1':
                                                        $color = 'danger';
                                                        break;
                                                    case '2':
                                                        $color = 'warning';
                                                        break;
                                                    case '3':
                                                        $color = 'dark';
                                                        break;
                                                    case '4':
                                                        $color = 'primary';
                                                        break;                                                    
                                                    default:
                                                        $color = 'success';
                                                        break;
                                                }
                                            @endphp
                                            <small class="badge bg-{{ $color }}">
                                                {{ $dailyUpdate->rating }} out of 5
                                            </small>
                                        @else
                                            <small class="badge bg-danger">
                                                {{ __('Not Reviewed') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyUpdate->user]) }}">
                                                        @if ($dailyUpdate->user->hasMedia('avatar'))
                                                            <img src="{{ $dailyUpdate->user->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $dailyUpdate->user->name }} Avatar" class="rounded-circle">
                                                        @else
                                                            <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">
                                                                {{ profile_name_pic($dailyUpdate->user) }}
                                                            </span>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyUpdate->user]) }}" target="_blank" class="emp_name text-truncate">{{ $dailyUpdate->user->name }}</a>
                                                <small class="emp_post text-truncate text-muted">{{ $dailyUpdate->user->role->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-start align-items-center user-name">
                                            <div class="avatar-wrapper">
                                                <div class="avatar me-2">
                                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyUpdate->team_leader]) }}">
                                                        @if ($dailyUpdate->team_leader->hasMedia('avatar'))
                                                            <img src="{{ $dailyUpdate->team_leader->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $dailyUpdate->team_leader->name }} Avatar" class="rounded-circle">
                                                        @else
                                                            <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold">
                                                                {{ profile_name_pic($dailyUpdate->team_leader) }}
                                                            </span>
                                                        @endif
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <a href="{{ route('administration.settings.user.show.profile', ['user' => $dailyUpdate->team_leader]) }}" target="_blank" class="emp_name text-truncate">{{ $dailyUpdate->team_leader->name }}</a>
                                                <small class="emp_post text-truncate text-muted">{{ $dailyUpdate->team_leader->role->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <small class="text-bold">{{ show_date($dailyUpdate->created_at) }}</small>
                                        <br>
                                        <span>
                                            at
                                            <small class="text-bold">{{ show_time($dailyUpdate->created_at) }}</small>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @can ('Daily Work Update Delete') 
                                            <a href="{{ route('administration.daily_work_update.destroy', ['daily_work_update' => $dailyUpdate]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Daily Work Update?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Daily Work Update Read') 
                                            <a href="{{ route('administration.daily_work_update.show', ['daily_work_update' => $dailyUpdate]) }}" target="_blank" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
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
    
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
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
