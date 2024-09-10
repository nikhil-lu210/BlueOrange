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
        $activeTeamLeader = $user->active_team_leader;

        $teamLeaders = User::select(['id', 'name'])
            ->whereStatus('Active')
            ->when($activeTeamLeader, function ($query) use ($activeTeamLeader) {
                // Exclude the active team leader if exists
                return $query->where('id', '!=', $activeTeamLeader->id);
            })
            ->where('id', '!=', $user->id)
            ->get();

        $user = User::with([
            'interacted_users',
            'employee_team_leaders' => function ($query) {
                $query->orderByDesc('employee_team_leader.created_at');
            }
        ])->findOrFail($user->id);        
            
        return view('administration.settings.user.includes.user_interaction', compact(['teamLeaders', 'user']));
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
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 
    }
}
