<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recognition\Recognition;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecognitionPolicy
{
    use HandlesAuthorization;

    /**
     * Handle all checks before running specific ability methods.
     */
    public function before(User $user, string $ability): ?Response
    {
        if ($user->hasPermissionTo('Recognition Everything') || $user->getAllPermissions()->contains('name', 'Recognition Everything')) {
            return Response::allow();
        }

        return null; // continue with normal checks
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return ($user->hasPermissionTo('Recognition Read') || $user->getAllPermissions()->contains('name', 'Recognition Read'))
            ? Response::allow()
            : Response::deny('You do not have permission to view recognitions.');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Recognition $recognition): Response
    {
        // Users can view their own recognitions or if they have read permission
        if ($user->id === $recognition->user_id) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('Recognition Read') || $user->getAllPermissions()->contains('name', 'Recognition Read')) {
            // Check if the recognition belongs to someone in their team
            $userInteractionIds = $user->user_interactions->pluck('id');
            if ($userInteractionIds->contains($recognition->user_id)) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to view this recognition.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return ($user->hasPermissionTo('Recognition Create') || $user->getAllPermissions()->contains('name', 'Recognition Create'))
            ? Response::allow()
            : Response::deny('You do not have permission to create recognitions.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Recognition $recognition): Response
    {
        // Users can update their own recognitions or if they have update permission
        if ($user->id === $recognition->recognizer_id) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('Recognition Update') || $user->getAllPermissions()->contains('name', 'Recognition Update')) {
            // Check if the recognition belongs to someone in their team
            $userInteractionIds = $user->user_interactions->pluck('id');
            if ($userInteractionIds->contains($recognition->user_id)) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to update this recognition.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Recognition $recognition): Response
    {
        // Users can delete their own recognitions or if they have delete permission
        if ($user->id === $recognition->recognizer_id) {
            return Response::allow();
        }

        if ($user->hasPermissionTo('Recognition Delete') || $user->getAllPermissions()->contains('name', 'Recognition Delete')) {
            // Check if the recognition belongs to someone in their team
            $userInteractionIds = $user->user_interactions->pluck('id');
            if ($userInteractionIds->contains($recognition->user_id)) {
                return Response::allow();
            }
        }

        return Response::deny('You do not have permission to delete this recognition.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Recognition $recognition): Response
    {
        return ($user->hasPermissionTo('Recognition Update') || $user->getAllPermissions()->contains('name', 'Recognition Update'))
            ? Response::allow()
            : Response::deny('You do not have permission to restore recognitions.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Recognition $recognition): Response
    {
        return ($user->hasPermissionTo('Recognition Delete') || $user->getAllPermissions()->contains('name', 'Recognition Delete'))
            ? Response::allow()
            : Response::deny('You do not have permission to permanently delete recognitions.');
    }
}
