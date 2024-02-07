<?php

namespace App\Http\Controllers\Administration\Profile;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance\Attendance;
use App\Http\Requests\Administration\Profile\ProfileUpdateRequest;
use App\Http\Requests\Administration\Profile\Security\PasswordUpdateRequest;

class ProfileController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();
            return $next($request);
        });
    }

    public function profile() {
        $user = Auth::user();

        return view('administration.profile.includes.profile', compact(['user']));
    }

    public function security() {
        $user = Auth::user();

        return view('administration.profile.security.index', compact(['user']));
    }

    public function updatePassword(PasswordUpdateRequest $request) {
        $user = Auth::user();
        try{
            $user->update([
                'password' => Hash::make($request->input('new_password')),
            ]);

            toast('Password Has Been Updated.', 'success');
            return redirect()->back();
        } catch (Exception $e){
            throw new Exception('Password Didn\'t Update.');
        }
    }

    public function edit() {
        $roles = Role::all();
        $user = Auth::user();

        return view('administration.profile.edit', compact(['roles', 'user']));
    }

    public function update(ProfileUpdateRequest $request) {
        $user = Auth::user();
        try {
            DB::transaction(function() use ($request, $user) {
                $fullName = $request->first_name .' '. $request->middle_name .' '. $request->last_name;
                
                $user->update([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                ]);

                if(isset($request->email)) {
                    $user->email = $request->email;
                    $user->save();
                }

                // Upload and associate the avatar with the user
                if ($request->hasFile('avatar')) {
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                if(isset($request->role_id)) {
                    // Sync the user's role
                    $role = Role::findOrFail($request->role_id);
                    $user->syncRoles([$role]);
                }
            }, 5);

            toast('Your profile has been updated.', 'success');
            return redirect()->route('administration.my.profile');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    public function attendance() {
        $user = Auth::user();
        $attendances = Attendance::with(['user:id,name'])
                        ->where('user_id', auth()->user()->id)
                        ->latest()
                        ->distinct()
                        ->get();

        return view('administration.profile.includes.attendance', compact(['user', 'attendances']));
    }

    public function break() {
        $user = Auth::user();

        return view('administration.profile.includes.break', compact(['user']));
    }
}
