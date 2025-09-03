<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Leave\LeaveHistory;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class LeaveHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Handle all checks before running specific ability methods.
     */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->hasPermissionTo('Leave History Everything')) {
            return Response::allow();
        }

        return null; // continue with normal checks
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasPermissionTo('Leave History Read')
            ? Response::allow()
            : Response::deny('You do not have permission to view leave histories.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LeaveHistory $leaveHistory): Response
    {
        // Users can view their own leaves or if they have update permission
        if ($user->id === $leaveHistory->user_id) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('Leave History Update')) {
            // Check if the leave belongs to someone in their team
            $userInteractionIds = $user->user_interactions->pluck('id');
            if ($userInteractionIds->contains($leaveHistory->user_id)) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to view this leave request.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if (!$user->hasPermissionTo('Leave History Create')) {
            return Response::deny('You do not have permission to create leave requests.');
        }

        if (!$user->allowed_leave) {
            return Response::deny('No active leave policy found. Please contact HR to assign a leave policy.');
        }

        // Check if user has active team leader
        if (!$user->active_team_leader) {
            return Response::deny('No active team leader found. Leave requests require a team leader for approval.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!$user->hasPermissionTo('Leave History Update')) {
            return Response::deny('You do not have permission to update leave requests.');
        }

        // Admins cannot approve/reject their own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('You cannot approve or reject your own leave request.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('You can only update leave requests for your team members.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, LeaveHistory $leaveHistory): Response
    {
        // Only the owner can cancel their own leave requests
        if ($user->id !== $leaveHistory->user_id) {
            return Response::deny('You can only cancel your own leave requests.');
        }

        // Only pending leaves can be canceled
        if ($leaveHistory->status !== 'Pending') {
            return Response::deny('Only pending leave requests can be canceled.');
        }

        // Cannot cancel leaves that are for today or in the past
        if ($leaveHistory->date <= now()->toDateString()) {
            return Response::deny('Cannot cancel leave requests for today or past dates.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!$user->hasPermissionTo('Leave History Update')) {
            return Response::deny('You do not have permission to approve leave requests.');
        }

        // Cannot approve own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('You cannot approve your own leave request.');
        }

        // Can only approve pending leaves
        if ($leaveHistory->status !== 'Pending') {
            return Response::deny('Only pending leave requests can be approved.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('You can only approve leave requests for your team members.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!$user->hasPermissionTo('Leave History Update')) {
            return Response::deny('You do not have permission to reject leave requests.');
        }

        // Cannot reject own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('You cannot reject your own leave request.');
        }

        // Can only reject pending leaves
        if ($leaveHistory->status !== 'Pending') {
            return Response::deny('Only pending leave requests can be rejected.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('You can only reject leave requests for your team members.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveHistory $leaveHistory): Response
    {
        // Only super admins can delete leave records
        if (!$user->hasRole('Super Admin')) {
            return Response::deny('Only super administrators can delete leave records.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LeaveHistory $leaveHistory): Response
    {
        return $user->hasRole('Super Admin')
            ? Response::allow()
            : Response::deny('Only super administrators can restore leave records.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LeaveHistory $leaveHistory): Response
    {
        return $user->hasRole('Super Admin')
            ? Response::allow()
            : Response::deny('Only super administrators can permanently delete leave records.');
    }

    /**
     * Determine whether the user can export leave data.
     */
    public function export(User $user): Response
    {
        return $user->hasPermissionTo('Leave History Read')
            ? Response::allow()
            : Response::deny('You do not have permission to export leave data.');
    }
}
