<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Hash;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Settings\User\UserStoreRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return view('administration.settings.user.index', compact(['users']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();

        return view('administration.settings.user.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            DB::transaction(function() use ($request) {
                $fullName = $request->first_name .' '. $request->middle_name .' '. $request->last_name;
                
                $user = User::create([
                    'userid' => $request->userid,
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);

                $role = Role::findOrFail($request->role_id);
                $user->assignRole($role);
            }, 5);

            toast('A New User Has Been Created.','success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Opps! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        dd($user);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        dd($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        dd($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        dd($user);
    }
}