<?php

namespace App\Http\Controllers\Administration\Settings\System\AppSetting;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Settings\Settings;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class RestrictionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $devices = [
            'mobile_restriction' => 'Mobile Devices',
            'computer_restriction' => 'Computer Devices'
        ];

        // Fetch restrictions from settings
        $restrictions = Settings::whereIn('key', array_keys($devices))
            ->pluck('value', 'key')
            ->map(fn($value) => (bool)$value) // Convert to boolean for easier usage in the view
            ->toArray();

        // Fetch IP ranges from settings
        $ipRanges = Settings::where('key', 'allowed_ip_ranges')
            ->value('value'); // Get the JSON value

        $ipRanges = json_decode($ipRanges, true) ?? []; // Decode JSON and ensure it's an array

        // Fetch unrestricted users
        $unrestrictedUsersJson = Settings::where('key', 'unrestricted_users')->value('value');
        $unrestrictedUsers = json_decode($unrestrictedUsersJson, true) ?? [];
        $unrestrictedUserIds = collect($unrestrictedUsers)->pluck('user_id')->toArray();

        // Fetch role users excluding unrestricted users
        $roleUsers = Role::select(['id', 'name'])->with([
            'users' => function ($user) use ($unrestrictedUserIds) {
                $user->select(['id', 'name'])
                    ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                    ->whereNotIn('id', $unrestrictedUserIds) // Exclude unrestricted users
                    ->whereStatus('Active');
            }
        ])->distinct()->get();

        return view('administration.settings.system.app_settings.restrictions', compact([
            'devices', 'restrictions', 'ipRanges', 'roleUsers', 'unrestrictedUsers'
        ]));
    }



    /**
     * Update the specified resource in storage.
     */
    public function updateDeviceRestriction(Request $request)
    {
        // Validate the request
        $request->validate([
            'mobile_restriction' => ['nullable', 'boolean'],
            'computer_restriction' => ['nullable', 'boolean'],
        ]);

        // Update or create settings for mobile and computer restrictions
        Settings::updateOrCreate(
            ['key' => 'mobile_restriction'],
            ['value' => $request->has('mobile_restriction') ? true : false]
        );

        Settings::updateOrCreate(
            ['key' => 'computer_restriction'],
            ['value' => $request->has('computer_restriction') ? true : false]
        );

        toast('Device Restriction Updated', 'success');
        return redirect()->back();
    }    


    /**
     * Update the specified resource in storage.
     */
    public function updateIpRange(Request $request)
    {
        $request->validate([
            'ip_address' => 'required|ip',
            'range' => 'required|integer|min:0|max:32',
        ]);

        $newRange = [
            'id' => Str::uuid()->toString(),
            'ip_address' => $request->ip_address,
            'range' => $request->range,
            'created_by' => auth()->user()->id,
            'created_at' => now()->toDateTimeString(),
        ];

        $settings = Settings::firstOrCreate(
            ['key' => 'allowed_ip_ranges'],
            ['value' => json_encode([])] // Default to an empty array if the key doesn't exist
        );

        $existingRanges = json_decode($settings->value, true) ?? [];

        // Check if the IP and range already exist
        foreach ($existingRanges as $range) {
            if ($range['ip_address'] === $newRange['ip_address'] && $range['range'] === $newRange['range']) {
                toast('This IP range already exists!', 'error');
                return redirect()->back();
            }
        }

        // Add the new range
        $existingRanges[] = $newRange;

        $settings->value = json_encode($existingRanges);
        $settings->save();

        toast('IP Range Restriction Updated', 'success');
        return redirect()->back();
    }


    /**
     * Update the unrestricted users list.
     */
    public function updateUnrestrictedUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id', // Validate that the user exists
        ]);

        // Prepare the new unrestricted user data
        $newUser = [
            'id' => Str::uuid()->toString(),
            'user_id' => $request->user_id,
            'assigned_by' => auth()->user()->id, // Get the authenticated user who assigned it
            'created_at' => now()->toDateTimeString(),
        ];

        // Fetch the current unrestricted users
        $settings = Settings::firstOrCreate(['key' => 'unrestricted_users']);
        $unrestrictedUsers = json_decode($settings->value, true) ?? [];

        // Check if the user is already in the unrestricted users list
        $existingUser = collect($unrestrictedUsers)->firstWhere('user_id', $request->user_id);

        if ($existingUser) {
            toast('This user is already unrestricted', 'info');
            return redirect()->back();
        }

        // Add the new unrestricted user to the list
        $unrestrictedUsers[] = $newUser;

        // Save the updated list back to settings
        $settings->value = json_encode($unrestrictedUsers);
        $settings->save();

        toast('Unrestricted user updated successfully', 'success');
        return redirect()->back();
    }


    /**
     * Destroy IP
     */
    public function destroyIpRange(string $id)
    {
        $settings = Settings::where('key', 'allowed_ip_ranges')->first();
        $ipRanges = json_decode($settings->value, true) ?? [];

        // Filter out the range with the given ID
        $updatedRanges = array_filter($ipRanges, function ($range) use ($id) {
            return $range['id'] !== $id;
        });

        $settings->value = json_encode(array_values($updatedRanges)); // Re-index the array
        $settings->save();

        toast('IP Range Deleted', 'success');
        return redirect()->back();
    }


    /**
     * Destroy User
     */
    public function destroyUnrestrictedUser(string $id)
    {
        $settings = Settings::where('key', 'unrestricted_users')->first();
        $users = json_decode($settings->value, true) ?? [];

        // Filter out the user with the given ID
        $updatedUsers = array_filter($users, function ($user) use ($id) {
            return $user['id'] !== $id;
        });

        $settings->value = json_encode(array_values($updatedUsers)); // Re-index the array
        $settings->save();

        toast('IP User Deleted', 'success');
        return redirect()->back();
    }
}
