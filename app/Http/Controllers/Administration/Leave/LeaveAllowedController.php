<?php

namespace App\Http\Controllers\Administration\Leave;

use App\Http\Controllers\Controller;
use App\Models\Leave\LeaveAllowed;
use App\Models\User;
use Illuminate\Http\Request;

class LeaveAllowedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        // dd($user);
        return view('administration.settings.user.includes.leave_allowed', compact(['user']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveAllowed $leaveAllowed)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveAllowed $leaveAllowed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, LeaveAllowed $leaveAllowed)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveAllowed $leaveAllowed)
    {
        //
    }
}
