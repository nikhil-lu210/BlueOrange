@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Update Role'))

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
    <b class="text-uppercase">{{ __('Update Role') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.rolepermission.role.index') }}">{{ __('All Roles') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.settings.rolepermission.role.show', ['role' => $role]) }}">{{ $role->name }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Update Role') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Update Role <b class="text-primary">({{ $role->name }})</b></h5>
        
                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.settings.rolepermission.role.show', ['role' => $role]) }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                        Back
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('administration.settings.rolepermission.role.update', ['role' => $role]) }}" method="post" autocomplete="off">
                    @csrf
                    @if ($role->name !== 'Developer' && $role->name !== 'Super Admin') 
                        <div class="col-12 mb-4">
                            <label class="form-label" for="name">Role Name <strong class="text-danger">*</strong></label>
                            <input type="text" name="name" value="{{ $role->name }}" class="form-control" placeholder="Enter a role name" tabindex="-1" required />
                        </div>
                    @endif
                    <div class="col-12">
                        <h5>Role Permissions</h5>
                        <!-- Permission table -->
                        <div class="table-responsive">
                            <table class="table table-flush-spacing">
                                <thead>
                                    <tr>
                                        <td class="text-nowrap fw-medium">
                                            {{ $role->name }} Access 
                                            <i class="ti ti-info-circle" data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system"></i>
                                        </td>
                                        <td class="" colspan="4">
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
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{ $permission->id }}" value="{{ $permission->id }}" {{ $role->hasPermissionTo($permission) ? 'checked' : '' }} />
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
                        <button type="submit" class="btn btn-primary float-end">Update Role</button>
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
