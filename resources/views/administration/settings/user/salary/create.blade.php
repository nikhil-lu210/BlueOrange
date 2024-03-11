@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Upgrade Salary'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
        dt > i {
            margin-top: -2px;
        }
        dl > dd > span > i {
            margin-top: -4px;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Upgrade Employee Salary') }}</b>
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
    <li class="breadcrumb-item active">{{ __('Upgrade Salary') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    {{ __('Upgrade Salary of') }}
                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}" target="_blank">
                        {{ $user->name }}
                    </a>
                </h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.user.salary.index', ['user' => $user]) }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Salary History
                    </a>
                </div>
            </div>
            <form action="{{ route('administration.settings.user.salary.store', ['user' => $user]) }}" method="post" autocomplete="off" name="sumbit_form" id="submitForm">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="basic_salary" class="form-label">{{ __('Basic Salary') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="basic_salary" id="basic_salary" value="{{ old('basic_salary') }}" class="form-control" placeholder="20,000" required>
                            </div>
                            @error('basic_salary')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="house_benefit" class="form-label">{{ __('House Benefit') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="house_benefit" id="house_benefit" value="{{ old('house_benefit') }}" class="form-control" placeholder="20,000" required>
                            </div>
                            @error('house_benefit')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="transport_allowance" class="form-label">{{ __('Transport Allowance') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="transport_allowance" id="transport_allowance" value="{{ old('transport_allowance') }}" class="form-control" placeholder="20,000" required>
                            </div>
                            @error('transport_allowance')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="medical_allowance" class="form-label">{{ __('Medical Allowance') }} <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="medical_allowance" id="medical_allowance" value="{{ old('medical_allowance') }}" class="form-control" placeholder="20,000" required>
                            </div>
                            @error('medical_allowance')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="night_shift_allowance" class="form-label">{{ __('Night Shift Allowance') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="night_shift_allowance" id="night_shift_allowance" value="{{ old('night_shift_allowance') }}" class="form-control" placeholder="20,000">
                            </div>
                            @error('night_shift_allowance')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="other_allowance" class="form-label">{{ __('Other Allowance') }}</label>
                            <div class="input-group input-group-merge">
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                <input type="number" step="0.01" min="0" name="other_allowance" id="other_allowance" value="{{ old('other_allowance') }}" class="form-control" placeholder="20,000">
                            </div>
                            @error('other_allowance')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <hr>
                    <dl class="row mt-3 mb-0 text-primary">
                        <dt class="col-5 mb-2 text-nowrap">
                            <i class="ti ti-topology-ring-3"></i>
                            <span class="mx-2 text-bold">Total Salary:</span>
                        </dt>
                        <dd class="col-7">
                            <span class="text-bold float-end"><i class="ti ti-currency-taka"></i><span id="total_salary">0</span></span>
                        </dd>
                    </dl>

                    <div class="row">
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary float-end">
                                <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                                {{ __('Upgrade') }}
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function () {
            // Function to calculate total salary
            function calculateTotalSalary() {
                // Get input values
                var basicSalary = parseFloat($("#basic_salary").val()) || 0;
                var houseBenefit = parseFloat($("#house_benefit").val()) || 0;
                var transportAllowance = parseFloat($("#transport_allowance").val()) || 0;
                var medicalAllowance = parseFloat($("#medical_allowance").val()) || 0;
                var nightShiftAllowance = parseFloat($("#night_shift_allowance").val()) || 0;
                var otherAllowance = parseFloat($("#other_allowance").val()) || 0;

                // Calculate total salary
                var totalSalary = basicSalary + houseBenefit + transportAllowance + medicalAllowance + nightShiftAllowance + otherAllowance;

                // Update the total salary on the page
                $("#total_salary").text(totalSalary.toFixed(2));
            }

            // Attach the calculateTotalSalary function to input input events
            $("#basic_salary, #house_benefit, #transport_allowance, #medical_allowance, #night_shift_allowance, #other_allowance").on('input', function () {
                calculateTotalSalary();
            });

            // Initial calculation on page load
            calculateTotalSalary();
        });
    </script>    
@endsection
