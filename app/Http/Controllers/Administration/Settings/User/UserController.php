<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Hash;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Http\Requests\Administration\Settings\User\UserStoreRequest;
use App\Http\Requests\Administration\Settings\User\UserUpdateRequest;
use App\Models\WorkingShift\Shift;
use App\Notifications\Administration\NewUserRegistrationNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['roles', 'media'])->distinct()->get();        

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
        // dd($request->all());
        $user = NULL;
        try {
            DB::transaction(function() use ($request, &$user) {
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
                
                // Upload and associate the avatar with the user
                if ($request->hasFile('avatar')) {
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                $role = Role::findOrFail($request->role_id);
                $user->assignRole($role);

                $admins = User::whereHas('roles', function ($query) {
                    $query->where('name', 'Super Admin');
                })->orWhereHas('roles', function ($query) {
                    $query->where('name', 'Admin');
                })->get();
                
                foreach ($admins as $key => $admin) {
                    $admin->notify(new NewUserRegistrationNotification($user));
                }
            }, 5);

            toast('A New User Has Been Created.','success');
            return redirect()->route('administration.settings.user.show.profile', ['user' => $user]);
        } catch (Exception $e) {
            dd($e);
            alert('Opps! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function showProfile(User $user)
    {
        return view('administration.settings.user.includes.profile', compact(['user']));
    }

    /**
     * Display the specified resource.
     */
    public function showAttendance(User $user)
    {
        $attendances = Attendance::where('user_id', $user->id)
                        ->latest()
                        ->distinct()
                        ->get();
        return view('administration.settings.user.includes.attendance', compact(['user', 'attendances']));
    }

    /**
     * Display the specified resource.
     */
    public function showBreak(User $user)
    {
        // dd($user);
        return view('administration.settings.user.includes.break', compact(['user']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();

        return view('administration.settings.user.edit', compact(['roles', 'user']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            DB::transaction(function() use ($request, $user) {
                $fullName = $request->first_name .' '. $request->middle_name .' '. $request->last_name;
                
                $user->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                    'email' => $request->email,
                ]);

                // Upload and associate the avatar with the user
                if ($request->hasFile('avatar')) {
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                // Sync the user's role
                $role = Role::findOrFail($request->role_id);
                $user->syncRoles([$role]);
            }, 5);

            toast('User information has been updated.', 'success');
            return redirect()->route('administration.settings.user.show.profile', ['user' => $user]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }


    /**
     * Shift Update
     */
    public function updateShift(Request $request, Shift $shift, User $user) {
        $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);
        // dd($request->all(), $shift->id, $user->id, date('Y-m-d'));

        try {
            DB::transaction(function() use ($request, $shift, $user) {
                $shift->update([
                    'implemented_to' => date('Y-m-d'),
                    'status' => 'Inactive'
                ]);

                Shift::create([
                    'user_id' => $user->id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'implemented_from' => date('Y-m-d')
                ]);
            }, 5);

            toast('User Shift has been updated.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        dd($user);
    }
}
