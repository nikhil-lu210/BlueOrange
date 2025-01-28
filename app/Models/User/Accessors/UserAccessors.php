<?php

namespace App\Models\User\Accessors;

use App\Models\User;
use App\Models\Salary;
use App\Models\EmployeeShift;
use App\Models\Leave\LeaveAllowed;
use Illuminate\Support\Collection;

trait UserAccessors
{
    /**
     * Get the first role of the user.
     */
    public function getRoleAttribute()
    {
        return $this->roles->first();
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
        // Fetch the users this user has interacted with
        $interactedUsers = $this->interacted_users()->get();

        // Fetch the users who have interacted with this user
        $interactingUsers = $this->interacting_users()->get();

        // Merge both collections, remove duplicates, and return
        return $interactedUsers->merge($interactingUsers)->unique('id');
    }
}
