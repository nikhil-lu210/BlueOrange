<?php

namespace App\Models\User\Accessors;

use App\Models\User;
use App\Models\Salary;
use App\Models\EmployeeShift;
use App\Models\Leave\LeaveAllowed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

trait UserAccessors
{
    /**
     * Get the first role of the user.
     * Uses caching to prevent duplicate queries.
     *
     * @return \Spatie\Permission\Models\Role|null
     * The first role assigned to the user or null if no roles are assigned.
     */
    public function getRoleAttribute(): ?Role
    {
        // Use a static cache to prevent duplicate queries
        static $roleCache = [];

        // Create a unique key for this user
        $cacheKey = 'user_role_' . $this->id;

        // Check if we already have the role in the cache
        if (isset($roleCache[$cacheKey])) {
            return $roleCache[$cacheKey];
        }

        // Get the first role and cache it
        $role = $this->roles->first();
        $roleCache[$cacheKey] = $role;

        return $role;
    }

    /**
     * Get the fullname with alias_name of the user.
     */
    public function getFullNameAttribute(): string
    {
        $fullName = $this->name. ' ('. $this->employee->alias_name. ')';
        return $fullName;
    }

    /**
     * Get the alias_name of the user.
     */
    public function getAliasNameAttribute(): string
    {
        return $this->employee->alias_name;
    }

    /**
     * Get the currently active employee shift.
     *
     * @return EmployeeShift|null
     */
    public function getCurrentShiftAttribute()
    {
        return $this->employee_shifts()
            ->where('status', 'active')
            ->latest('created_at')
            ->first();
    }

    /**
     * Get the active allowed leave.
     *
     * @return LeaveAllowed|null
     */
    public function getAllowedLeaveAttribute()
    {
        // Retrieve the first active leave_allowed entry for the user.
        return $this->leave_alloweds()
            ->where('is_active', true)
            ->first();
    }

    /**
     * Get the currently active salary.
     *
     * @return Salary|null
     */
    public function getCurrentSalaryAttribute()
    {
        return $this->salaries()
            ->where('status', 'active')
            ->latest('created_at')
            ->first();
    }


    /**
     * Get the currently active team leader for the user.
     *
     * @return User|null
     */
    public function getActiveTeamLeaderAttribute()
    {
        return $this->employee_team_leaders()
            ->wherePivot('is_active', true)
            ->first();
    }

    /**
     * Get all users the current user has interacted with.
     *
     * Combines users the current user has interacted with and
     * users who have interacted with the current user.
     *
     * @return Collection
     */
    public function getUserInteractionsAttribute()
    {
        try {
            // Use a simple, direct approach without caching to avoid memory issues
            $userIds = collect();
            
            // Get users this user has interacted with
            $interactedIds = DB::table('user_interactions')
                ->where('user_id', $this->id)
                ->pluck('interacted_user_id');
            
            // Get users who have interacted with this user
            $interactingIds = DB::table('user_interactions')
                ->where('interacted_user_id', $this->id)
                ->pluck('user_id');
            
            // Combine and get unique IDs
            $allIds = $interactedIds->merge($interactingIds)->unique()->filter();
            
            // If no interactions, return just the current user
            if ($allIds->isEmpty()) {
                return collect([$this]);
            }
            
            // Get users with a simple query
            $users = User::whereIn('id', $allIds)
                ->whereNull('deleted_at')
                ->get();
            
            // Add the current user if not already included
            if (!$users->contains('id', $this->id)) {
                $users->push($this);
            }
            
            return $users;
        } catch (\Exception $e) {
            // Log the error and return a fallback
            \Log::error('Error in getUserInteractionsAttribute: ' . $e->getMessage());
            return collect([$this]);
        }
    }
}
