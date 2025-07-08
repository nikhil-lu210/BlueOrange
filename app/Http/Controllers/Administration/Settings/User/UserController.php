<?php

namespace App\Http\Controllers\Administration\Settings\User;

use Exception;
use App\Models\User;
use App\Enums\BloodGroup;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\EmployeeShift\EmployeeShift;
use App\Services\Administration\User\UserService;
use App\Http\Requests\Administration\Settings\User\UserStoreRequest;
use App\Http\Requests\Administration\Settings\User\UserUpdateRequest;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = $this->userService->getUserListingData($request);
        return view('administration.settings.user.index', $data);
    }

    /**
     * Display a listing of the barcodes.
     */
    public function allBarcodes(Request $request)
    {
        $users = User::with(['employee', 'media', 'roles'])->whereStatus('Active')->get();
        // dd($users->first());

        // foreach ($users as $key => $user) {
        //     $this->generateBarCode($user);
        // }

        return view('administration.settings.user.barcode', compact(['users']));
    }

    public function downloadAllBarcodes()
    {
        try {
            return $this->userService->downloadAllBarcodes();
        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred while creating the ZIP file.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->userService->getAllRoles();
        $religions = $this->userService->getAllReligions();
        $institutes = $this->userService->getAllInstitutes();
        $educationLevels = $this->userService->getAllEducationLevels();

        return view('administration.settings.user.create', compact(['roles', 'religions', 'institutes', 'educationLevels']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            toast('A New User Has Been Created.','success');
            return redirect()->route('administration.settings.user.user_interaction.index', ['user' => $user]);
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource profile.
     */
    public function showProfile(User $user)
    {
        $user = $this->userService->getUser($user);
        return view('administration.settings.user.includes.profile', compact('user'));
    }

    /**
     * Display the specified resource.
     */
    public function showAttendance(User $user)
    {
        $user = $this->userService->getUser($user);

        $attendances = $user->attendances;

        return view('administration.settings.user.includes.attendance', compact(['user', 'attendances']));
    }


    /**
     * Display the user files list.
     */
    public function showFiles(User $user)
    {
        $user = $this->userService->getUser($user);

        return view('administration.settings.user.includes.user_files', compact(['user']));
    }

    /**
     * Store the file.
     */
    public function uploadFile(Request $request, User $user)
    {
        $user = $this->userService->getUser($user);

        try {
            $request->validate([
                'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // max:5120 = 5MB if needed
                'note' => 'required|string',
            ]);

            $directory = 'users/' . $user->userid . '/files';
            store_file_media($request->file('file'), $user, $directory, $request->input('note'));

            toast('File Uploaded Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $user = $this->userService->getUser($user);
        $roles = $this->userService->getAllRoles();
        $religions = $this->userService->getAllReligions();
        $institutes = $this->userService->getAllInstitutes();
        $educationLevels = $this->userService->getAllEducationLevels();

        $groupedBloodGroups = [
            'Standard (ABO + Rh)' => [
                BloodGroup::A_POSITIVE,
                BloodGroup::A_NEGATIVE,
                BloodGroup::B_POSITIVE,
                BloodGroup::B_NEGATIVE,
                BloodGroup::AB_POSITIVE,
                BloodGroup::AB_NEGATIVE,
                BloodGroup::O_POSITIVE,
                BloodGroup::O_NEGATIVE,
            ],
            'Rare Types' => [
                BloodGroup::RH_NULL,
                BloodGroup::BOMBAY,
            ],
            'Other Systems' => [
                BloodGroup::KELL_POS,
                BloodGroup::KELL_NEG,
                BloodGroup::DUFFY_A,
                BloodGroup::DUFFY_B,
                BloodGroup::KIDD_A,
                BloodGroup::KIDD_B,
                BloodGroup::MNS_MN,
                BloodGroup::MNS_SS,
                BloodGroup::LUTHERAN_A,
                BloodGroup::LUTHERAN_B,
                BloodGroup::DIEGO_A,
                BloodGroup::DIEGO_B,
                BloodGroup::LEWIS_A,
                BloodGroup::LEWIS_B,
                BloodGroup::P1,
                BloodGroup::P_SMALL,
            ],
        ];

        return view('administration.settings.user.edit', compact(['roles', 'religions', 'user', 'groupedBloodGroups', 'institutes', 'educationLevels']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $this->userService->updateUser($user, $request->validated());

            toast('User information has been updated.','success');
            return redirect()->route('administration.settings.user.show.profile', ['user' => $user]);
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * EmployeeShift Update
     */
    public function updateShift(Request $request, EmployeeShift $shift, User $user) {
        $validatedShift = $request->validate([
            'start_time' => ['required'],
            'end_time' => ['required'],
        ]);

        try {
            $this->userService->updateShift($shift, $user, $validatedShift);

            toast('Employee\'s Shift Has Been Updated.','success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * User Status Update
     */
    public function updateStatus(Request $request, User $user) {
        $validatedStatus = $request->validate([
            'status' => ['required', 'in:Active,Inactive,Fired,Resigned']
        ]);

        // dd($validatedStatus);

        try {
            $this->userService->updateStatus($user, $validatedStatus);

            toast($user->alias_name. '\'s Status Has Been Updated to '. $request->status,'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    /**
     * User Password Update
     */
    public function updatePassword(Request $request, User $user) {
        $validatedPassword = $request->validate([
            'user_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        abort_if($user->role->name === 'Developer', 403, 'You are not authorized to update this user\'s password.');

        try {
            $this->userService->updatePassword($user, $validatedPassword);

            toast($user->alias_name. '\'s Password Has Been Updated', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            $this->userService->deleteUser($user);

            toast('User Has Been Deleted.','success');
            return redirect()->route('administration.settings.user.index');
        } catch (Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function generateQrCode(User $user)
    {
        try {
            $this->userService->generateQrCode($user);

            toast('QR Code Generated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }


    public function generateBarCode(User $user)
    {
        try {
            $this->userService->generateBarCode($user);

            toast('Bar Code Generated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }
}
