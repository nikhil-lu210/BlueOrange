<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Auth;
use Hash;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Endroid\QrCode\Builder\Builder;
use App\Http\Controllers\Controller;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Mail;
use App\Models\Attendance\Attendance;
use Illuminate\Support\Facades\Storage;
use App\Models\EmployeeShift\EmployeeShift;
use App\Mail\Administration\User\UserCredentialsMail;
use App\Notifications\Administration\UserInfoUpdateNofication;
use App\Http\Requests\Administration\Settings\User\UserStoreRequest;
use App\Http\Requests\Administration\Settings\User\UserUpdateRequest;
use App\Notifications\Administration\NewUserRegistrationNotification;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::select(['id', 'name'])->distinct()->get();

        $query = User::select(['id','userid','name','email','status'])->with([
            'media', 
            'roles:id,name'
        ]);

        if ($request->has('role_id') && !is_null($request->role_id)) {
            $query->whereHas('roles', function ($role) use ($request) {
                $role->where('roles.id', $request->role_id);
            });
        }

        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Active');
        }

        $users = $query->get();
        
        return view('administration.settings.user.index', compact(['roles', 'users']));
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
                $fullName = $request->first_name .' '. $request->last_name;
                
                $user = User::create([
                    'userid' => $request->userid,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                    'email' => $request->email,
                    'password' => Hash::make($request->password)
                ]);
                
                // Attach the interaction for this user with himself/herself
                $user->interacted_users()->attach($user->id);
                
                // Upload and associate the avatar with the user
                // Update the path from App\Services\MediaLibrary\PathGenerators\UserPathGenerator
                if ($request->hasFile('avatar')) {
                    $user->addMedia($request->avatar)
                         ->toMediaCollection('avatar');
                }

                // Create associated employee for the user
                $user->employee()->create([
                    'joining_date' => $request->joining_date,
                    'alias_name' => $request->alias_name,
                    'father_name' => $request->father_name,
                    'mother_name' => $request->mother_name,
                    'personal_email' => $request->personal_email,
                    'official_email' => $request->official_email,
                    'personal_contact_no' => $request->personal_contact_no,
                    'official_contact_no' => $request->official_contact_no,
                ]);
                
                // Create associated employee_shift for the user
                EmployeeShift::create([
                    'user_id' => $user->id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'implemented_from' => date('Y-m-d')
                ]);

                // Generate QR code and save it to storage (https://github.com/endroid/qr-code)
                $qrCode = Builder::create()
                                ->writer(new PngWriter())
                                ->data($user->userid)
                                ->size(300)
                                ->margin(10)
                                ->build();
                $qrCodePath = 'qrcodes/' . $user->userid . '.png';
                Storage::disk('public')->put($qrCodePath, $qrCode->getString());

                // Save the QR code file as a media item
                $user->addMedia(storage_path('app/public/' . $qrCodePath))
                    ->toMediaCollection('qrcode');

                $role = Role::findOrFail($request->role_id);
                $user->assignRole($role);

                $authUser = Auth::user();

                $notifiableUsers = User::whereHas('roles', function ($query) {
                    $query->whereIn('name', ['Super Admin', 'Admin', 'HR Manager']);
                })->get();
                
                foreach ($notifiableUsers as $key => $notifiableUser) {
                    $notifiableUser->notify(new NewUserRegistrationNotification($user, $authUser));
                }

                // Send Login Credentials Mail to the User's email
                Mail::to($user->email)->send(new UserCredentialsMail($request));
            }, 5);

            toast('A New User Has Been Created.','success');
            return redirect()->route('administration.settings.user.user_interaction.index', ['user' => $user]);
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
                $fullName = $request->first_name .' '. $request->last_name;
                
                $user->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'name' => $fullName,
                    'email' => $request->email,
                ]);
                
                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    // Remove the previous avatar if it exists
                    if ($user->hasMedia('avatar')) {
                        $user->clearMediaCollection('avatar');
                    }
                    
                    // Add the updated avatar
                    // Update the path from App\Services\MediaLibrary\PathGenerators\UserPathGenerator
                    $user->addMedia($request->avatar)->toMediaCollection('avatar');
                }

                // Sync the user's role
                $role = Role::findOrFail($request->role_id);
                $user->syncRoles([$role]);

                // update associated employee for the user
                $user->employee()->update([
                    'joining_date' => $request->joining_date,
                    'alias_name' => $request->alias_name,
                    'father_name' => $request->father_name,
                    'mother_name' => $request->mother_name,
                    'personal_email' => $request->personal_email,
                    'official_email' => $request->official_email,
                    'personal_contact_no' => $request->personal_contact_no,
                    'official_contact_no' => $request->official_contact_no,
                ]);

                $authUser = Auth::user();

                // Send Notification to that User
                $user->notify(new UserInfoUpdateNofication($user, $authUser));
            }, 5);

            toast('User information has been updated.', 'success');
            return redirect()->route('administration.settings.user.show.profile', ['user' => $user]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }


    /**
     * EmployeeShift Update
     */
    public function updateShift(Request $request, EmployeeShift $employee_shift, User $user) {
        $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);
        // dd($request->all(), $employee_shift->id, $user->id, date('Y-m-d'));

        try {
            DB::transaction(function() use ($request, $employee_shift, $user) {
                $employee_shift->update([
                    'implemented_to' => date('Y-m-d'),
                    'status' => 'Inactive'
                ]);

                EmployeeShift::create([
                    'user_id' => $user->id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'implemented_from' => date('Y-m-d')
                ]);
            }, 5);

            toast('User EmployeeShift has been updated.', 'success');
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
        // dd($user);
        try {
            DB::transaction(function() use ($user) {
                $user->delete();
            }, 5);

            toast('User Has Been Deleted.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }


    public function generateQrCode(User $user) {
        if ($user->hasMedia('qecode')) {
            toast('User Has Already QR Code.', 'warning');
            return redirect()->back();
        }

        // Generate QR code and save it to storage (https://github.com/endroid/qr-code)
        $qrCode = Builder::create()
                ->writer(new PngWriter())
                ->data($user->userid)
                ->size(300)
                ->margin(10)
                ->build();
        $qrCodePath = 'qrcodes/' . $user->userid . '.png';
        Storage::disk('public')->put($qrCodePath, $qrCode->getString());

        // Save the QR code file as a media item
        // Update the path from App\Services\MediaLibrary\PathGenerators\UserPathGenerator
        $user->addMedia(storage_path('app/public/' . $qrCodePath))
             ->toMediaCollection('qrcode');

        toast('QR Code Generated Successfully.', 'success');
        return redirect()->back();
    }
}
