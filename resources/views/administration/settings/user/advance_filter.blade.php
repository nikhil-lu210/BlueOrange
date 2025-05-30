@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('User Management'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
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
    <b class="text-uppercase">{{ __('Users Advance Filter') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('User Management') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.user.index') }}">{{ __('All Users') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Users Advance Filter') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.settings.user.advance_filter.index') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="team_leader_id" class="form-label">{{ __('Select Team Leader') }}</label>
                            <select name="team_leader_id" id="team_leader_id" class="select2 form-select @error('team_leader_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->team_leader_id) ? 'selected' : '' }}>{{ __('Select Team Leader') }}</option>
                                @foreach ($teamLeaders as $leader)
                                    <option value="{{ $leader->id }}" {{ $leader->id == request()->team_leader_id ? 'selected' : '' }}>
                                        {{ get_employee_name($leader) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_leader_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="role_id" class="form-label">Select Role</label>
                            <select name="role_id" id="role_id" class="select2 form-select @error('role_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->role_id) ? 'selected' : '' }}>Select Role</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}" {{ $role->id == request()->role_id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="start_time" class="form-label">{{ __('Shift Start & End Time') }}</label>
                            <div class="input-group">
                                <input type="text" id="start_time" name="start_time" value="{{ old('start_time', request()->start_time) }}" placeholder="HH:MM" class="form-control time-picker @error('start_time') is-invalid @enderror" />
                                <small class="input-group-text text-muted text-uppercase fs-tiny">To</small>
                                <input type="text" id="end_time" name="end_time" value="{{ old('end_time', request()->end_time) }}" placeholder="HH:MM" class="form-control time-picker @error('end_time') is-invalid @enderror" />
                            </div>
                            @error('start_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                            @error('end_time')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-2">
                            <label for="status" class="form-label">Select Task Status</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>Select Status</option>
                                <option value="Active" {{ request()->status == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ request()->status == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="Fired" {{ request()->status == 'Fired' ? 'selected' : '' }}>Fired</option>
                                <option value="Resigned" {{ request()->status == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->role_id || request()->status)
                            <a href="{{ route('administration.settings.user.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Users
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>All</span>
                    <span>{{ request()->status ?? 'Active' }}</span>
                    <span>{{ request()->role_id ? show_plural(show_role(request()->role_id)) : 'Users' }}</span>
                </h5>

                @can ('User Create')
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.settings.user.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create New User
                        </a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Email & Shift</th>
                                <th class="text-center">Religion, Gender & Blood Group</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $key => $user)
                                <tr>
                                    <th>#{{ serial($users, $key) }}</th>
                                    <th>
                                        <b class="text-primary fs-5">{{ $user->userid }}</b>
                                        <br>
                                        <small>
                                            <a href="{{ route('administration.settings.user.user_interaction.index', ['user' => $user]) }}" target="_blank" class="mb-1 text-capitalize text-bold text-dark" title="Team Leader">
                                                @isset ($user->active_team_leader)
                                                    {{ $user->active_team_leader->employee->alias_name }}
                                                @else
                                                    {{ __('Not Assigned') }}
                                                @endisset
                                            </a>
                                        </small>
                                    </th>
                                    <td>
                                        {!! show_user_name_and_avatar($user) !!}
                                    </td>
                                    <td>
                                        <a href="mailto:{{ optional($user->employee)->official_email }}" class="mb-1 text-bold" title="Official Email">
                                            {{ optional($user->employee)->official_email }}
                                        </a>
                                        <br>
                                        <b class="text-dark" title="Current Working Shift">
                                            {{ show_time(optional($user->current_shift)->start_time). ' to '.show_time(optional($user->current_shift)->end_time) }}
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <b class="text-bold text-dark">{{ optional(optional($user->employee)->religion)->name }}</b>
                                        <br>
                                        <small class="text-muted">{{ optional($user->employee)->gender }}</small>
                                        <br>
                                        <small class="text-muted">{{ optional($user->employee)->blood_group }}</small>
                                    </td>
                                    <td class="text-center">
                                        @canany (['User Update', 'User Delete'])
                                            <div class="d-inline-block">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="text-primary ti ti-dots-vertical"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end m-0" style="">
                                                    @can ('User Update')
                                                        <a href="{{ route('administration.settings.user.edit', ['user' => $user]) }}" class="dropdown-item">
                                                            <i class="text-primary ti ti-pencil"></i>
                                                            Edit
                                                        </a>
                                                    @endcan
                                                    @can ('User Delete')
                                                        <div class="dropdown-divider"></div>
                                                        <a href="{{ route('administration.settings.user.destroy', ['user' => $user]) }}" class="dropdown-item text-danger delete-record confirm-danger">
                                                            <i class="ti ti-trash"></i>
                                                            Delete
                                                        </a>
                                                    @endcan
                                                </div>
                                            </div>
                                        @endcanany
                                        <a href="{{ route('administration.settings.user.show.profile', ['user' => $user]) }}" class="btn btn-sm btn-icon btn-primary item-edit" data-bs-toggle="tooltip" title="Show Details">
                                            <i class="ti ti-info-hexagon"></i>
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
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
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
        $(document).ready(function () {
            $('.time-picker').flatpickr({
                enableTime: true,
                noCalendar: true
            });
        });
    </script>
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection
