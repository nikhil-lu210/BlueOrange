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
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Click to Update Shift">
                                    <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#updateShift" class="text-primary">
                                        <i class="ti ti-clock"></i>
                                        {{ show_time(optional($user->current_shift)->start_time) }}
                                        <small>to</small>
                                        {{ show_time(optional($user->current_shift)->end_time) }}
                                    </a>
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


{{-- Modal for Shift Update --}}
@canany(['User Create', 'User Update'])
    <div class="modal fade" id="updateShift" tabindex="-1" aria-hidden="true"  data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form action="{{ route('administration.settings.user.shift.update', ['shift' => $user->current_shift, 'user' => $user]) }}" method="post" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="updateShiftTitle">Update Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="start_time" class="form-label">{{ __('Shift Start Time') }} <strong class="text-danger">*</strong></label>
                                <input type="text" id="start_time" name="start_time" value="{{ optional($user->current_shift)->start_time ?? old('start_time') }}" placeholder="HH:MM" class="form-control time-picker @error('start_time') is-invalid @enderror" required/>
                                @error('start_time')
                                    <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="end_time" class="form-label">{{ __('Shift End Time') }} <strong class="text-danger">*</strong></label>
                                <input type="text" id="end_time" name="end_time" value="{{ optional($user->current_shift)->end_time ?? old('end_time') }}" placeholder="HH:MM" class="form-control time-picker @error('end_time') is-invalid @enderror" required/>
                                @error('end_time')
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
                            Update Shift
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
    <!-- Datatable js -->    
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
    <script src="{{asset('assets/js/pages-profile.js')}}"></script>
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
            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true
            }); 
        });
    </script>    
@endsection
