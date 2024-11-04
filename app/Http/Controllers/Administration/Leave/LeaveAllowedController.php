<?php

namespace App\Http\Controllers\Administration\Leave;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Leave\LeaveAllowed;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Leave\LeaveAllowedStoreRequest;
use App\Services\Administration\Leave\LeaveAllowedService;

class LeaveAllowedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
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
    public function store(LeaveAllowedStoreRequest $request, User $user, LeaveAllowedService $leaveAllowedService)
    {
        try {
            $leaveAllowedService->store($user, $request->validated());

            return redirect()->back()->with('success', 'Leave allowed upgraded successfully.');
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
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
