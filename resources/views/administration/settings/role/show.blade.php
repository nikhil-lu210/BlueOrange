@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Role Details'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Role Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.rolepermission.role.index') }}">{{ __('All Roles') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Role Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"><strong>{{ $role->name }}</strong> Role's Details</h5>

                @if (auth()->user()->hasRole('Developer') || ($role->name !== 'Developer' && $role->name !== 'Super Admin'))
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.settings.rolepermission.role.edit', ['role' => $role]) }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit Role
                        </a>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="col-md-12 mb-4">
                    <div class="row">
                        <div class="col-xl-7 col-12">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 mb-2 fw-bold text-nowrap">Role Name:</dt>
                                <dd class="col-sm-8">{{ $role->name }}</dd>

                                <dt class="col-sm-4 mb-2 fw-bold text-nowrap">Role Assigned:</dt>
                                <dd class="col-sm-8">{{ date_time_ago($role->created_at) }}</dd>

                                @if ($role->created_at != $role->updated_at)
                                    <dt class="col-sm-4 mb-2 fw-bold text-nowrap">Last Update:</dt>
                                    <dd class="col-sm-8">{{ date_time_ago($role->updated_at) }}</dd>
                                @endif
                            </dl>
                        </div>
                        <div class="col-xl-5 col-12">
                            <dl class="row mb-0">
                                <dt class="col-sm-4 mb-2 fw-bold text-nowrap">Total Permissions:</dt>
                                <dd class="col-sm-8">{{ $role->permissions()->count() }}</dd>

                                <dt class="col-sm-4 mb-2 fw-bold text-nowrap">Total Users:</dt>
                                <dd class="col-sm-8">{{ $role->users()->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mt-5">
                    <h5 class="text-center"><strong>{{ $role->name }}</strong> Role's Permissions</h5>

                    <div class="col-md-12 mb-4">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <dl class="row mb-0">
                                    @foreach ($permissionModules as $module)
                                        <dt class="col-sm-4 mb-2 fw-bold text-nowrap">{{ $module->name }}:</dt>
                                        <dd class="col-sm-8">
                                            @foreach ($module->permissions as $permission)
                                                <span class="badge bg-label-primary">{{ $permission->name }}</span>
                                            @endforeach
                                        </dd>
                                    @endforeach
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Users of <strong>{{ $role->name }}</strong> Role's</h5>
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
                                <th>Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($role->users as $key => $user)
                                <tr>
                                    <th>#{{ serial($role->users, $key) }}</th>
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
                                    <td>{!! show_status($user->status) !!}</td>
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            //
        });
    </script>
@endsection
