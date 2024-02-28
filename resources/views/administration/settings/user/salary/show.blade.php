@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Salary History Details'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
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
    <b class="text-uppercase">{{ __('Salary History Details') }}</b>
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
        <a href="{{ route('administration.settings.user.salary.index', ['user' => $user]) }}">
            {{ __('Salary History') }}
        </a>
    </li>
    <li class="breadcrumb-item active">{{ __('Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><strong>{{ $user->name }}</strong>'s Salary History Details</h5>
        
                @canany(['Salary Update'])
                    <div class="card-header-elements ms-auto">
                        <button type="button" data-bs-toggle="modal" data-bs-target="#editSalaryHistory" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Salary History
                        </button>
                    </div>
                @endcanany
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
                                    <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="{{ $user->name }} No Avatar" class="img-fluid">
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
<!-- End row -->

{{-- Modal for Salary Upgrade --}}
@canany(['Salary Create', 'Salary Update'])
    <div class="modal fade" id="editSalaryHistory" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('administration.settings.user.salary.update', ['user' => $user, 'salary' => $salary]) }}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="editSalaryHistoryTitle">
                            <span class="ti ti-edit ti-sm me-1"></span>
                            Update Salary History
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label for="basic_salary" class="form-label">{{ __('Basic Salary') }} <strong class="text-danger">*</strong></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="basic_salary" value="{{ $salary->basic_salary ?? old('basic_salary') }}" class="form-control" placeholder="20,000" required>
                                </div>
                                @error('basic_salary')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="house_benefit" class="form-label">{{ __('House Benefit') }} <strong class="text-danger">*</strong></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="house_benefit" value="{{ $salary->house_benefit ?? old('house_benefit') }}" class="form-control" placeholder="20,000" required>
                                </div>
                                @error('house_benefit')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="transport_allowance" class="form-label">{{ __('Transport Allowance') }} <strong class="text-danger">*</strong></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="transport_allowance" value="{{ $salary->transport_allowance ?? old('transport_allowance') }}" class="form-control" placeholder="20,000" required>
                                </div>
                                @error('transport_allowance')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="medical_allowance" class="form-label">{{ __('Medical Allowance') }} <strong class="text-danger">*</strong></label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="medical_allowance" value="{{ $salary->medical_allowance ?? old('medical_allowance') }}" class="form-control" placeholder="20,000" required>
                                </div>
                                @error('medical_allowance')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="night_shift_allowance" class="form-label">{{ __('Night Shift Allowance') }}</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="night_shift_allowance" value="{{ $salary->night_shift_allowance ?? old('night_shift_allowance') }}" class="form-control" placeholder="20,000">
                                </div>
                                @error('night_shift_allowance')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="other_allowance" class="form-label">{{ __('Other Allowance') }}</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                                    <input type="number" step="0.01" min="0" name="other_allowance" value="{{ $salary->other_allowance ?? old('other_allowance') }}" class="form-control" placeholder="20,000">
                                </div>
                                @error('other_allowance')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            <i class="ti ti-x"></i>
                            Close
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="ti ti-check"></i>
                            Update Salary History
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endcanany

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    {{-- <!-- Page JS --> --}}
    {{-- <script src="{{ asset('assets/js/forms-pickers.js') }}"></script> --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            $('.date-time-picker').flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i'
            }); 
        });
    </script>    
@endsection
