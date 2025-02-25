@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Leave'))

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
    <b class="text-uppercase">{{ __('All Leaves') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Leave') }}</li>
    <li class="breadcrumb-item active">{{ __('All Leaves') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('administration.leave.history.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="team_leader_id" class="form-label">{{ __('Select Team Leader') }}</label>
                            <select name="team_leader_id" id="team_leader_id" class="select2 form-select @error('team_leader_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->team_leader_id) ? 'selected' : '' }}>{{ __('Select Team Leader') }}</option>
                                @foreach ($teamLeaders as $leader)
                                    <option value="{{ $leader->id }}" {{ $leader->id == request()->team_leader_id ? 'selected' : '' }}>
                                        {{ get_employee_name($leader) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('announcer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-3">
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
                        
                        <div class="mb-3 col-md-2">
                            <label class="form-label">{{ __('Leaves Of') }}</label>
                            <input type="text" name="leave_month_year" value="{{ request()->leave_month_year ?? old('leave_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('leave_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="type" class="form-label">{{ __('Select Leave Type') }}</label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->type) ? 'selected' : '' }}>{{ __('Select Type') }}</option>
                                <option value="Earned" {{ request()->type == 'Earned' ? 'selected' : '' }}>{{ __('Earned Leave') }}</option>
                                <option value="Sick" {{ request()->type == 'Sick' ? 'selected' : '' }}>{{ __('Sick Leave') }}</option>
                                <option value="Casual" {{ request()->type == 'Casual' ? 'selected' : '' }}>{{ __('Casual Leave') }}</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="status" class="form-label">{{ __('Select Status') }}</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>{{ __('Select status') }}</option>
                                <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="Approved" {{ request()->status == 'Approved' ? 'selected' : '' }}>{{ __('Approved') }}</option>
                                <option value="Rejected" {{ request()->status == 'Rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                                <option value="Canceled" {{ request()->status == 'Canceled' ? 'selected' : '' }}>{{ __('Canceled') }}</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div> 
                    
                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->leave_month_year || request()->type) 
                            <a href="{{ route('administration.leave.history.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_leaves" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Leaves') }}
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
                    <span>{{ request()->type ?? request()->type }}</span>
                    <span>Leaves</span>
                    <span>of</span>
                    <span class="text-bold">{{ request()->user_id ? show_user_data(request()->user_id, 'name') : 'All Users' }}</span>
                    <sup>(<b>Month: </b> {{ request()->leave_month_year ? request()->leave_month_year : date('F Y') }})</sup>
                </h5>
        
                <div class="card-header-elements ms-auto">
                    @if ($leaves->count() > 0)
                        <a href="{{ route('administration.leave.history.export', [
                            'user_id' => request('user_id'), 
                            'leave_month_year' => request('leave_month_year'),
                            'type' => request('type'),
                            'filter_leaves' => request('filter_leaves')
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
                                <th>Employee</th>
                                <th>Date</th>
                                <th>Total Leave</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($leaves as $key => $leave) 
                                <tr>
                                    <th>#{{ serial($leaves, $key) }}</th>
                                    <td>
                                        {!! show_user_name_and_avatar($leave->user, role: null) !!}
                                    </td>
                                    <td>
                                        @php
                                            switch ($leave->type) {
                                                case 'Earned':
                                                    $typeBg = 'success';
                                                    break;
                                                
                                                case 'Sick':
                                                    $typeBg = 'warning';
                                                    break;
                                                
                                                default:
                                                    $typeBg = 'danger';
                                                    break;
                                            }
                                        @endphp
                                        {{ show_date($leave->date) }}
                                        <br>
                                        <small class="badge bg-label-{{ $typeBg }}" title="Requested Leave Type">{{ $leave->type }} Leave</small>
                                    </td>
                                    <td>
                                        @if (!is_null($leave->is_paid_leave))
                                            @if ($leave->is_paid_leave == true)
                                                <small class="badge bg-success">Paid</small>
                                            @else
                                                <small class="badge bg-danger">Unpaid</small>
                                            @endif
                                            <br>
                                        @endif
                                        <span class="text-bold">{{ $leave->total_leave->forHumans() }}</span>
                                    </td>
                                    <td>
                                        @php
                                            switch ($leave->status) {
                                                case 'Pending':
                                                    $statusBg = 'primary';
                                                    break;
                                                
                                                case 'Approved':
                                                    $statusBg = 'success';
                                                    break;
                                                
                                                default:
                                                    $statusBg = 'danger';
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge bg-{{ $statusBg }}">{{ $leave->status }}</span>
                                        @if (!is_null($leave->reviewed_by))
                                            <br>
                                            <a href="{{ route('administration.settings.user.show.profile', ['user' => $leave->reviewer]) }}" target="_blank" class="text-bold text-primary" title="Reviewed By">
                                                {{ $leave->reviewer->first_name . ' (' . $leave->reviewer->employee->alias_name . ')' }}
                                            </a>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.leave.history.show', ['leaveHistory' => $leave]) }}" class="btn btn-sm btn-icon btn-primary item-edit" data-bs-toggle="tooltip" title="Show Details">
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