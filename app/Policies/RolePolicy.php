<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * System roles that have special restrictions
     */
    private const SYSTEM_ROLES = ['Developer', 'Super Admin'];

    /**
     * Handle all checks before running specific ability methods.
     */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->hasPermissionTo('Role Everything') || $user->getAllPermissions()->contains('name', 'Role Everything')) {
            return Response::allow();
        }

        return null; // continue with normal checks
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return ($user->hasPermissionTo('Role Read') || $user->getAllPermissions()->contains('name', 'Role Read'))
            ? Response::allow()
            : Response::deny('You do not have permission to view roles.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): Response
    {
        return ($user->hasPermissionTo('Role Read') || $user->getAllPermissions()->contains('name', 'Role Read'))
            ? Response::allow()
            : Response::deny('You do not have permission to view this role.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return ($user->hasPermissionTo('Role Create') || $user->getAllPermissions()->contains('name', 'Role Create'))
            ? Response::allow()
            : Response::deny('You do not have permission to create roles.');
    }

    /**
     * Determine whether the user can update the model.
     * Special logic for system roles, otherwise uses permission-based authorization
     */
    public function update(User $user, Role $role): Response
    {
        // System roles have special restrictions
        if ($this->isSystemRole($role)) {
            if ($role->name === 'Developer') {
                // Only Developer can modify Developer role
                return $user->hasRole('Developer')
                    ? Response::allow()
                    : Response::deny('Only a Developer can modify the Developer role.');
            }
            
            if ($role->name === 'Super Admin') {
                // Developer or Super Admin can modify Super Admin role
                return $user->hasAnyRole(['Developer', 'Super Admin'])
                    ? Response::allow()
                    : Response::deny('Only a Developer or Super Admin can modify the Super Admin role.');
            }
        }
        
        // Regular roles use permission-based authorization
        return ($user->hasPermissionTo('Role Update') || $user->getAllPermissions()->contains('name', 'Role Update'))
            ? Response::allow()
            : Response::deny('You do not have permission to update this role.');
    }

    /**
     * Determine whether the user can delete the model.
     * System roles cannot be deleted, regular roles use permission-based authorization
     */
    public function delete(User $user, Role $role): Response
    {
        // System roles cannot be deleted
        if ($this->isSystemRole($role)) {
            return Response::deny('System roles cannot be deleted.');
        }
        
        // Regular roles use permission-based authorization
        return ($user->hasPermissionTo('Role Delete') || $user->getAllPermissions()->contains('name', 'Role Delete'))
            ? Response::allow()
            : Response::deny('You do not have permission to delete this role.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): Response
    {
        return ($user->hasPermissionTo('Role Update') || $user->getAllPermissions()->contains('name', 'Role Update'))
            ? Response::allow()
            : Response::deny('You do not have permission to restore this role.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): Response
    {
        return ($user->hasPermissionTo('Role Delete') || $user->getAllPermissions()->contains('name', 'Role Delete'))
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete this role.');
    }

    /**
     * Check if the role is a system role
     */
    private function isSystemRole(Role $role): bool
    {
        return in_array($role->name, self::SYSTEM_ROLES);
    }
}
