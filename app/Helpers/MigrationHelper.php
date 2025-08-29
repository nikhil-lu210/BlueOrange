<?php

use App\Models\PermissionModule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

if (!function_exists('assign_permission')) {
    /**
     * Create permissions for a module and assign them to a role (optional).
     *
     * @param string $moduleName
     * @param array $permissions
     * @param string|null $roleName
     * @return void
     */
    function assign_permission(string $moduleName, array $permissions = ['Everything','Create','Read','Update','Delete'], string $roleName = 'Developer'): void
    {
        try {
            // Create or get the permission module
            $module = PermissionModule::firstOrCreate(['name' => $moduleName]);

            // Create permissions if they don't exist
            foreach ($permissions as $permission) {
                Permission::firstOrCreate([
                    'permission_module_id' => $module->id,
                    'name' => "{$moduleName} {$permission}",
                ]);
            }

            // Assign to role if role name is given
            if ($roleName) {
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    foreach ($permissions as $permission) {
                        $permissionName = "{$moduleName} {$permission}";
                        if (!$role->hasPermissionTo($permissionName)) {
                            $role->givePermissionTo($permissionName);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::warning("Failed to assign {$moduleName} permissions: " . $e->getMessage());
        }
    }
}

