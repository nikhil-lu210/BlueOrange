<?php

namespace App\Http\Controllers\Administration\Settings\System\AppSetting;

use App\Http\Controllers\Controller;
use App\Models\Settings\Settings;
use Illuminate\Http\Request;

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

        return view('administration.settings.system.app_settings.restrictions', compact('devices', 'restrictions'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'mobile_restriction' => ['nullable', 'boolean'],
            'computer_restriction' => ['nullable', 'boolean'],
        ]);

        // Update or create settings for mobile and computer restrictions
        Settings::updateOrCreate(
            ['key' => 'mobile_restriction'],
            ['value' => $request->has('mobile_restriction') ? 1 : 0]
        );

        Settings::updateOrCreate(
            ['key' => 'computer_restriction'],
            ['value' => $request->has('computer_restriction') ? 1 : 0]
        );

        toast('Device Restriction Updated', 'success');
        return redirect()->back();
    }
}
