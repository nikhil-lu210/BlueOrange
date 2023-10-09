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
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/page-profile.css')}}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('User Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">{{ __('Users') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('User Details') }}</li>
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
                        <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="{{ $user->name }} No Avatar" class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img">
                    @endif
                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ $user->name }}</h4>
                            <p class="fw-bold text-dark mb-1">ID: <span class="text-primary">{{ $user->userid }}</span></p>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-crown"></i> 
                                    {{ $user->roles[0]->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-calendar"></i> 
                                    {{ show_date($user->created_at) }}
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('administration.settings.user.edit', ['user' => $user]) }}" class="btn btn-primary waves-effect waves-light">
                            <i class="ti ti-pencil me-1"></i>
                            Edit User 
                        </a>
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
                <a class="nav-link {{ request()->is('settings/user/show/profile*') ? 'active' : '' }}" href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}">
                    <i class="ti-xs ti ti-user-check me-1"></i> 
                    Profile
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/user/show/attendance*') ? 'active' : '' }}" href="{{ route('administration.settings.user.show.attendance', ['user' => $user]) }}">
                    <i class="ti-xs ti ti-clock-dollar me-1"></i> 
                    Attendance
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/user/show/break*') ? 'active' : '' }}" href="{{ route('administration.settings.user.show.break', ['user' => $user]) }}">
                    <i class="ti-xs ti ti-hourglass-empty me-1"></i> 
                    Breaks
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->is('settings/user/show/task*') ? 'active' : '' }}" href="#">
                    <i class="ti-xs ti ti-subtask me-1"></i> 
                    Tasks
                </a>
            </li>
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
    <script src="{{asset('assets/js/pages-profile.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // 
        });
    </script>    
@endsection
