<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Leave\LeaveHistory;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class LeaveHistoryPolicy
{
    use HandlesAuthorization;

    /**
     * Handle all checks before running specific ability methods.
     */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->hasPermissionTo('Leave History Everything') || $user->getAllPermissions()->contains('name', 'Leave History Everything')) {
            return Response::allow();
        }

        return null; // continue with normal checks
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return ($user->hasPermissionTo('Leave History Read') || $user->getAllPermissions()->contains('name', 'Leave History Read'))
            ? Response::allow()
            : Response::deny('Access denied: You need \'Leave History Read\' permission to view leave records.');
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

        if ($user->hasPermissionTo('Leave History Update') || $user->getAllPermissions()->contains('name', 'Leave History Update')) {
            // Check if the leave belongs to someone in their team
            $userInteractionIds = $user->user_interactions->pluck('id');
            if ($userInteractionIds->contains($leaveHistory->user_id)) {
                return Response::allow();
            }
        }

        return Response::deny('Access denied: You can only view your own leave requests or those of your team members.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if (!($user->hasPermissionTo('Leave History Create') || $user->getAllPermissions()->contains('name', 'Leave History Create'))) {
            return Response::deny('Access denied: You need \'Leave History Create\' permission to submit leave requests.');
        }

        if (!$user->allowed_leave) {
            return Response::deny('Leave policy missing: No active leave policy has been assigned to your account. Please contact HR to set up your leave entitlements.');
        }

        // Check if user has active team leader
        if (!$user->active_team_leader) {
            return Response::deny('Team leader missing: No active team leader has been assigned to your account. Leave requests require team leader approval. Please contact HR.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!($user->hasPermissionTo('Leave History Update') || $user->getAllPermissions()->contains('name', 'Leave History Update'))) {
            return Response::deny('Access denied: You need \'Leave History Update\' permission to modify leave requests.');
        }

        // Admins cannot approve/reject their own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('Self-approval not allowed: You cannot approve or reject your own leave requests. Please ask your team leader to handle this.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('Team access only: You can only manage leave requests for members of your team.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can cancel the model.
     */
    public function cancel(User $user, LeaveHistory $leaveHistory): Response
    {
        $leaveRequestCreator = $leaveHistory->user;
        $leaveYear = Carbon::parse($leaveHistory->date)->year;
        $currentYear = now()->year;

        // Scenario 1: User has "Leave History Everything" permission - can cancel any leave directly
        if ($user->hasPermissionTo('Leave History Everything') || $user->getAllPermissions()->contains('name', 'Leave History Everything')) {
            return Response::allow();
        }

        // Scenario 2: User is the leave request creator - can cancel only "Pending" leave history
        if ($user->id === $leaveHistory->user_id) {
        if ($leaveHistory->status !== 'Pending') {
                return Response::deny('Status restriction: You can only cancel your own leave requests that are still pending approval.');
            }
            return Response::allow();
        }

        // Scenario 3: User is the active team leader - can cancel any leave history of running year only
        if ($leaveRequestCreator && $leaveRequestCreator->active_team_leader && $leaveRequestCreator->active_team_leader->id === $user->id) {
            if ($leaveYear !== $currentYear) {
                return Response::deny('Year restriction: As a team leader, you can only cancel leave requests for the current year (' . $currentYear . ').');
            }
            return Response::allow();
        }


        // Default: No permission to cancel
        return Response::deny('Access denied: You don\'t have permission to cancel this leave request. Only the request creator, team leader, or administrators can cancel leaves.');
    }

    /**
     * Determine whether the user can approve the model.
     */
    public function approve(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!($user->hasPermissionTo('Leave History Update') || $user->getAllPermissions()->contains('name', 'Leave History Update'))) {
            return Response::deny('Access denied: You need \'Leave History Update\' permission to approve leave requests.');
        }

        // Cannot approve own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('Self-approval not allowed: You cannot approve your own leave requests. Please ask your team leader to handle this.');
        }

        // Can only approve pending leaves
        if ($leaveHistory->status !== 'Pending') {
            return Response::deny('Status restriction: Only pending leave requests can be approved. This request has already been processed.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('Team access only: You can only approve leave requests for members of your team.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can reject the model.
     */
    public function reject(User $user, LeaveHistory $leaveHistory): Response
    {
        if (!($user->hasPermissionTo('Leave History Update') || $user->getAllPermissions()->contains('name', 'Leave History Update'))) {
            return Response::deny('Access denied: You need \'Leave History Update\' permission to reject leave requests.');
        }

        // Cannot reject own leave
        if ($user->id === $leaveHistory->user_id) {
            return Response::deny('Self-rejection not allowed: You cannot reject your own leave requests. Please ask your team leader to handle this.');
        }

        // Can only reject pending leaves
        if ($leaveHistory->status !== 'Pending') {
            return Response::deny('Status restriction: Only pending leave requests can be rejected. This request has already been processed.');
        }

        // Check if the leave belongs to someone in their team
        $userInteractionIds = $user->user_interactions->pluck('id');
        if (!$userInteractionIds->contains($leaveHistory->user_id)) {
            return Response::deny('Team access only: You can only reject leave requests for members of your team.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LeaveHistory $leaveHistory): Response
    {
        // Only super admins can delete leave records
        if (!$user->hasAnyRole(['Super Admin', 'Developer'])) {
            return Response::deny('Access restricted: Only Super Administrators and Developers can delete leave records.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LeaveHistory $leaveHistory): Response
    {
        return $user->hasAnyRole(['Super Admin', 'Developer'])
            ? Response::allow()
            : Response::deny('Access restricted: Only Super Administrators and Developers can restore leave records.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LeaveHistory $leaveHistory): Response
    {
        return $user->hasAnyRole(['Super Admin', 'Developer'])
            ? Response::allow()
            : Response::deny('Access restricted: Only Super Administrators and Developers can permanently delete leave records.');
    }

    /**
     * Determine whether the user can export leave data.
     */
    public function export(User $user): Response
    {
        return ($user->hasPermissionTo('Leave History Everything') || $user->getAllPermissions()->contains('name', 'Leave History Everything'))
            ? Response::allow()
            : Response::deny('Access denied: You need \'Leave History Everything\' permission to export leave data.');
    }
}
