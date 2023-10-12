@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Roles'))

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
    .more-user-avatar {
        background-color: #dddddd;
        border-radius: 50px;
        text-align: center;
        padding-top: 5px;
        border: 1px solid #333333;
    }
    .more-user-avatar small {
        font-size: 12px;
        color: #333333;
        font-weight: bold;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Roles') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
    <li class="breadcrumb-item active">{{ __('All Roles') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row g-4">
    @foreach ($roles as $role) 
        <div class="col-xl-4 col-lg-6 col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-normal mb-2">Total <strong>{{ $role->users()->count() }}</strong> Users</h6>
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            @foreach ($role->users->take(5) as $user)
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar avatar-sm pull-up">
                                    @if ($user->hasMedia('avatar'))
                                        <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                    @else
                                        <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
                                    @endif
                                </li>
                            @endforeach
                            @if ($role->users->count() > 5)
                                <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $role->users->count() - 5 }} More" class="avatar avatar-sm pull-up more-user-avatar">
                                    <small>{{ $role->users->count() - 5 }}+</small>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="d-flex justify-content-between align-items-end mt-1">
                        <div class="role-heading">
                            <h4 class="mb-1">{{ $role->name }}</h4>
                            <span class="role-edit-modal">
                                <span>Total Permissions: <strong>{{ $role->permissions->count() }}</strong></span>
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('administration.settings.rolepermission.role.edit', ['role' => $role]) }}" class="text-muted" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Edit Role">
                                <i class="ti ti-edit ti-md text-info"></i>
                            </a>
                            <a href="{{ route('administration.settings.rolepermission.role.show', ['role' => $role]) }}" class="text-muted" data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Show Role Details">
                                <i class="ti ti-info-hexagon ti-md text-primary"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card h-100">
            <div class="row h-100">
                <div class="col-sm-5">
                    <div class="d-flex align-items-end h-100 justify-content-center mt-sm-0 mt-3">
                        <img src="{{ asset('assets/img/illustrations/add-new-roles.png') }}" class="img-fluid mt-sm-4 mt-md-0" alt="add-new-roles" width="83" />
                    </div>
                </div>
                <div class="col-sm-7">
                    <div class="card-body text-sm-end text-center ps-sm-0">
                        <a href="{{ route('administration.settings.rolepermission.role.create') }}" class="btn btn-primary mb-2 text-nowrap add-new-role">
                            Add New Role
                        </a>
                        <p class="mb-0 mt-1">Add role, if it does not exist</p>
                    </div>
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
        // Custom Script Here
    </script>
@endsection
