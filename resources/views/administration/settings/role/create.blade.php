@extends('layouts.administration.app')

@section('meta_tags')
    {{-- External META's --}}
@endsection

@section('page_title', __('Create New Role'))

@section('css_links')
    {{-- External CSS --}}
@endsection

@section('custom_css')
    {{-- Custom CSS --}}
    <style>
        /* Custom CSS Here */
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create New Role') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Role') }}</li>
    <li class="breadcrumb-item active">{{ __('Create New Role') }}</li>
@endsection

@section('content')
<!-- Start row -->
<div class="row justify-content-center">
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
                <form action="{{ route('administration.settings.rolepermission.role.store') }}" method="post" autocomplete="off">
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
                                        <td class="bg-white" colspan="6">
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
                                            <td>
                                                <!-- "Everything" Checkbox for the module -->
                                                <div class="d-flex">
                                                    <div class="form-check me-3 me-lg-5">
                                                        <input class="form-check-input everything-checkbox" type="checkbox" id="select_everything{{ $module->id }}" data-module-id="{{ $module->id }}" />
                                                        <label class="form-check-label" for="select_everything{{ $module->id }}">
                                                            Select Everything
                                                        </label>
                                                    </div>
                                                </div>
                                            </td>
                                            @foreach ($module->permissions as $sl => $permission) 
                                                <td>
                                                    <div class="d-flex">
                                                        <div class="form-check me-3 me-lg-5">
                                                            <input class="form-check-input" type="checkbox" name="permissions[]" id="permission{{ $permission->id }}" value="{{ $permission->id }}" data-module-id="{{ $module->id }}" />
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
    {{-- External Javascript Links --}}
@endsection

@section('custom_script')
    {{-- Custom Javascript --}}
    <script>
        $(document).ready(function () {
            // "Select All Permissions" checkbox behavior
            $("#selectAllPermissions").click(function () {
                var selectAllChecked = $(this).prop("checked");
                $("input[name='permissions[]']").prop("checked", selectAllChecked);
                $(".everything-checkbox").prop("checked", selectAllChecked);
            });
    
            // Individual permission checkbox click behavior
            $("input[name='permissions[]']").click(function () {
                var row = $(this).closest("tr");
                var moduleId = $(this).data("module-id");
    
                var everythingCheckbox = row.find(".everything-checkbox[data-module-id='" + moduleId + "']");
                var permissionsCheckboxes = row.find("input[name='permissions[]'][data-module-id='" + moduleId + "']");
    
                // Handle the "Everything" checkbox based on individual permissions
                if ($(this).prop("checked")) {
                    var allChecked = permissionsCheckboxes.filter(":checked").length === permissionsCheckboxes.length;
                    if (allChecked) {
                        everythingCheckbox.prop("checked", true);
                    }
                } else {
                    everythingCheckbox.prop("checked", false);
                }
    
                // Check the "Select All Permissions" checkbox if all individual permissions are selected
                var anyUnchecked = $("input[name='permissions[]']:not(:checked)").length > 0;
                $("#selectAllPermissions").prop("checked", !anyUnchecked);
            });
    
            // "Everything" checkbox click behavior
            $(".everything-checkbox").click(function () {
                var moduleId = $(this).data("module-id");
                var row = $(this).closest("tr");
                var permissionsCheckboxes = row.find("input[name='permissions[]'][data-module-id='" + moduleId + "']");
    
                // If "Everything" is checked, select all individual permissions for that module
                if ($(this).prop("checked")) {
                    permissionsCheckboxes.prop("checked", true);
                } else {
                    permissionsCheckboxes.prop("checked", false);
                }
    
                // Check the "Select All Permissions" checkbox if all permissions are selected
                var anyUnchecked = $("input[name='permissions[]']:not(:checked)").length > 0;
                $("#selectAllPermissions").prop("checked", !anyUnchecked);
            });
        });
    </script>
@endsection
