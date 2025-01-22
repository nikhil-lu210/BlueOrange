<?php

namespace App\Services\Administration\User;

use Exception;
use ZipArchive;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\EmployeeShift\EmployeeShift;
use App\Mail\Administration\User\UserCredentialsMail;
use App\Notifications\Administration\NewUserRegistrationNotification;

class UserService
{
    public function getUserListingData($request)
    {
        $roles = $this->getAllRoles();

        $query = User::select(['id', 'userid', 'first_name', 'last_name', 'name', 'email', 'status'])
                    ->with(['media', 'roles:id,name']);

        // Check if the authenticated user has 'User Everything' or 'User Create' permission
        if (!auth()->user()->hasAnyPermission(['User Everything', 'User Create', 'User Update', 'User Delete'])) {
            // Restrict to users based on user interactions
            $query->whereIn('id', auth()->user()->user_interactions->pluck('id'));
        }

        // Apply role filter if provided
        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($role) => $role->where('roles.id', $request->role_id));
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

        return compact('roles', 'users');
    }

    public function getAllRoles()
    {
        return Role::select(['id', 'name'])->orderBy('name')->get();
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

            $this->attachAvatar($user, $data['avatar'] ?? null);
            $this->createEmployeeShift($user->id, $data);
            $user->assignRole($data['role_id']);
            $this->generateQrCode($user);
            $this->generateBarCode($user);

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
            // Ensure $data is passed as an object
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
        return User::with(['roles', 'media'])->findOrFail($user->id);
    }

    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
            $data['name'] = $data['first_name'] . ' ' . $data['last_name'];
            
            $user->update($data);
            $this->attachAvatar($user, $data['avatar'] ?? null);
            $user->syncRoles([$data['role_id']]);
        });
    }

    public function updateShift(EmployeeShift $shift, User $user, array $data) {
        return DB::transaction(function() use ($data, $shift, $user) {
            $shift->update([
                'implemented_to' => date('Y-m-d'),
                'status' => 'Inactive'
            ]);

            EmployeeShift::create([
                'user_id' => $user->id,
                'start_time' => $data['start_time'],
                'end_time' => $data['end_time'],
                'total_time' => get_total_time_hh_mm_ss($data['start_time'], $data['end_time']),
                'implemented_from' => date('Y-m-d')
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

    private function attachAvatar(User $user, $avatar = null)
    {
        if ($avatar) {
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
