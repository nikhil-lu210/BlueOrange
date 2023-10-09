<?php

namespace App\Http\Controllers\Administration\Profile;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Profile\ProfileUpdateRequest;

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

            toast('Your profile has been updated.', 'success');
            return redirect()->route('administration.my.profile');
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    public function attendance() {
        $user = Auth::user();

        return view('administration.profile.includes.attendance', compact(['user']));
    }

    public function break() {
        $user = Auth::user();

        return view('administration.profile.includes.break', compact(['user']));
    }
}
