@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', ___('Attendance Issues'))

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
    <b class="text-uppercase">{{ ___('All Attendance Issues') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ ___('Attendance Issues') }}</li>
    <li class="breadcrumb-item active">{{ ___('All Attendance Issues') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <form action="{{ route('administration.attendance.issue.my') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label class="form-label">{{ ___('Attendance Issues Of') }}</label>
                            <input type="text" name="issue_month_year" value="{{ request()->issue_month_year ?? old('issue_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('issue_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-5">
                            <label for="type" class="form-label">{{ ___('Select Attendance Type') }}</label>
                            <select name="type" id="type" class="form-select bootstrap-select w-100 @error('type') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->type) ? 'selected' : '' }}>{{ ___('Select Type') }}</option>
                                <option value="Regular" {{ request()->type == 'Regular' ? 'selected' : '' }}>{{ ___('Regular') }}</option>
                                <option value="Overtime" {{ request()->type == 'Overtime' ? 'selected' : '' }}>{{ ___('Overtime') }}</option>
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="status" class="form-label">{{ ___('Select Status') }}</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>{{ ___('Select status') }}</option>
                                <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>{{ ___('Pending') }}</option>
                                <option value="Approved" {{ request()->status == 'Approved' ? 'selected' : '' }}>{{ ___('Approved') }}</option>
                                <option value="Rejected" {{ request()->status == 'Rejected' ? 'selected' : '' }}>{{ ___('Rejected') }}</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->issue_month_year || request()->type || request()->status)
                            <a href="{{ route('administration.attendance.issue.my') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ ___('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_issues" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ ___('Filter Issues') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>{{ ___('Attendance Issues Of') }}</span>
                    <span>of</span>
                    <b>{{ request()->issue_month_year ? request()->issue_month_year : date('F Y') }}</b>
                    @if(request()->type || request()->status)
                        <sup>(<b>Filtered: </b>
                            @if(request()->type) {{ request()->type }} @endif
                            @if(request()->type && request()->status) | @endif
                            @if(request()->status) {{ request()->status }} @endif
                        )</sup>
                    @endif
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>{{ ___('Sl.') }}</th>
                                <th>{{ ___('Name') }}</th>
                                <th>{{ ___('Title') }}</th>
                                <th>{{ ___('Issue Date For') }}</th>
                                <th class="text-center">{{ ___('Action') }}</th>
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
