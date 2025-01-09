<?php

namespace App\Services\Administration\User;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\EmployeeShift\EmployeeShift;

class UserService
{
    public function getUserListingData($request)
    {
        $roles = Role::select(['id', 'name'])->get();
        $query = User::select(['id', 'userid', 'first_name', 'last_name', 'name', 'email', 'status'])
                     ->with(['media', 'roles:id,name']);

        if ($request->filled('role_id')) {
            $query->whereHas('roles', fn($role) => $role->where('roles.id', $request->role_id));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'Active');
        }

        $users = $query->get();

        return compact('roles', 'users');
    }

    public function getAllRoles()
    {
        return Role::all();
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

            return $user;
        });
    }

    public function getUser(User $user)
    {
        return User::with(['roles', 'media'])->findOrFail($user->id);
    }

    public function updateUser(User $user, array $data)
    {
        return DB::transaction(function () use ($user, $data) {
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
}
