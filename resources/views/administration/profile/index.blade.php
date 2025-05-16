@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Profile'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('My Profile') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.my.profile') }}">
            {{ __('My Profile') }}
        </a>
    </li>
    @yield('profile_breadcrumb')
@endsection


@section('content')

<!-- Start row -->
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
                            <p class="fw-bold text-dark mb-1">
                                <span class="text-primary" data-bs-toggle="tooltip" title="Employee ID" data-bs-placement="right">{{ $user->userid }}</span>
                            </p>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Employee Role" data-bs-placement="bottom">
                                    <i class="ti ti-crown"></i>
                                    {{ $user->role->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" title="Team Leader">
                                    <i class="ti ti-user-shield"></i>
                                    @isset ($user->active_team_leader)
                                        {{ $user->active_team_leader->employee->alias_name }}
                                    @else
                                        {{ __('Not Assigned') }}
                                    @endisset
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Working Shift">
                                    <i class="ti ti-clock"></i>
                                    {{ show_time(optional($user->current_shift)->start_time) }}
                                    <small>to</small>
                                    {{ show_time(optional($user->current_shift)->end_time) }}
                                </li>
                            </ul>
                        </div>
                        @can ('User Update')
                            <a href="{{ route('administration.my.profile.edit') }}" class="btn btn-dark btn-icon rounded-pill confirm-danger" data-bs-toggle="tooltip" title="Edit Profile">
                                <i class="ti ti-pencil"></i>
                            </a>
                        @endcan
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
                <a class="nav-link {{ request()->is('my/profile*') ? 'active' : '' }}" href="{{ route('administration.my.profile') }}">
                    <i class="ti-xs ti ti-user-check me-1"></i>
                    Profile
                </a>
            </li>
            @can ('Salary Read')
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('my/salary*') ? 'active' : '' }}" href="{{ route('administration.my.salary.monthly.history') }}">
                        <i class="ti-xs ti ti-currency-taka me-1"></i>
                        Salaries
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

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
    <script src="{{ asset('assets/js/pages-profile.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            //
        });
    </script>
@endsection
