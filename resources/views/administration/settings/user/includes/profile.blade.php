@extends('administration.settings.user.show')

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        dt > i,
        dd > span > i {
            margin-top: -4px;
        }
    </style>
@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Basic Information</small>
                <dl class="row mt-3 mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-hash text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">User ID:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->userid }}</span>  
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-user text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Full Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->name }}</span>  
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-user-bolt text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Alias Name:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <span>{{ $user->name }}</span>  
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-qrcode text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">QR Code:</span>
                    </dt>
                    <dd class="col-sm-8">
                        @if ($user->hasMedia('qrcode'))
                            <img src="{{ $user->getFirstMediaUrl('qrcode') }}" alt="{{ $user->name }} QRCODE" class="d-block h-auto ms-0 ms-sm-4" width="150px">
                        @else
                            <a href="{{ route('administration.settings.user.generate.qr.Code', ['user' => $user]) }}" class="btn btn-outline-primary btn-sm confirm-success">Generate QR Code</a>
                        @endif
                    </dd>
                </dl>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Contact Information</small>
                <dl class="row mt-3 mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-phone-call text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Contact No:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="tel:+880 1234 567890" class="text-primary">+880 1234 567890</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-mail text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Email:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="mailto:{{ $user->email }}" class="text-primary">{{ $user->email }}</a>
                    </dd>
                </dl>
                <dl class="row mb-1">
                    <dt class="col-sm-4 fw-medium text-nowrap">
                        <i class="ti ti-brand-skype text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Skype:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <a href="skype:nigel.staffindia?chat" class="text-primary">{{ 'nigel.staffindia' }}</a>
                    </dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        @canany(['User Create', 'User Update'])
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6 class="card-text text-uppercase float-start">Salary Breakdown</h6>
                    <div class="d-inline-block float-end">
                        <a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-primary waves-effect dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end m-0" style="">
                            <a href="{{ route('administration.settings.user.salary.index', ['user' => $user]) }}" class="dropdown-item">
                                <i class="text-primary ti ti-history me-1"></i> 
                                Salary History
                            </a>
                            <a href="{{ route('administration.settings.user.salary.monthly.index', ['user' => $user]) }}" class="dropdown-item">
                                <i class="text-primary ti ti-calendar-time me-1"></i> 
                                Monthly Salary History
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('administration.settings.user.salary.create', ['user' => $user]) }}" class="dropdown-item btn btn-primary waves-effect">
                                <i class="ti ti-plus me-1"></i> 
                                Upgrade Salary
                            </a>
                        </div>
                    </div>
                </div>
                @if ($user->current_salary) 
                    <div class="card-body">
                        <dl class="row mt-3 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-coin"></i>
                                <span class="fw-medium mx-2 text-heading">Basic Salary:</span>
                            </dt>
                            <dd class="col-7">
                                <span><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->basic_salary) }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-coin"></i>
                                <span class="fw-medium mx-2 text-heading">House Benefit:</span>
                            </dt>
                            <dd class="col-7">
                                <span><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->house_benefit) }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-coin"></i>
                                <span class="fw-medium mx-2 text-heading">Transport Allowance:</span>
                            </dt>
                            <dd class="col-7">
                                <span><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->transport_allowance) }}</span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-5 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-coin"></i>
                                <span class="fw-medium mx-2 text-heading">Medical Allowance:</span>
                            </dt>
                            <dd class="col-7">
                                <span><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->medical_allowance) }}</span>
                            </dd>
                        </dl>
                        @if ($user->current_salary->night_shift_allowance) 
                            <dl class="row mt-3 mb-1">
                                <dt class="col-5 mb-2 fw-medium text-nowrap">
                                    <i class="ti ti-coin"></i>
                                    <span class="fw-medium mx-2 text-heading">Night Shift Allowance:</span>
                                </dt>
                                <dd class="col-7">
                                    <span><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->night_shift_allowance) }}</span>
                                </dd>
                            </dl>
                        @endif
                        <dl class="row mt-3 mb-0 text-primary">
                            <dt class="col-5 mb-2 text-nowrap">
                                <i class="ti ti-topology-ring-3"></i>
                                <span class="mx-2 text-bold">Total Salary:</span>
                            </dt>
                            <dd class="col-7">
                                <span class="text-bold"><i class="ti ti-currency-taka"></i>{{ format_number($user->current_salary->total) }}</span>
                            </dd>
                        </dl>
                    </div>
                @endif
            </div>
        @endcanany
    </div>
</div>
<!--/ User Profile Content -->
@endsection