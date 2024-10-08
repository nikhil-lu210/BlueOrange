<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="/assets/" data-template="">
    <head>
        {{-- Meta Starts --}}
        @include('layouts.administration.partials.metas')
        {{-- Meta Ends --}}
        
        <title>{{ config('app.name') }} || Not Authorized</title>
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="{{ asset('Logo/logo_white_01.png') }}" />

        <!-- Start css -->
        @include('layouts.administration.partials.stylesheet')
        <!-- End css -->

        <!-- Page CSS -->
        <!-- Page -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-misc.css') }}" />
    </head>

    <body>
        <!-- Content -->

        <!-- Not Authorized -->
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card invoice-preview-card">
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
                                            <span>Payment Date:</span>
                                            <span class="fw-medium">May 25, 2021</span>
                                        </div>
                                        <div class="pt-1">
                                            <span>Pay To:</span>
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
                                    <h6 class="mb-3 text-bold">Work Summary <small class="text-muted">(September 2024)</small></h6>
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
                                </div>
                            </div>
                        </div>
            
                        <div class="card-footer border-top pt-4">
                            <div class="row">
                                <div class="col-12">
                                    <u class="fw-medium">Note:</u>
                                    <span class="text-capitalize">This is a Electronic Generated Payslip, thus no signature or stamp required. Thank You!</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Not Authorized -->
    </body>
</html>
