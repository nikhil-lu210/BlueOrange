<?php

namespace App\Http\Controllers\Administration\Settings\User;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserInteractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $user = User::with([
            'interacted_users',
            'employee_team_leaders' => function ($query) {
                $query->orderByDesc('employee_team_leader.created_at');
            }
        ])->findOrFail($user->id);        
            
        return view('administration.settings.user.includes.user_interaction', compact(['user']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // 
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // 
    }
}
