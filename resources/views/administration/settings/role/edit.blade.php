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
        .custom-option-basic .custom-option-content {
            padding: 0.8em;
            padding-left: 2.5em;
        }
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
                    <div class="row mb-2">
                        <!-- Permission Summary -->
                        <div class="col-md-8">
                            <div class="alert alert-primary d-flex align-items-center">
                                <span class="tf-icon ti ti-primary-circle me-2"></span>
                                <div class="flex-grow-1">
                                    <strong>Permission Summary:</strong>
                                    <span id="permissionSummary">No permissions selected</span>
                                </div>
                                <div class="permission-stats">
                                    <span class="badge bg-primary me-2" title="Selected Permissions">
                                        <span id="selectedCount">0</span> / <span id="totalCount">{{ $modules->sum(fn($m) => $m->permissions->count()) }}</span>
                                    </span>
                                    <span class="badge bg-success" title="Modules">
                                        <span id="moduleCount">0</span> / {{ $modules->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Current Role Info -->
                        <div class="col-md-4">
                            <div class="alert alert-dark d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    <span class="tf-icon ti ti-info-circle me-2"></span>
                                    <strong>Role:</strong> {{ $role->name }} 
                                </div>
                                <span class="badge bg-primary ms-2" title="Current Role Permissions">{{ $role->permissions->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <!-- Role Basic Information -->
                        <div class="col-md-9">
                            @if ($role->name !== 'Developer' && $role->name !== 'Super Admin')
                                <input type="text" name="name" value="{{ $role->name }}" class="form-control form-control-lg" placeholder="Enter a role name" required />
                            @else
                                <div class="alert alert-danger">
                                    <span class="tf-icon ti ti-lock me-2"></span>
                                    <strong>{{ $role->name }} Role:</strong> This is a system role and cannot be renamed.
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="form-check custom-option custom-option-basic mb-1">
                                <label class="form-check-label custom-option-content" for="selectAllPermissions">
                                    <input class="form-check-input" type="checkbox" id="selectAllPermissions">
                                    <span class="custom-option-header pb-0">
                                        <span class="h6 mb-0">Select All Permissions</span>
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Permission Table -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-bordered">
                                    <thead class="fixed-header bg-label-primary">
                                        <tr>
                                            <th class="text-left">Modules</th>
                                            <th class="text-center">Everything</th>
                                            <th class="text-center">Create</th>
                                            <th class="text-center">Read</th>
                                            <th class="text-center">Update</th>
                                            <th class="text-center">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody class="scrollable-content">
                                        @foreach ($modules as $module)
                                        <tr>
                                            <th>
                                                <div class="d-flex justify-content-between align-items-center text-capitalize">
                                                    <span>
                                                        <span class="tf-icon ti ti-lock me-2"></span>
                                                        {{ $module->name }}
                                                    </span>
                                                    <span class="badge bg-label-primary ms-2">
                                                        {{ $module->permissions->count() }}
                                                    </span>
                                                </div>
                                            </th>
                                            @php
                                                $permissionTypes = ['Everything', 'Create', 'Read', 'Update', 'Delete'];
                                            @endphp
                                            
                                            @foreach($permissionTypes as $permissionType)
                                                @php
                                                    $permission = $module->permissions->filter(function($p) use ($permissionType) {
                                                        return str_contains($p->name, $permissionType);
                                                    })->first();
                                                @endphp
                                                <td class="text-center">
                                                    @if($permission)
                                                        <label class="switch switch-square">
                                                            <input type="checkbox" 
                                                                   class="switch-input {{ $permissionType === 'Everything' ? 'module-everything-checkbox' : 'permission-checkbox' }}" 
                                                                   @if($permissionType !== 'Everything') name="permissions[]" @endif
                                                                   id="permission_{{ $permission->id }}" 
                                                                   value="{{ $permission->id }}" 
                                                                   data-module-id="{{ $module->id }}"
                                                                   @if($permissionType === 'Everything') data-permission-id="{{ $permission->id }}" @endif
                                                                   @if($role->hasPermissionTo($permission)) checked @endif>
                                                            <span class="switch-toggle-slider">
                                                                <span class="switch-on"><i class="ti ti-check"></i></span>
                                                                <span class="switch-off"><i class="ti ti-x"></i></span>
                                                            </span>
                                                        </label>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
            let totalPermissions = {{ $modules->sum(fn($m) => $m->permissions->count()) }};
            let totalModules = {{ $modules->count() }};
            let originalPermissions = [];
            
            // Store original permission state
            $("input[name='permissions[]']:checked").each(function() {
                originalPermissions.push($(this).val());
            });
            
            // Update permission summary
            function updatePermissionSummary() {
                let selectedPermissions = $("input[name='permissions[]']:checked").length;
                let selectedModules = $('tbody tr').filter(function() {
                    return $(this).find("input[name='permissions[]']:checked").length > 0;
                }).length;
                
                $('#selectedCount').text(selectedPermissions);
                $('#moduleCount').text(selectedModules);
                
                if (selectedPermissions === 0) {
                    $('#permissionSummary').text('No permissions selected');
                } else if (selectedPermissions === totalPermissions) {
                    $('#permissionSummary').text('All permissions selected');
                } else {
                    $('#permissionSummary').text(`${selectedPermissions} permissions selected across ${selectedModules} modules`);
                }
                
                // Update module "Everything" checkboxes
                $('tbody tr').each(function() {
                    let moduleId = $(this).find('.module-everything-checkbox').data('module-id');
                    let moduleSelectedCount = $(this).find("input[name='permissions[]']:checked").length;
                    let moduleTotalCount = $(this).find("input[name='permissions[]']").length;
                    
                    // Update module "Everything" checkbox
                    let moduleEverythingCheckbox = $(this).find('.module-everything-checkbox');
                    if (moduleSelectedCount === moduleTotalCount && moduleTotalCount > 0) {
                        moduleEverythingCheckbox.prop('checked', true);
                    } else {
                        moduleEverythingCheckbox.prop('checked', false);
                    }
                });
                
                // Update main "Select All" checkbox
                let anyUnchecked = $("input[name='permissions[]']:not(:checked)").length > 0;
                $("#selectAllPermissions").prop("checked", !anyUnchecked);
            }
            
            // Main "Select All Permissions" checkbox behavior
            $("#selectAllPermissions").click(function () {
                var selectAllChecked = $(this).prop("checked");
                $("input[name='permissions[]']").prop("checked", selectAllChecked);
                $(".module-everything-checkbox").prop("checked", selectAllChecked);
                updatePermissionSummary();
            });
            
            // Module "Everything" checkbox behavior
            $('.module-everything-checkbox').change(function() {
                let moduleId = $(this).data('module-id');
                let moduleRow = $(this).closest('tr');
                let isChecked = $(this).prop('checked');
                
                // Check/uncheck all permissions in this module row
                moduleRow.find("input[name='permissions[]']").prop('checked', isChecked);
                updatePermissionSummary();
            });
            
            // Individual permission checkbox behavior
            $('.permission-checkbox').change(function() {
                updatePermissionSummary();
            });
            
            // Form submission with loading state
            $('form').submit(function(e) {
                let selectedPermissions = $("input[name='permissions[]']:checked").length;
                
                if (selectedPermissions === 0) {
                    e.preventDefault();
                    toastr.error('Please select at least one permission for this role.');
                    return false;
                }
                
                // Check if there are any changes
                let hasChanges = false;
                $("input[name='permissions[]']").each(function() {
                    let permissionId = $(this).val();
                    let isChecked = $(this).prop('checked');
                    let wasOriginallyChecked = originalPermissions.includes(permissionId);
                    
                    if (isChecked !== wasOriginallyChecked) {
                        hasChanges = true;
                        return false; // break the loop
                    }
                });
                
                if (!hasChanges) {
                    e.preventDefault();
                    toastr.info('No changes detected. The role permissions remain the same.');
                    return false;
                }
                
                $('button[type="submit"]').addClass('loading').prop('disabled', true);
            });
            
            // Initialize summary
            updatePermissionSummary();
        });
    </script>
@endsection
