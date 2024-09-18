<?php

namespace App\Http\Controllers\Administration\Profile;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Administration\Profile\ProfileUpdateRequest;
use App\Http\Requests\Administration\Profile\Security\PasswordUpdateRequest;
use App\Notifications\Administration\ProfileUpdateNofication;

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
                $fullName = $request->first_name .' '. $request->last_name;
                
                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                ]);

                if(isset($request->email)) {
                    $user->email = $request->email;
                    $user->save();
                }

                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    // Remove the previous avatar if it exists
                    if ($user->hasMedia('avatar')) {
                        $user->clearMediaCollection('avatar');
                    }
                    
                    // Add the updated avatar
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                if(isset($request->role_id)) {
                    // Sync the user's role
                    $role = Role::findOrFail($request->role_id);
                    $user->syncRoles([$role]);
                }

                $notifiableUsers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Super Admin', 'Admin', 'HR Manager']);
                })->get();
                
                foreach ($notifiableUsers as $key => $notifiableUser) {
                    $notifiableUser->notify(new ProfileUpdateNofication($user));
                }
            }, 5);

            toast('Your profile has been updated.', 'success');
            return redirect()->route('administration.my.profile');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }
}
