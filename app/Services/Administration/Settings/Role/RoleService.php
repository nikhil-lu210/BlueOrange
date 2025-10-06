<?php

namespace App\Services\Administration\Settings\Role;

use Exception;
use App\Models\PermissionModule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    private const SYSTEM_ROLES = ['Developer', 'Super Admin'];

    /**
     * Get all roles with user statistics
     */
    public function getAllRolesWithStats()
    {
        return Role::with([
            'users' => fn($query) => $query->whereStatus('Active'),
            'permissions'
        ])
        ->withCount(['users as active_users_count' => fn($query) => $query->whereStatus('Active')])
        ->get();
    }

    /**
     * Get all permission modules with their permissions
     */
    public function getPermissionModules()
    {
        return PermissionModule::with(['permissions'])
            ->get()
            ->sortBy('name');
    }

    /**
     * Get permission modules that have permissions assigned to the given role
     */
    public function getRolePermissionModules(Role $role)
    {
        return PermissionModule::whereHas('permissions.roles', fn($query) => $query->where('name', $role->name))
            ->with(['permissions' => fn($query) => $query->whereHas('roles', fn($roleQuery) => $roleQuery->where('name', $role->name))])
            ->get()
            ->sortBy('name');
    }

    /**
     * Create a new role with permissions
     */
    public function createRole(array $data): Role
    {
        return DB::transaction(function() use ($data) {
            $role = Role::create(['name' => $data['name']]);
            $this->syncRolePermissions($role, $data['permissions'] ?? []);
            return $role;
        }, 5);
    }

    /**
     * Update an existing role
     */
    public function updateRole(Role $role, array $data): Role
    {
        return DB::transaction(function() use ($role, $data) {
            $this->updateRoleName($role, $data);
            $this->syncRolePermissions($role, $data['permissions'] ?? []);
            return $role->fresh();
        }, 5);
    }

    /**
     * Check if the role is a system role
     */
    public function isSystemRole(Role $role): bool
    {
        return in_array($role->name, self::SYSTEM_ROLES);
    }


    /**
     * Get role statistics for display
     */
    public function getRoleStatistics(Role $role): array
    {
        return [
            'permission_count' => $role->permissions->count(),
            'user_count' => $role->users()->count(),
            'active_user_count' => $role->users()->whereStatus('Active')->count(),
        ];
    }

    /**
     * Generate success message for role operations
     */
    public function generateSuccessMessage(string $action, Role $role, int $permissionCount): string
    {
        return "Role '{$role->name}' has been {$action} successfully with {$permissionCount} permissions.";
    }

    /**
     * Generate update success message with permission changes
     */
    public function generateUpdateSuccessMessage(Role $role, int $originalCount, int $newCount): string
    {
        $changes = $newCount - $originalCount;
        $message = "Role '{$role->name}' has been updated successfully. ";
        
        if ($changes > 0) {
            $message .= "Added {$changes} permissions. ";
        } elseif ($changes < 0) {
            $message .= "Removed " . abs($changes) . " permissions. ";
        } else {
            $message .= "Permission count remains the same. ";
        }
        
        $message .= "Total permissions: {$newCount}.";
        return $message;
    }

    /**
     * Generate error message for role operations
     */
    public function generateErrorMessage(string $action, Exception $e): string
    {
        return "Failed to {$action} role: " . $e->getMessage();
    }

    // ==================== PRIVATE METHODS ====================

    /**
     * Update role name if allowed
     */
    private function updateRoleName(Role $role, array $data): void
    {
        if (isset($data['name']) && $data['name'] && !$this->isSystemRole($role)) {
            $role->update([
                'name' => $data['name'],
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Sync permissions for a role
     */
    private function syncRolePermissions(Role $role, array $permissionIds): void
    {
        $this->validatePermissions($permissionIds);
        $permissions = Permission::whereIn('id', $permissionIds)->get();
        $role->syncPermissions($permissions);
    }

    /**
     * Validate permission IDs
     */
    private function validatePermissions(array $permissionIds): void
    {
        if (empty($permissionIds)) {
            throw new Exception('At least one permission must be selected for the role.');
        }

        $permissions = Permission::whereIn('id', $permissionIds)->get();
        
        if ($permissions->count() !== count($permissionIds)) {
            throw new Exception('Some selected permissions are invalid.');
        }
    }
}
