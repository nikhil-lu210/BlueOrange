<?php

namespace App\Http\Controllers\Administration\Profile;

use Auth;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\Education\Institute\Institute;
use App\Models\Education\EducationLevel\EducationLevel;
use App\Notifications\Administration\ProfileUpdateNofication;
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

    /**
     * Update the user's information.
     */
    public function updateInformation(Request $request)
    {
        $user = Auth::user();

        try {
            // Dynamically build validation rules for only the fields present in the request
            $rules = [];

            if ($request->has('blood_group')) {
                $rules['blood_group'] = 'required|string';
            }

            if ($request->has('father_name')) {
                $rules['father_name'] = 'required|string';
            }

            if ($request->has('mother_name')) {
                $rules['mother_name'] = 'required|string';
            }

            // Educational fields validation
            if ($request->has('institute_id')) {
                $rules['institute_id'] = ['nullable', function ($attribute, $value, $fail) {
                    if ($value && !str_starts_with($value, 'new:') && !is_numeric($value)) {
                        $fail('The institute must be a valid selection or new entry.');
                    }
                    if ($value && is_numeric($value) && !Institute::where('id', $value)->exists()) {
                        $fail('The selected institute is invalid.');
                    }
                }];
            }

            if ($request->has('education_level_id')) {
                $rules['education_level_id'] = ['nullable', function ($attribute, $value, $fail) {
                    if ($value && !str_starts_with($value, 'new:') && !is_numeric($value)) {
                        $fail('The education level must be a valid selection or new entry.');
                    }
                    if ($value && is_numeric($value) && !EducationLevel::where('id', $value)->exists()) {
                        $fail('The selected education level is invalid.');
                    }
                }];
            }

            if ($request->has('passing_year')) {
                $rules['passing_year'] = ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 10)];
            }

            // Validate the request based on dynamic rules
            $validated = $request->validate($rules);

            // Process educational fields (handle new entries)
            $updateData = $validated;

            // Handle new institute creation
            if (isset($validated['institute_id']) && str_starts_with($validated['institute_id'], 'new:')) {
                $instituteName = substr($validated['institute_id'], 4);
                $institute = Institute::create(['name' => $instituteName]);
                $updateData['institute_id'] = $institute->id;
            }

            // Handle new education level creation
            if (isset($validated['education_level_id']) && str_starts_with($validated['education_level_id'], 'new:')) {
                $levelTitle = substr($validated['education_level_id'], 4);
                $educationLevel = EducationLevel::create(['title' => $levelTitle]);
                $updateData['education_level_id'] = $educationLevel->id;
            }

            // Only update the fields that are present in the request
            $user->employee()->update($updateData);

            toast('Your information has been updated.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }
}
