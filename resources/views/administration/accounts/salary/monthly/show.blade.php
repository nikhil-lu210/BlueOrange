@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Monthly Salary Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-invoice.css') }}">
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
        
        th > i, td > i {
            margin-top: -4px;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Monthly Salary Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Accounts') }}</li>
    <li class="breadcrumb-item">{{ __('Salary') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.accounts.salary.monthly.index') }}">
            {{ __('Monthly Salaries') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Monthly Salary Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card invoice-preview-card">
            <div class="card-header d-flex justify-content-between border-bottom header-elements">
                <h5 class="card-action-title mb-0">
                    <span class="text-dark text-bold">{{ $monthly_salary->user->name }}'s</span> 
                    <span class="text-muted">Monthly Salary Details of</span> 
                    <span class="text-dark text-bold">{{ show_month($monthly_salary->for_month) }}</span>
                </h5>
                <div class="card-action-element">
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if ($monthly_salary->status !== 'Paid')
                                <li>
                                    <a class="dropdown-item text-primary" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addEarningModal">
                                        <i class="ti ti-plus me-1 fs-5" style="margin-top: -5px;"></i>
                                        Add Earning
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addDeductionModal">
                                        <i class="ti ti-minus me-1 fs-5" style="margin-top: -5px;"></i>
                                        Add Deduction
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item btn btn-warning confirm-warning" href="{{ route('administration.accounts.salary.monthly.regenerate', ['monthly_salary' => $monthly_salary]) }}">
                                        <i class="ti ti-refresh me-1 fs-5" style="margin-top: -5px;"></i>
                                        Re-Generate Salary
                                    </a>
                                </li>
                            @else
                                @isset ($payslip) 
                                    <li>
                                        <a class="dropdown-item text-dark" href="{{ file_media_download($payslip) }}" target="_blank">
                                            <i class="ti ti-download me-1 fs-5" style="margin-top: -5px;"></i>
                                            Download Payslip
                                        </a>
                                    </li>
                                @endisset
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item btn btn-primary" href="{{ route('administration.accounts.salary.monthly.send.mail.payslip', ['monthly_salary' => $monthly_salary]) }}">
                                        <i class="ti ti-mail-share me-1 fs-5"></i>
                                        Send Email (Payslip)
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mt-3">
                    <div class="col-md-6 text-start">
                        <div class="mb-0">
                            <div class="mb-2 app-brand-link">
                                <span class="app-brand-logo demo">
                                    <img src="{{ asset('Logo/logo_black_01.png') }}" width="100%">
                                </span>
                                <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
                            </div>
                            <dl class="row mt-1 mb-1">
                                <dt class="col-2 fw-medium text-nowrap">
                                    <span class="fw-medium mx-2 text-heading">Company:</span>
                                </dt>
                                <dd class="col-10 mb-0">
                                    <span>{{ __('Staff-India (UK) Ltd.') }}</span>
                                </dd>
                            </dl>
                            <dl class="row mb-1">
                                <dt class="col-2 fw-medium text-nowrap">
                                    <span class="fw-medium mx-2 text-heading">Address:</span>
                                </dt>
                                <dd class="col-10 mb-0">
                                    <span>{{ __('House-7, Road-30, Block-D, Tposhor, Sylhet, Bangladesh') }}</span>
                                </dd>
                            </dl>
                            <dl class="row mb-1">
                                <dt class="col-2 fw-medium text-nowrap">
                                    <span class="fw-medium mx-2 text-heading">Phone No:</span>
                                </dt>
                                <dd class="col-10 mb-0">
                                    <span>{{ __('+8801712345678') }}</span>
                                </dd>
                            </dl>
                            <dl class="row mb-1">
                                <dt class="col-2 fw-medium text-nowrap">
                                    <span class="fw-medium mx-2 text-heading">Email:</span>
                                </dt>
                                <dd class="col-10">
                                    <span>{{ __('manager@mail.com') }}</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div>
                            <h4 class="fw-medium mb-2 text-uppercase">#{{ $monthly_salary->payslip_id }}</h4>
                            <div class="mb-2 pt-1">
                                <span class="text-bold text-dark">Payment For:</span>
                                <span class="fw-medium">{{ show_month($monthly_salary->for_month) }}</span>
                            </div>
                            @isset ($monthly_salary->paid_at) 
                                <div class="mb-2 pt-1">
                                    <span class="text-bold text-dark">Paid At:</span>
                                    <span class="fw-medium">{{ show_date_time($monthly_salary->paid_at) }}</span>
                                </div>
                            @endisset
                            <div class="pt-1">
                                <span class="text-bold text-dark">Pay To:</span>
                                <span class="fw-bold">
                                    <a href="{{ route('administration.settings.user.show.profile', ['user' => $monthly_salary->user]) }}" target="_blank">{{ $monthly_salary->user->name }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4"/>

                <div class="row">
                    <div class="col-md-6 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                        <h6 class="mb-3 text-bold">Base Salary Breakdown</h6>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td class="pe-4 border-0">Basic Salary:</td>
                                    <td class="border-0">
                                        <i class="ti ti-currency-taka"></i>
                                        {{ format_number($monthly_salary->salary->basic_salary) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">House Benefit:</td>
                                    <td class="border-0">
                                        <i class="ti ti-currency-taka"></i>
                                        {{ format_number($monthly_salary->salary->house_benefit) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">Transportation:</td>
                                    <td class="border-0">
                                        <i class="ti ti-currency-taka"></i>
                                        {{ format_number($monthly_salary->salary->transport_allowance) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">Medical Allowance:</td>
                                    <td class="border-0">
                                        <i class="ti ti-currency-taka"></i>
                                        {{ format_number($monthly_salary->salary->medical_allowance) }}
                                    </td>
                                </tr>
                                @isset ($monthly_salary->salary->night_shift_allowance) 
                                    <tr>
                                        <td class="pe-4 border-0">Night-Shift Allowance:</td>
                                        <td class="border-0">
                                            <i class="ti ti-currency-taka"></i>
                                            {{ format_number($monthly_salary->salary->night_shift_allowance) }}
                                        </td>
                                    </tr>
                                @endisset
                                @isset ($monthly_salary->salary->other_allowance) 
                                    <tr>
                                        <td class="pe-4 border-0">Other Allowance:</td>
                                        <td class="border-0">
                                            <i class="ti ti-currency-taka"></i>
                                            {{ format_number($monthly_salary->salary->other_allowance) }}
                                        </td>
                                    </tr>
                                @endisset
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3 text-bold">Work Summary <small class="text-muted">({{ show_month($monthly_salary->for_month) }})</small></h6>
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <td class="pe-4 border-0">Total Workable Days:</td>
                                    <td class="border-0">{{ format_number($monthly_salary->total_workable_days) }} Days</td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">Total Weekends:</td>
                                    <td class="border-0">{{ format_number($monthly_salary->total_weekends) }} Days</td>
                                </tr>
                                @if ($monthly_salary->total_holidays > 0) 
                                    <tr>
                                        <td class="pe-4 border-0">Total Holiday(s):</td>
                                        <td class="border-0">{{ format_number($monthly_salary->total_holidays) }} Day(s)</td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="pe-4 border-0">Total Worked (Regular):</td>
                                    <td class="border-0">{{ total_time($salary['total_worked_regular']) }}</td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">Total Worked (Overtime):</td>
                                    <td class="border-0">{{ total_time($salary['total_worked_overtime']) }}</td>
                                </tr>
                                <tr>
                                    <td class="pe-4 border-0">Hourly Rate:</td>
                                    <td class="border-0">{{ format_number($monthly_salary->hourly_rate) }} <sup class="text-bold">TK</sup></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <hr class="my-4"/>

                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive table-bordered">
                            <table class="table m-0">
                                <thead class="bg-label-primary">
                                    <tr class="text-bold">
                                        <th class="text-primary text-bold">Gross Earnings</th>
                                        <th class="text-primary text-bold text-end">
                                            <i class="ti ti-currency-taka"></i><span>{{ $salary['total_earning'] }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($salary['earnings'] as $earning) 
                                        <tr>
                                            <th class="text-nowrap">{{ $earning->reason }}</th>
                                            <td class="text-end">
                                                {{ format_number($earning->total) }}
                                                <sup class="text-bold">TK</sup>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="table-responsive table-bordered">
                            <table class="table m-0">
                                <thead class="bg-label-danger">
                                    <tr class="text-bold">
                                        <th class="text-danger text-bold">Gross Deductions</th>
                                        <th class="text-danger text-bold text-end">
                                            <i class="ti ti-currency-taka"></i><span>{{ $salary['total_deduction'] }}</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($salary['deductions'] as $deduction)
                                        <tr>
                                            <th class="text-nowrap">{{ $deduction->reason }}</th>
                                            <td class="text-end">
                                                {{ format_number($deduction->total) }}
                                                <sup class="text-bold">TK</sup>
                                            </td>
                                        </tr>
                                    @empty 
                                        <tr>
                                            <th class="text-nowrap text-center text-muted" colspan="2">NO DEDUCTION</th>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="my-4"/>

                <div class="row">
                    <div class="col-md-8 align-self-end">
                        <b class="border-top pt-2 px-2">Accountant Digital Signature</b>
                        <br>
                        <br>
                        @isset ($monthly_salary->payer) 
                            <b class="px-2"><span class="text-muted">Paid By:</span> {{ $monthly_salary->payer->name }}</b>
                        @else
                            <span class="badge bg-primary">PENDING</span>
                        @endisset
                    </div>
                    <div class="col-md-4">
                        <dl class="row mt-1 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <span class="fw-medium mx-2 text-heading">Total Earnings:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                <span>
                                    <i class="ti ti-currency-taka"></i>{{ format_number($salary['total_earning']) }}
                                </span>
                            </dd>
                        </dl>
                        <dl class="row mt-1 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <span class="fw-medium mx-2 text-heading">Total Deductions:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                <span>
                                    <i class="ti ti-minus"></i>
                                    <i class="ti ti-currency-taka"></i>{{ format_number($salary['total_deduction']) }}
                                </span>
                            </dd>
                        </dl>
                        <dl class="row pt-2 mb-1 bg-label-success">
                            <dt class="col-5 mb-2 fw-bold text-nowrap">
                                <span class="fw-bold mx-2 text-heading">Net Payable Salary:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                <span class="fw-bold text-success">
                                    <i class="ti ti-currency-taka"></i>{{ format_number($monthly_salary->total_payable) }}
                                </span>
                            </dd>
                        </dl>
                        <dl class="row">
                            <dd class="col-12 text-end">
                                <small class="text-muted text-capitalize">
                                    <span class="text-dark">{{ spell_number($monthly_salary->total_payable) }}</span> taka only
                                </small>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="card-footer border-top d-flex justify-content-between pt-4">
                <div class="payslip-note pt-2">
                    <u class="fw-medium">Note:</u>
                    <span class="text-capitalize">This is a Electronic Generated Payslip, thus no signature or stamp required. Thank You!</span>
                </div>
                @if ($monthly_salary->status !== 'Paid') 
                    <div class="footer-action">
                        <a href="javascrip:void(0);" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#markAsPaidModal">
                            <i class="ti ti-check me-2"></i>
                            <span>Mark As Paid</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>


{{-- Modals --}}
@if ($monthly_salary->status !== 'Paid')
    @include('administration.accounts.salary.monthly.modals._add_earning')
    @include('administration.accounts.salary.monthly.modals._add_deduction')
    @include('administration.accounts.salary.monthly.modals._mark_as_paid')
@endif

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>
    
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
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
        });
        
        $(document).ready(function () {
            $('.date-time-picker').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i:s',
                defaultDate: new Date()
            }); 
        });
    </script>
@endsection
