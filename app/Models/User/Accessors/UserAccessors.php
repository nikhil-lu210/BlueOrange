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
     *
     * @return \Spatie\Permission\Models\Role|null
     * The first role assigned to the user or null if no roles are assigned.
     */
    public function getRoleAttribute(): ?Role
    {
        return $this->roles->first();
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
        // Use a static cache to prevent duplicate queries
        static $cache = [];

        // Create a unique key for this user
        $cacheKey = 'user_interactions_' . $this->id;

        // Check if we already have the interactions in the cache
        if (isset($cache[$cacheKey])) {
            return $cache[$cacheKey];
        }

        // Optimize by using a single query with UNION
        $interactedUsers = DB::table('users')
            ->select('users.*')
            ->join('user_interactions', 'users.id', '=', 'user_interactions.interacted_user_id')
            ->where('user_interactions.user_id', $this->id)
            ->whereNull('users.deleted_at');

        $interactingUsers = DB::table('users')
            ->select('users.*')
            ->join('user_interactions', 'users.id', '=', 'user_interactions.user_id')
            ->where('user_interactions.interacted_user_id', $this->id)
            ->whereNull('users.deleted_at')
            ->union($interactedUsers);

        // Get the results as a collection of User models
        $userIds = $interactingUsers->pluck('id')->unique();
        $users = User::whereIn('id', $userIds)->get();

        // Add the current user
        $result = $users->push($this)->unique('id');

        // Cache the result
        $cache[$cacheKey] = $result;

        return $result;
    }
}
