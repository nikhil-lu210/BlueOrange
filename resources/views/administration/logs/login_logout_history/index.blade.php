@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Login & Logout Histories'))

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
    <b class="text-uppercase">{{ __('All Login & Logout Histories') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Logs') }}</li>
    <li class="breadcrumb-item active">{{ __('All Login & Logout Histories') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.logs.login_logout_history.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-7">
                            <label for="user_id" class="form-label">{{ __('Select Employee') }}</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>{{ __('Select Employee') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->employee->alias_name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-5">
                            <label class="form-label">{{ __('History Of') }}</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->created_month_year) 
                            <a href="{{ route('administration.logs.login_logout_history.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_histories" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Histories') }}
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
                <h5 class="mb-0">
                    All Login & Logout Histories of 
                    <span class="text-bold">{{ request()->user_id ? show_user_data(request()->user_id, 'name') : 'All Users' }}</span>
                    <sup>(<b>Month: </b> {{ request()->created_month_year ? request()->created_month_year : date('F Y') }})</sup>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Employee</th>
                                <th>Clockin</th>
                                <th>Clockout</th>
                                <th>IP Address</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $key => $history) 
                                <tr>
                                    <th>#{{ serial($histories, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($history->user) !!}
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            <small>
                                                <span class="text-bold">Date:</span>
                                                {{ show_date($history->login_time) }}
                                            </small>
                                            <small>
                                                <span class="text-bold">Time:</span>
                                                {{ show_time($history->login_time) }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @if (!is_null($history->logout_time)) 
                                            <div class="d-grid">
                                                <small>
                                                    <span class="text-bold">Date:</span>
                                                    {{ show_date($history->logout_time) }}
                                                </small>
                                                <small>
                                                    <span class="text-bold">Time:</span>
                                                    {{ show_time($history->logout_time) }}
                                                </small>
                                            </div>
                                        @else 
                                            <span class="badge bg-warning">No History</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-grid">
                                            <small>
                                                <span class="text-bold">Login:</span>
                                                <code>{{ $history->login_ip }}</code>
                                            </small>
                                            @if (!is_null($history->logout_ip)) 
                                                <small>
                                                    <span class="text-bold">Logout:</span>
                                                    <code>{{ $history->logout_ip }}</code>
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="javascript:void(0);" 
                                        class="btn btn-sm btn-icon btn-primary" 
                                        title="Show Details" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#showHistoryModal" 
                                        data-history="{{ json_encode([
                                            'user_name' => $history->user->name,
                                            'login_time' => show_date_time($history->login_time),
                                            'logout_time' => $history->logout_time ? show_date_time($history->logout_time) : "No History",
                                            'login_ip' => $history->login_ip,
                                            'logout_ip' => $history->logout_ip ?? "No History",
                                            'user_agent' => $history->user_agent
                                        ]) }}">
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


{{-- Page Modal --}}
@include('administration.logs.login_logout_history.modals.history_show')

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

    <script>
        $(document).ready(function() {
            $('#showHistoryModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var history = button.data('history');

                console.log(history);
                

                // Update the modal's content.
                var modal = $(this);
                modal.find('.modal-body .user_name').text(history.user_name);
                modal.find('.modal-body .login_time').text(history.login_time);
                modal.find('.modal-body .logout_time').text(history.logout_time);
                modal.find('.modal-body .login_ip').text(history.login_ip);
                modal.find('.modal-body .logout_ip').text(history.logout_ip);
                modal.find('.modal-body .user_agent').text(history.user_agent);
            });
        });
    </script>
@endsection
