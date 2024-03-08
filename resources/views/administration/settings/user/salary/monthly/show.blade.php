@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Monthly Salary Details'))

@section('css_links')
    {{--  External CSS  --}}
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
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}">
            {{ $user->name }}
        </a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.salary.monthly.index', ['user' => $user]) }}">
            {{ __('Monthly Salary History') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Monthly Salary Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card invoice-preview-card">
            <div class="card-header border-bottom header-elements">
                <h5 class="mb-0"><strong>{{ $user->name }}</strong>'s Monthly Salary Details</h5>
        
                <div class="card-header-elements ms-auto">
                    <button type="button" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-printer ti-xs me-1"></span>
                        Print Invoice
                    </button>
                    <button type="button" class="btn btn-sm btn-dark">
                        <span class="tf-icon ti ti-download ti-xs me-1"></span>
                        Donwload Invoice
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between flex-xl-row flex-md-column flex-sm-row flex-column m-sm-3 m-0">
                    <div class="mb-xl-0 mb-4">
                        <div class="d-flex svg-illustration mb-4 gap-2 align-items-center">
                            <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                                    fill="#7367F0"
                                />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                                <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd" d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                                    fill="#7367F0"
                                />
                            </svg>

                            <span class="app-brand-text fw-bold fs-4"> Vuexy </span>
                        </div>
                        <p class="mb-2">Office 149, 450 South Brand Brooklyn</p>
                        <p class="mb-2">San Diego County, CA 91905, USA</p>
                        <p class="mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p>
                    </div>
                    <div>
                        <h4 class="fw-medium mb-2">INVOICE #86423</h4>
                        <div class="mb-2 pt-1">
                            <span>Date Issues:</span>
                            <span class="fw-medium">April 25, 2021</span>
                        </div>
                        <div class="pt-1">
                            <span>Date Due:</span>
                            <span class="fw-medium">May 25, 2021</span>
                        </div>
                    </div>
                </div>

                <hr class="my-4"/>
            
                <div class="row">
                    <div class="col-md-6 mb-xl-0 mb-md-4 mb-sm-0 mb-4">
                        <h6 class="mb-3">Invoice To:</h6>
                        <p class="mb-1">Thomas shelby</p>
                        <p class="mb-1">Shelby Company Limited</p>
                        <p class="mb-1">Small Heath, B10 0HF, UK</p>
                        <p class="mb-1">718-986-6062</p>
                        <p class="mb-0">peakyFBlinders@gmail.com</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-4">Bill To:</h6>
                        <table>
                            <tbody>
                                <tr>
                                    <td class="pe-4">Total Due:</td>
                                    <td class="fw-medium">$12,110.55</td>
                                </tr>
                                <tr>
                                    <td class="pe-4">Bank name:</td>
                                    <td>American Bank</td>
                                </tr>
                                <tr>
                                    <td class="pe-4">Country:</td>
                                    <td>United States</td>
                                </tr>
                                <tr>
                                    <td class="pe-4">IBAN:</td>
                                    <td>ETD95476213874685</td>
                                </tr>
                                <tr>
                                    <td class="pe-4">SWIFT code:</td>
                                    <td>BR91905</td>
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
                                            <i class="ti ti-currency-taka"></i><span>25000</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-nowrap">Basic Salary</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->basic_salary) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">House Benefit</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->house_benefit) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Transport Allowance</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->transport_allowance) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Medical Allowance</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->medical_allowance) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Night Shift Allowance</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->night_shift_allowance) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Other Allowance</th>
                                        <td class="text-end">
                                            {{ format_number($monthly_salary->salary->other_allowance) }}
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Bonus</th>
                                        <td class="text-end">
                                            25000
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
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
                                            <i class="ti ti-currency-taka"></i><span>2500</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th class="text-nowrap">Basic</th>
                                        <td class="text-end">
                                            25000
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-nowrap">Basic</th>
                                        <td class="text-end">
                                            25000
                                            <sup class="text-bold">TK</sup>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <hr class="my-4"/>
                
                <div class="row">
                    <div class="col-md-8 align-self-end">
                        <b class="border-top pt-2 px-2">Accountant Digital Signature</b>
                    </div>
                    <div class="col-md-4">
                        <dl class="row mt-1 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <span class="fw-medium mx-2 text-heading">Total Earnings:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                <span><i class="ti ti-currency-taka"></i>{{ format_number(25000) }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-1 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <span class="fw-medium mx-2 text-heading">Total Deductions:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                -<span><i class="ti ti-currency-taka"></i>{{ format_number(5000) }}</span>
                            </dd>
                        </dl>
                        <dl class="row pt-2 mb-1 bg-label-primary">
                            <dt class="col-5 mb-2 fw-bold text-nowrap">
                                <span class="fw-bold text-primary mx-2 text-heading">Net Payable Salary:</span>
                            </dt>
                            <dd class="col-7 text-end">
                                <span class="fw-bold text-primary"><i class="ti ti-currency-taka"></i>{{ format_number(20000) }}</span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="card-footer border-top pt-4">
                <div class="row">
                    <div class="col-12">
                        <span class="fw-medium">Note:</span>
                        <span>It was a pleasure working with you and your team. We hope you will keep us in mind for future freelance projects. Thank You!</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Code Here
    </script>    
@endsection
