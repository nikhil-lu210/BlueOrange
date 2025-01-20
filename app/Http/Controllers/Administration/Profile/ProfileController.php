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

                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    // Remove the previous avatar if it exists
                    if ($user->hasMedia('avatar')) {
                        $user->clearMediaCollection('avatar');
                    }
                    
                    // Add the updated avatar
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                // update associated employee for the user
                $user->employee()->update([
                    'father_name' => $request->father_name,
                    'mother_name' => $request->mother_name,
                    'birth_date' => $request->birth_date,
                    'personal_email' => $request->personal_email,
                    'personal_contact_no' => $request->personal_contact_no
                ]);

                $notifiableUsers = User::whereStatus('Active')->get();
                
                foreach ($notifiableUsers as $key => $notifiableUser) {
                    if ($notifiableUser->hasAnyPermission(['User Everything', 'User Update'])) {
                        $notifiableUser->notify(new ProfileUpdateNofication($user));
                    }
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
