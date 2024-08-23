@extends('administration.profile.index')

@section('profile_breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.my.salary.index') }}">{{ __('Base Salary History') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Base Salary Details') }}</li>
@endsection

@section('profile_content')

<!-- User Profile Content -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Base Salary Details</h5>
        
                @if ($salary->status === 'Active' && $salary->monthly_salaries->count() == 0) 
                    @canany(['Salary Update'])
                        <div class="card-header-elements ms-auto">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#editSalaryHistory" class="btn btn-sm btn-primary">
                                <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                                Edit Salary History
                            </button>
                        </div>
                    @endcanany
                @endif
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-4">
                        <div class="card">
                          <div class="card-body text-center">
                            <div class="rounded-3 text-center mb-3">
                                @if ($user->hasMedia('avatar'))
                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $user->name }} Avatar" class="img-fluid rounded-3" width="100%">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $user->name }} No Avatar" class="img-fluid">
                                @endif
                            </div>
                            <h6 class="mb-1 text-center">{{ show_date($salary->implemented_from) }}</h6>
                            @isset ($salary->implemented_to)
                                <h6 class="text-center text-bold mb-1 text-lowercase">To</h6>
                                <h6 class="mb-1 text-center">{{ show_date($salary->implemented_to) }}</h6>
                            @else
                                <h6 class="mb-1 text-center text-success"><span>RUNNING</span></h6>
                            @endisset
                            <h4 class="mb-0 mt-2 text-center">
                                <b>
                                    <span class="text-primary">
                                        <i class="ti ti-currency-taka" style="margin-top: -4px; font-size: 24px;"></i>{{ format_number($salary->total) }}
                                    </span>
                                </b>
                            </h4>
                          </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="card mb-4">
                            <div class="card-header pb-0">
                                <h6 class="card-text text-uppercase float-start">Salary History Details</h6>
                            </div>
                            <div class="card-body">
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-event text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Implemented From:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span>{{ show_date($salary->implemented_from) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-off text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Implemented To:</span>
                                    </dt>
                                    <dd class="col-7">
                                        @isset ($salary->implemented_to)
                                            <span>{{ show_date($salary->implemented_to) }}</span>
                                        @else
                                            <span class="text-success">Running</span>
                                        @endisset
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-heart text-heading"></i>
                                        <span class="fw-medium mx-2 text-heading">Total Implementation:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span>{{ total_day($salary->implemented_from, $salary->implemented_to) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-coin"></i>
                                        <span class="fw-medium mx-2 text-heading">Basic Salary:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->basic_salary) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-coin"></i>
                                        <span class="fw-medium mx-2 text-heading">House Benefit:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->house_benefit) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-coin"></i>
                                        <span class="fw-medium mx-2 text-heading">Transport Allowance:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->transport_allowance) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-5 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-coin"></i>
                                        <span class="fw-medium mx-2 text-heading">Medical Allowance:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->medical_allowance) }}</span>
                                    </dd>
                                </dl>
                                @if ($salary->night_shift_allowance) 
                                    <dl class="row mt-3 mb-1">
                                        <dt class="col-5 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-coin"></i>
                                            <span class="fw-medium mx-2 text-heading">Night Shift Allowance:</span>
                                        </dt>
                                        <dd class="col-7">
                                            <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->night_shift_allowance) }}</span>
                                        </dd>
                                    </dl>
                                @endif
                                @if ($salary->other_allowance) 
                                    <dl class="row mt-3 mb-1">
                                        <dt class="col-5 mb-2 fw-medium text-nowrap">
                                            <i class="ti ti-coin"></i>
                                            <span class="fw-medium mx-2 text-heading">Other Allowance:</span>
                                        </dt>
                                        <dd class="col-7">
                                            <span><i class="ti ti-currency-taka"></i>{{ format_number($salary->other_allowance) }}</span>
                                        </dd>
                                    </dl>
                                @endif
                                <dl class="row mt-3 mb-0 text-primary">
                                    <dt class="col-5 mb-2 text-nowrap">
                                        <i class="ti ti-topology-ring-3"></i>
                                        <span class="mx-2 text-bold">Total Salary:</span>
                                    </dt>
                                    <dd class="col-7">
                                        <span class="text-bold"><i class="ti ti-currency-taka"></i>{{ format_number($salary->total) }}</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>

{{-- Monthly Salaries under this salary --}}
@if ($salary->monthly_salaries->count() > 0) 
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="mb-0">
                        <strong>{{ $user->name }}</strong>'s Monthly Salaries for 
                        <span class="text-bold">{{ format_number($salary->total) }}<sup>TK</sup></span>
                    </h5>
                </div>
                <div class="card-body">
                    <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Salary Proccessed</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($salary->monthly_salaries as $key => $monthlySalary) 
                                <tr>
                                    <th>#{{ serial($salary->monthly_salaries, $key) }}</th>
                                    <td>{{ show_date_time($monthlySalary->created_at) }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-label-{{ $monthlySalary->status == 'Paid' ? 'success' : 'primary' }}">{{ $monthlySalary->status }}</span>
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-icon confirm-success" data-bs-toggle="tooltip" title="Download Invoice">
                                            <i class="text-dark ti ti-download"></i>
                                        </a>
                                        <a href="{{ route('administration.my.salary.monthly.history.show', ['monthly_salary' => $monthlySalary]) }}" class="btn btn-sm btn-icon" data-bs-toggle="tooltip" title="Show Details">
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
@endif
<!--/ User Profile Content -->
@endsection