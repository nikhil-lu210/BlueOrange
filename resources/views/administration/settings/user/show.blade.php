@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('User Details'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
    {{-- <!-- Vendors CSS --> --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type="number"] {
        -moz-appearance: textfield;
    }
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
    <b class="text-uppercase">{{ __('User Details') }}</b>
@endsection

{{-- {{ dd($user) }} --}}
@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ get_employee_name($user) }}</li>
@endsection


@section('content')

<!-- Start row -->
@php
    switch ($user->status) {
        case 'Active':
            $statusColor = 'success';
            break;

        case 'Fired':
            $statusColor = 'danger';
            break;


        case 'Resigned':
            $statusColor = 'warning';
            break;

        default:
            $statusColor = 'dark';
            break;
    }
@endphp
<!-- Header -->
<div class="row justify-content-center mt-5">
    <div class="col-md-12 mt-4">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    @if ($user->hasMedia('avatar'))
                        <img src="{{ $user->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ $user->name }} Avatar" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                    @else
                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $user->name }} No Avatar" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                    @endif
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ get_employee_name($user) }}</h4>
                            <p class="fw-bold text-dark mb-1">ID: <span class="text-primary">{{ $user->userid }}</span></p>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-crown"></i>
                                    {{ $user->role->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" title="Joining Date">
                                    <i class="ti ti-calendar"></i>
                                    {{ show_date($user->employee->joining_date) }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" title="Click to Update Shift">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateShift" class="text-primary">
                                        <i class="ti ti-clock"></i>
                                        {{ show_time(optional($user->current_shift)->start_time) }}
                                        <small>to</small>
                                        {{ show_time(optional($user->current_shift)->end_time) }}
                                    </a>
                                </li>
                                <li class="list-inline-item d-flex gap-1" title="{{ __('Click To Update User Status') }}">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateStatusModal" class="text-bold text-{{ $statusColor }}">
                                        <i class="ti ti-activity"></i>
                                        {{ $user->status }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="actions">
                            @canany (['User Everything', 'User Update'])
                                <a href="{{ route('administration.settings.user.edit', ['user' => $user]) }}" class="btn btn-primary waves-effect waves-light">
                                    <i class="ti ti-pencil me-1"></i>
                                    Edit User
                                </a>
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updatePasswordModal" class="btn btn-warning waves-effect waves-light">
                                    <i class="ti ti-lock-cog me-1"></i>
                                    Update Password
                                </a>
                            @endcanany
                            @hasanyrole(['Developer', 'Super Admin'])
                                <a href="{{ route('custom_auth.impersonate.login', ['user' => $user]) }}" class="btn btn-dark waves-effect waves-light confirm-warning">
                                    <i class="ti ti-lock me-1"></i>
                                    Login As {{ $user->alias_name }}
                                </a>
                            @endhasanyrole
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ Header -->

<!-- Navbar pills -->
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-pills flex-column flex-sm-row mb-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/user/show/*/profile*') ? 'active' : '' }}" href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}">
                    <i class="ti-xs ti ti-user-check me-1"></i>
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/user/show/*/attendance*') ? 'active' : '' }}" href="{{ route('administration.settings.user.show.attendance', ['user' => $user]) }}">
                    <i class="ti-xs ti ti-clock-dollar me-1"></i>
                    Attendance
                </a>
            </li>
            @can ('User Interaction Read')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings/user/show/*/user_interaction*') ? 'active' : '' }}" href="{{ route('administration.settings.user.user_interaction.index', ['user' => $user]) }}">
                        <i class="ti-xs ti ti-users-group me-1"></i>
                        User Interactions
                    </a>
                </li>
            @endcan
            @can ('Leave Allowed Read')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('settings/user/show/*/allowed_leaves*') ? 'active' : '' }}" href="{{ route('administration.settings.user.leave_allowed.index', ['user' => $user]) }}">
                        <i class="ti-xs ti ti-calendar-x me-1"></i>
                        Allowed Leaves
                    </a>
                </li>
            @endcan
        </ul>
    </div>
</div>
<!--/ Navbar pills -->

<!-- User Profile Content -->
@yield('profile_content')
<!--/ User Profile Content -->
<!-- End row -->


{{-- Modal for Shift Update --}}
@canany(['User Create', 'User Update'])
    @include('administration.settings.user.modals.user_shift_update_modal')

    @include('administration.settings.user.modals.user_status_update_modal')

    @include('administration.settings.user.modals.user_password_update_modal')
@endcanany

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/pages-profile.js') }}"></script>
    {{-- <!-- Vendors JS --> --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>


    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true
            });
        });
    </script>
@endsection
