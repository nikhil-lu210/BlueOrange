@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Monthly Salaries'))

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
    <b class="text-uppercase">{{ __('Monthly Salaries') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Salary') }}</li>
    <li class="breadcrumb-item active">{{ __('Monthly Salaries') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('administration.accounts.salary.monthly.index') }}" method="get" autocomplete="off">
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
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        
                        <div class="mb-3 col-md-3">
                            <label class="form-label">{{ __('Salaries Of') }}</label>
                            <input type="text" name="for_month" value="{{ request()->for_month ?? old('for_month') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('for_month')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">{{ __('Select Status') }}</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>{{ __('Select Status') }}</option>
                                <option value="Paid" {{ request()->status == 'Paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                                <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->for_month || request()->status) 
                            <a href="{{ route('administration.accounts.salary.monthly.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                {{ __('Reset Filters') }}
                            </a>
                        @endif
                        <button type="submit" name="filter_salaries" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            {{ __('Filter Salaries') }}
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
                <h5 class="mb-0">Monthly Salaries</h5>
        
                <div class="card-header-elements ms-auto">
                    @if ($canManuallyGenerate) 
                        <a href="{{ route('administration.accounts.salary.monthly.generage') }}" class="btn btn-sm btn-primary confirm-warning">
                            <span class="tf-icon ti ti-check ti-xs me-1"></span>
                            Generate {{ show_month($lastMonth) }} Salaries
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th><i class="ti ti-hash"></i></th>
                                <th>Payment For</th>
                                <th>Employee</th>
                                <th>Base Salary</th>
                                <th>Total Payable</th>
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($monthly_salaries as $key => $monthlySalary) 
                                <tr>
                                    <th>#{{ serial($monthly_salaries, $key) }}</th>
                                    <td>
                                        <span class="text-bold">{{ show_month($monthlySalary->for_month) }}</span>
                                        @isset ($monthlySalary->paid_at) 
                                            <br>
                                            <span title="Paid At">
                                                <span class="text-muted">{{ show_date($monthlySalary->paid_at) }}</span>
                                                <br>
                                                at <span class="text-muted">{{ show_time($monthlySalary->paid_at) }}</span>
                                            </span>
                                        @endisset
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($monthlySalary->user) !!}
                                    </td>
                                    <td>
                                        <a href="{{ route('administration.settings.user.salary.show', ['salary' => $monthlySalary->salary, 'user' => $monthlySalary->user]) }}" target="_blank" class="text-bold" data-bs-toggle="tooltip" title="{{ spell_number($monthlySalary->salary->total) }} taka only">
                                            <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                            {{ format_number($monthlySalary->salary->total) }}
                                        </a>
                                    </td>
                                    <td>
                                        <span class="text-bold" data-bs-toggle="tooltip" title="{{ spell_number($monthlySalary->total_payable) }} taka only">
                                            <i class="ti ti-currency-taka" style="margin-top: -4px; margin-right: -5px;"></i>
                                            {{ format_number($monthlySalary->total_payable) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $monthlySalary->status == 'Paid' ? 'success' : 'danger' }}">{{ $monthlySalary->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('administration.accounts.salary.monthly.show', ['monthly_salary' => $monthlySalary]) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
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
