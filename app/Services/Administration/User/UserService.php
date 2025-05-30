<?php

namespace App\Services\Administration\User;

use Exception;
use ZipArchive;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User\Employee\Employee;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\EmployeeShift\EmployeeShift;
use App\Mail\Administration\User\UserCredentialsMail;
use App\Mail\Administration\User\UserStatusUpdateNotifyMail;
use App\Models\Religion\Religion;
use App\Models\Education\Institute\Institute;
use App\Models\Education\EducationLevel\EducationLevel;
use App\Notifications\Administration\NewUserRegistrationNotification;
use App\Notifications\Administration\User\ShiftUpdateNotification;
use App\Mail\Administration\User\ShiftUpdateMail;

class UserService
{
    public function getUserListingData($request)
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        $teamLeaders = User::whereIn('id', $userIds)
                            ->whereStatus('Active')
                            ->get()
                            ->filter(function ($user) {
                                return $user->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete']);
                            });

        $roles = $this->getAllRoles();

        $query = User::select(['id', 'userid', 'first_name', 'last_name', 'name', 'email', 'status'])
                    ->with(['media', 'employee', 'roles:id,name']);

        // Check if the authenticated user has 'User Everything' or 'User Create' permission
        if (!auth()->user()->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete'])) {
            // Restrict to users based on user interactions
            $query->whereIn('id', $userIds);
        }

        // If a team leader ID is provided, filter employees under them
        if ($request->filled('team_leader_id')) {
            $teamLeader = User::findOrFail($request->team_leader_id);

            $query->whereHas('employee_team_leaders', function ($tlQuery) use ($teamLeader) {
                $tlQuery->where('is_active', true)
                    ->where('team_leader_id', $teamLeader->id);
            });
        }

        // Apply role filter if provided
        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($role) => $role->where('roles.id', $request->role_id));
        }

        // Apply shift filter
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $startTime = $request->input('start_time').':00';
            $endTime = $request->input('end_time').':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($startTime, $endTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('start_time', '=', $startTime)
                    ->whereTime('end_time', '=', $endTime);
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Active');
        }

        // Default sorting (optional)
        $query->orderBy('name');

        $users = $query->get();

        return compact('teamLeaders', 'roles', 'users');
    }

    public function getAllRoles()
    {
        return Role::select(['id', 'name'])->orderBy('name')->get();
    }

    public function getAllReligions()
    {
        return Religion::select(['id', 'name'])->get();
    }

    public function getAllInstitutes()
    {
        return Institute::select(['id', 'name'])->orderBy('name')->get();
    }

    public function getAllEducationLevels()
    {
        return EducationLevel::select(['id', 'title'])->orderBy('id')->get();
    }

    /**
     * Get advanced filter data including all filter options and filtered users
     */
    public function getAdvancedFilterData($request)
    {
        // Get all filter options
        $teamLeaders = $this->getTeamLeaders();
        $roles = $this->getAllRoles();
        $religions = $this->getAllReligions();
        $institutes = $this->getAllInstitutes();
        $educationLevels = $this->getAllEducationLevels();

        // Get blood group options (ENUM values)
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        // Get gender options
        $genders = ['Male', 'Female', 'Other'];

        // Get status options
        $statuses = ['Active', 'Inactive', 'Fired', 'Resigned'];

        // Apply filters and get users (initially empty if no filters applied)
        $users = $this->getFilteredUsers($request);

        return compact(
            'request', 'users', 'teamLeaders', 'roles', 'religions',
            'institutes', 'educationLevels', 'bloodGroups', 'genders', 'statuses'
        );
    }

    /**
     * Get team leaders for filtering
     */
    public function getTeamLeaders()
    {
        $userIds = auth()->user()->user_interactions->pluck('id');

        return User::whereIn('id', $userIds)
                    ->whereStatus('Active')
                    ->get()
                    ->filter(function ($user) {
                        return $user->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete']);
                    });
    }

    /**
     * Get filtered users based on request parameters
     */
    public function getFilteredUsers($request)
    {
        // If no filters are applied, return empty collection
        if (!$this->hasAnyFilters($request)) {
            return collect();
        }

        $userIds = auth()->user()->user_interactions->pluck('id');

        $query = User::select(['id', 'userid', 'first_name', 'last_name', 'name', 'email', 'status', 'created_at', 'updated_at'])
                    ->with(['media', 'employee.religion', 'employee.institute', 'employee.education_level', 'roles:id,name', 'employee_shifts' => function($query) {
                        $query->where('status', 'Active')->latest('created_at');
                    }]);

        // Check if the authenticated user has permissions to see all users
        $authUser = auth()->user();
        if (!$authUser->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete'])) {
            $query->whereIn('id', $userIds);
        }

        // Apply all filters
        $this->applyAdvancedFilters($query, $request);

        // Default sorting
        $query->orderBy('name');

        return $query->get();
    }

    /**
     * Check if any filters are applied
     */
    private function hasAnyFilters($request)
    {
        $filterFields = [
            'userid', 'name', 'email', 'status', 'team_leader_id', 'role_id',
            'alias_name', 'joining_date_from', 'joining_date_to', 'birth_date_from', 'birth_date_to',
            'gender', 'blood_group', 'religion_id', 'institute_id', 'education_level_id',
            'passing_year_from', 'passing_year_to', 'start_time', 'end_time',
            'created_from', 'created_to', 'updated_from', 'updated_to',
            'personal_email', 'official_email', 'personal_contact_no', 'official_contact_no'
        ];

        foreach ($filterFields as $field) {
            if ($request->filled($field)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply advanced filters to the query
     */
    private function applyAdvancedFilters($query, $request)
    {
        // User basic information filters
        if ($request->filled('userid')) {
            $query->where('userid', 'LIKE', '%' . $request->userid . '%');
        }

        if ($request->filled('name')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('first_name', 'LIKE', '%' . $request->name . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->where('email', 'LIKE', '%' . $request->email . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date filters for user
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }

        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        if ($request->filled('updated_from')) {
            $query->whereDate('updated_at', '>=', $request->updated_from);
        }

        if ($request->filled('updated_to')) {
            $query->whereDate('updated_at', '<=', $request->updated_to);
        }

        // Role filter
        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($role) => $role->where('roles.id', $request->role_id));
        }

        // Team leader filter
        if ($request->filled('team_leader_id')) {
            $query->whereHas('employee_team_leaders', function ($tlQuery) use ($request) {
                $tlQuery->where('is_active', true)
                    ->where('team_leader_id', $request->team_leader_id);
            });
        }

        // Employee information filters
        $this->applyEmployeeFilters($query, $request);

        // Shift filters
        $this->applyShiftFilters($query, $request);
    }

    /**
     * Apply employee-related filters
     */
    private function applyEmployeeFilters($query, $request)
    {
        $query->whereHas('employee', function($employeeQuery) use ($request) {
            // Alias name filter
            if ($request->filled('alias_name')) {
                $employeeQuery->where('alias_name', 'LIKE', '%' . $request->alias_name . '%');
            }

            // Joining date range filter
            if ($request->filled('joining_date_from')) {
                $employeeQuery->whereDate('joining_date', '>=', $request->joining_date_from);
            }

            if ($request->filled('joining_date_to')) {
                $employeeQuery->whereDate('joining_date', '<=', $request->joining_date_to);
            }

            // Birth date range filter
            if ($request->filled('birth_date_from')) {
                $employeeQuery->whereDate('birth_date', '>=', $request->birth_date_from);
            }

            if ($request->filled('birth_date_to')) {
                $employeeQuery->whereDate('birth_date', '<=', $request->birth_date_to);
            }

            // Gender filter
            if ($request->filled('gender')) {
                $employeeQuery->where('gender', $request->gender);
            }

            // Blood group filter
            if ($request->filled('blood_group')) {
                $employeeQuery->where('blood_group', $request->blood_group);
            }

            // Religion filter
            if ($request->filled('religion_id')) {
                $employeeQuery->where('religion_id', $request->religion_id);
            }

            // Institute filter
            if ($request->filled('institute_id')) {
                $employeeQuery->where('institute_id', $request->institute_id);
            }

            // Education level filter
            if ($request->filled('education_level_id')) {
                $employeeQuery->where('education_level_id', $request->education_level_id);
            }

            // Passing year range filter
            if ($request->filled('passing_year_from')) {
                $employeeQuery->where('passing_year', '>=', $request->passing_year_from);
            }

            if ($request->filled('passing_year_to')) {
                $employeeQuery->where('passing_year', '<=', $request->passing_year_to);
            }

            // Email filters
            if ($request->filled('personal_email')) {
                $employeeQuery->where('personal_email', 'LIKE', '%' . $request->personal_email . '%');
            }

            if ($request->filled('official_email')) {
                $employeeQuery->where('official_email', 'LIKE', '%' . $request->official_email . '%');
            }

            // Contact number filters
            if ($request->filled('personal_contact_no')) {
                $employeeQuery->where('personal_contact_no', 'LIKE', '%' . $request->personal_contact_no . '%');
            }

            if ($request->filled('official_contact_no')) {
                $employeeQuery->where('official_contact_no', 'LIKE', '%' . $request->official_contact_no . '%');
            }
        });
    }

    /**
     * Apply shift-related filters
     */
    private function applyShiftFilters($query, $request)
    {
        if ($request->filled('start_time') && $request->filled('end_time')) {
            $startTime = $request->input('start_time') . ':00';
            $endTime = $request->input('end_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($startTime, $endTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('start_time', '=', $startTime)
                    ->whereTime('end_time', '=', $endTime);
            });
        } elseif ($request->filled('start_time')) {
            $startTime = $request->input('start_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($startTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('start_time', '=', $startTime);
            });
        } elseif ($request->filled('end_time')) {
            $endTime = $request->input('end_time') . ':00';

            $query->whereHas('employee_shifts', function ($shiftQuery) use ($endTime) {
                $shiftQuery->where('status', 'Active')
                    ->whereTime('end_time', '=', $endTime);
            });
        }
    }

    /**
     * Handle institute creation for new entries
     */
    private function handleInstituteCreation($instituteValue)
    {
        if (!$instituteValue) {
            return null;
        }

        // If it's a new institute (starts with 'new:')
        if (str_starts_with($instituteValue, 'new:')) {
            $instituteName = trim(substr($instituteValue, 4)); // Remove 'new:' prefix

            if (empty($instituteName)) {
                return null;
            }

            // Check if institute already exists
            $existingInstitute = Institute::where('name', $instituteName)->first();
            if ($existingInstitute) {
                return $existingInstitute->id;
            }

            // Create new institute (slug will be auto-generated by mutator)
            $institute = Institute::create([
                'name' => $instituteName,
                'description' => null,
            ]);

            return $institute->id;
        }

        // If it's an existing institute ID
        if (is_numeric($instituteValue)) {
            return (int) $instituteValue;
        }

        return null;
    }

    /**
     * Handle education level creation for new entries
     */
    private function handleEducationLevelCreation($educationLevelValue)
    {
        if (!$educationLevelValue) {
            return null;
        }

        // If it's a new education level (starts with 'new:')
        if (str_starts_with($educationLevelValue, 'new:')) {
            $educationLevelTitle = trim(substr($educationLevelValue, 4)); // Remove 'new:' prefix

            if (empty($educationLevelTitle)) {
                return null;
            }

            // Check if education level already exists
            $existingEducationLevel = EducationLevel::where('title', $educationLevelTitle)->first();
            if ($existingEducationLevel) {
                return $existingEducationLevel->id;
            }

            // Create new education level (slug will be auto-generated by mutator)
            $educationLevel = EducationLevel::create([
                'title' => $educationLevelTitle,
                'description' => null,
            ]);

            return $educationLevel->id;
        }

        // If it's an existing education level ID
        if (is_numeric($educationLevelValue)) {
            return (int) $educationLevelValue;
        }

        return null;
    }

    public function createUser(array $data)
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'userid' => $data['userid'],
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $this->createEmployee($user->id, $data);

            if (isset($data['avatar']) && $data['avatar'] instanceof UploadedFile) {
                $this->attachAvatar($user, $data['avatar']);
            }

            $this->createEmployeeShift($user->id, $data);
            $user->assignRole($data['role_id']);

            // $this->generateQrCode($user); // QR Code Disabled
            $this->generateBarCode($user);

            // Remove avatar before passing data to mail queue
            unset($data['avatar']);

            // Send new user registration notification
            $this->sendNewUserRegistrationNotification($user);

            // Send Login Credentials Mail to the User's email
            $this->sendUserCredentialMail($data['official_email'], $data);

            return $user;
        });
    }


    private function sendNewUserRegistrationNotification($user)
    {
        $authUser = Auth::user();

        $notifiableUsers = User::whereStatus('Active')->get()->filter(function ($user) {
            return $user->hasAnyPermission(['User Everything', 'User Update']);
        });

        foreach ($notifiableUsers as $key => $notifiableUser) {
            $notifiableUser->notify(new NewUserRegistrationNotification($user, $authUser));
        }
    }


    private function sendUserCredentialMail($email, $data)
    {
        try {
            unset($data['avatar']); // Remove UploadedFile before queuing
            $dataObject = (object) $data;
            Mail::to($email)->queue(new UserCredentialsMail($dataObject));
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }



    public function getUser(User $user)
    {
        $authUser = auth()->user();

        // Check if the authenticated user has the necessary permissions
        if (!$authUser->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete'])) {
            // Restrict access to users related to the authenticated user through user_interactions
            if (!$authUser->user_interactions->pluck('id')->contains($user->id)) {
                abort(403, 'You do not have permission to access this user.');
            }
        }

        // Fetch the user with the required relationships
        return User::with(['roles', 'employee.religion', 'media'])->findOrFail($user->id);
    }


    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            // Merge first_name and last_name for users table
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];

            // Update only the users table
            $user->update([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            // Ensure user has an associated employee record
            $employee = $user->employee;

            if ($employee) {
                // Handle new institute creation
                $instituteId = $this->handleInstituteCreation($data['institute_id'] ?? null);

                // Handle new education level creation
                $educationLevelId = $this->handleEducationLevelCreation($data['education_level_id'] ?? null);

                // Update only the employees table
                $employee->update([
                    'joining_date' => $data['joining_date'],
                    'alias_name' => $data['alias_name'],
                    'father_name' => $data['father_name'],
                    'mother_name' => $data['mother_name'],
                    'birth_date' => $data['birth_date'],
                    'personal_email' => $data['personal_email'],
                    'official_email' => $data['official_email'],
                    'personal_contact_no' => $data['personal_contact_no'],
                    'official_contact_no' => $data['official_contact_no'],
                    'religion_id' => $data['religion_id'],
                    'gender' => $data['gender'],
                    'blood_group' => $data['blood_group'],
                    'institute_id' => $instituteId,
                    'education_level_id' => $educationLevelId,
                    'passing_year' => $data['passing_year'] ?? null,
                ]);
            }

            // Handle avatar update
            $this->attachAvatar($user, $data['avatar'] ?? null);

            // Sync roles
            $user->syncRoles([$data['role_id']]);
        });
    }


    public function updateShift(EmployeeShift $shift, User $user, array $data) {
        return DB::transaction(function() use ($data, $shift, $user) {
            // Create the new shift
            $newShift = EmployeeShift::create([
                'user_id' => $user->id,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'total_time' => get_total_time_hh_mm_ss($data['start_time'], $data['end_time']),
                'implemented_from' => date('Y-m-d')
            ]);

            // Update the old shift
            $shift->update([
                'implemented_to' => date('Y-m-d'),
                'status' => 'Inactive'
            ]);

            // Reload the user with fresh relationships
            $user = User::with(['employee', 'employee_team_leaders'])->findOrFail($user->id);

            // Get the active team leader separately since it's an accessor, not a relationship
            $activeTeamLeader = $user->active_team_leader;

            // Get the authenticated user
            $authUser = Auth::user();

            // 1. Notify the employee
            $user->notify(new ShiftUpdateNotification($user, $shift, $newShift, $authUser));
            Mail::to($user->employee->official_email)
                ->queue(new ShiftUpdateMail($user, $shift, $newShift, $user, $authUser));

            // 2. Notify the active team leader (if exists)
            if ($activeTeamLeader) {
                $activeTeamLeader->notify(new ShiftUpdateNotification($user, $shift, $newShift, $authUser));

                Mail::to($activeTeamLeader->employee->official_email)
                    ->queue(new ShiftUpdateMail($user, $shift, $newShift, $activeTeamLeader, $authUser));
            }

            // 3. Notify users with "User Create" or "User Update" permissions
            $activeTeamLeaderId = $activeTeamLeader ? $activeTeamLeader->id : null;

            $notifiableUsers = User::whereStatus('Active')
                ->with('employee')
                ->get()
                ->filter(function ($notifiableUser) use ($user, $activeTeamLeaderId) {
                    // Skip the employee and team leader as they've already been notified
                    if ($notifiableUser->id === $user->id ||
                        ($activeTeamLeaderId && $notifiableUser->id === $activeTeamLeaderId)) {
                        return false;
                    }

                    // Include users with the specified permissions
                    return $notifiableUser->hasAnyPermission(['User Create', 'User Update']);
                });

            foreach ($notifiableUsers as $notifiableUser) {
                $notifiableUser->notify(new ShiftUpdateNotification($user, $shift, $newShift, $authUser));
                Mail::to($notifiableUser->employee->official_email)
                    ->queue(new ShiftUpdateMail($user, $shift, $newShift, $notifiableUser, $authUser));
            }
        }, 5);
    }

    public function updateStatus(User $user, array $data) {
        return DB::transaction(function() use ($data, $user) {
            $user->update([
                'status' => $data['status']
            ]);

            $notifiableUsers = User::with(['employee'])->whereStatus('Active')->get();

            // Send Mail to the Issue Applier by Queue
            foreach ($notifiableUsers as $notifiableUser) {
                Mail::to($notifiableUser->employee->official_email)->queue(new UserStatusUpdateNotifyMail($user, $notifiableUser, auth()->user()));
            }
        }, 5);
    }

    public function updatePassword(User $user, array $data) {
        return DB::transaction(function() use ($data, $user) {
            $user->update([
                'password' => Hash::make($data['user_password']),
            ]);
        }, 5);
    }

    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            if ($user->employee) {
                $user->employee->delete();
            }
            $user->delete();
        });
    }


    private function createEmployee($userId, $data)
    {
        // Handle new institute creation
        $instituteId = $this->handleInstituteCreation($data['institute_id'] ?? null);

        // Handle new education level creation
        $educationLevelId = $this->handleEducationLevelCreation($data['education_level_id'] ?? null);

        Employee::create([
            'user_id' => $userId,
            'joining_date' => $data['joining_date'],
            'alias_name' => $data['alias_name'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'birth_date' => $data['birth_date'],
            'personal_email' => $data['personal_email'],
            'official_email' => $data['official_email'],
            'personal_contact_no' => $data['personal_contact_no'],
            'official_contact_no' => $data['official_contact_no'],
            'institute_id' => $instituteId,
            'education_level_id' => $educationLevelId,
            'passing_year' => $data['passing_year'] ?? null,
        ]);
    }


    private function attachAvatar(User $user, $avatar = null)
    {
        if ($avatar instanceof UploadedFile) {
            if ($user->hasMedia('avatar')) {
                $user->clearMediaCollection('avatar');
            }
            $user->addMedia($avatar)->toMediaCollection('avatar');
        }
    }


    private function createEmployeeShift($userId, $data)
    {
        EmployeeShift::create([
            'user_id' => $userId,
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'total_time' => get_total_time_hh_mm_ss($data['start_time'], $data['end_time']),
            'implemented_from' => now()->toDateString(),
        ]);
    }

    public function generateQrCode(User $user)
    {
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

    public function generateBarCode(User $user)
    {
        if ($user->hasMedia('barcode')) {
            toast('User already has a barcode.', 'warning');
            return redirect()->back();
        }

        // Generate Barcode using Picqer Barcode Generator
        $generator = new BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($user->userid, $generator::TYPE_CODE_128); // Generates CODE 128 barcode
        $barcodePath = 'barcodes/' . $user->userid . '.png';

        // Save the barcode to storage
        Storage::disk('public')->put($barcodePath, $barcodeData);

        // Save the barcode file as a media item
        $user->addMedia(storage_path('app/public/' . $barcodePath))
            ->toMediaCollection('barcode');

        toast('Barcode generated successfully.', 'success');
        return redirect()->back();
    }


    public function downloadAllBarcodes()
    {
        // Get all users who have a barcode media
        $users = User::has('media')->get();  // Adjust if necessary to filter users with barcode media

        // Initialize a new ZipArchive instance
        $zip = new ZipArchive();
        $zipFileName = 'barcodes_' . now()->format('Y-m-d_H-i-s') . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);  // Path to store the temporary zip file

        // Open the zip file
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            return response()->json(['message' => 'Could not create ZIP file.'], 500);
        }

        // Iterate through each user and add their barcode file to the ZIP
        foreach ($users as $user) {
            $media = $user->getFirstMedia('barcode');  // Get the barcode media

            if ($media && file_exists($media->getPath())) {
                // Add file to the ZIP
                $zip->addFile($media->getPath(), $media->file_name);
            }
        }

        // Close the ZIP file
        $zip->close();

        // Return the ZIP file as a downloadable response
        return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
    }
}
