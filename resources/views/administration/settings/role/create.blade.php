@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Create New Permission'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Create New Permission') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Permission') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Create New Role</h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.rolepermission.role.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Roles
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="#" method="post" autocomplete="off">
                    @csrf
                    <div class="col-12 mb-4">
                        <label class="form-label" for="name">Role Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter a role name" tabindex="-1" />
                    </div>
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <thead>
                                    <tr>
                                        <td class="bg-white text-nowrap fw-medium">
                                            Superadmin Access 
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                        <td class="bg-white" colspan="4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="selectAllPermissions" />
                                                <label class="form-check-label" for="selectAllPermissions">
                                                    Select All Permissions
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($modules as $key => $module) 
                                        <tr>
                                            <td class="text-nowrap fw-medium">{{ $module->name }}</td>
                                            @foreach ($module->permissions as $sl => $permission) 
                                            <td>
                                                <div class="d-flex">
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{ $permission->id }}" />
                                                        <label class="form-check-label" for="permission{{ $permission->id }}">
                                                            {{ $permission->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Permission table -->
                    </div>
                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary float-end">Add New Role</button>
                    </div>
                </form>
                <!--/ Add role form -->
            </div>            
        </div>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // When the "Select All Permissions" checkbox is clicked
            $("#selectAllPermissions").click(function () {
                // Get the state of the "Select All Permissions" checkbox
                var selectAllChecked = $(this).prop("checked");
    
                // Set the state of all other permission checkboxes to match
                $("input[name='permissions[]']").prop("checked", selectAllChecked);
            });
    
            // When any permission checkbox is clicked
            $("input[name='permissions[]']").click(function () {
                // Check if any permission checkbox is unchecked
                var anyUnchecked = $("input[name='permissions[]']:not(:checked)").length > 0;
    
                // Update the state of "Select All Permissions" accordingly
                $("#selectAllPermissions").prop("checked", !anyUnchecked);
            });
        });
    </script>    
@endsection
