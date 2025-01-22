<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserInteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        // dd($user->user_interactions->pluck('id'));
        $activeTeamLeader = $user->active_team_leader;

        $teamLeaders = User::select(['id', 'name'])
            ->whereStatus('Active')
            ->when($activeTeamLeader, function ($query) use ($activeTeamLeader) {
                // Exclude the active team leader if exists
                return $query->where('id', '!=', $activeTeamLeader->id);
            })
            ->where('id', '!=', $user->id)
            ->orderBy('name', 'ASC')
            ->get();

        $users = User::whereDoesntHave('interacting_users', function($userQuery) use ($user) {
                            $userQuery->where('user_id', $user->id)
                                    ->orWhere('interacted_user_id', $user->id);
                        })
                        ->whereDoesntHave('interacted_users', function($userQuery) use ($user) {
                            $userQuery->where('user_id', $user->id)
                                    ->orWhere('interacted_user_id', $user->id);
                        })
                        ->where('id', '!=', $user->id) // Exclude the current user from the list
                        ->orderBy('name', 'ASC')
                        ->get();


        $user = User::with([
            'interacted_users',
            'employee_team_leaders' => function ($query) {
                $query->orderByDesc('employee_team_leader.created_at');
            }
        ])->findOrFail($user->id);        
            
        return view('administration.settings.user.includes.user_interaction', compact(['teamLeaders', 'users', 'user']));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function updateTeamLeader(Request $request, User $user)
    {
        try {
            DB::transaction(function() use ($request, &$user) {
                if ($user->employee_team_leaders->count() > 0) {
                    // Deactivate the current active team leader
                    $user->employee_team_leaders()
                    ->updateExistingPivot($user->active_team_leader->id, ['is_active' => false]);
                }

                // Assign the new team leader and set them as active
                $user->employee_team_leaders()
                     ->attach($request->input('team_leader_id'), ['is_active' => true]);
            }, 5);

            toast('Team Leader Updated For '.$user->name.'.','success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e);
            alert('Opps! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function addUsers(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'users' => ['required', 'array'],
            'users.*' => [
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if the selected user is already in the interacted_users or interacting_users relation
                    if (
                        $user->interacted_users()->where('interacted_user_id', $value)->exists() || 
                        $user->interacting_users()->where('user_id', $value)->exists()
                    ) {
                        // Fail the validation if the user is already interacting
                        $fail('The user is already interacting with ' . $user->name);
                    }
                },
            ],
        ]);

        try {
            // Start a database transaction
            DB::transaction(function () use ($validatedData, &$user) {
                // Loop through the users and attach interactions
                foreach ($validatedData['users'] as $interactedUserId) {
                    // Attach the interaction for this user
                    $user->interacted_users()->attach($interactedUserId);
                }
            }, 5);
            
            toast('Users added for interactions with ' . $user->name . '.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            // Handle errors
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function removeUser(Request $request, User $user)
    {
        // Validate the request
        $request->validate([
            'user' => [
                'required',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) use ($user) {
                    // Check if the selected user is either an interacted user or interacting user
                    $isInteracted = $user->interacted_users()->where('interacted_user_id', $value)->exists();
                    $isInteracting = $user->interacting_users()->where('user_id', $value)->exists();

                    if (!$isInteracted && !$isInteracting) {
                        $fail('The selected user is not interacting with ' . $user->name);
                    }
                },
            ],
        ]);

        try {
            DB::transaction(function() use ($request, $user) {
                // Remove the user from interactions if found
                $user->interacted_users()->detach($request->user);
                $user->interacting_users()->detach($request->user);
            });

            // Success message
            toast('User has been removed from interactions with ' . $user->name . '.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            // Error handling
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
