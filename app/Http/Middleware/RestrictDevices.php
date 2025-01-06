<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Settings\Settings;
use Symfony\Component\HttpFoundation\Response;

class RestrictDevices
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $agent = new Agent();
        dd($agent, $agent->platform(), $this->isTrulyMobile($agent), $agent->device());

        $mobileRestriction = Settings::where('key', 'mobile_restriction')->value('value');
        $computerRestriction = Settings::where('key', 'computer_restriction')->value('value');

        $userRole = auth()->check() ? auth()->user()->roles[0]->name : null;

        // Detect if the device is a mobile
        if ($mobileRestriction && $this->isTrulyMobile($agent) && $userRole !== 'Developer') {
            return response()->view('errors.restrictions.mobile', [], 403);
        }

        // Detect if the device is a desktop
        if ($computerRestriction && !$this->isTrulyMobile($agent) && $userRole !== 'Developer') {
            return response()->view('errors.restrictions.computer', [], 403);
        }

        return $next($request);
    }

    /**
     * Determine if the device is truly a mobile device.
     */
    private function isTrulyMobile(Agent $agent): bool
    {
        // Check if the device is mobile using a combination of device name, platform, and general detection
        return $agent->isMobile() 
            || $this->isKnownMobileDevice($agent->device()) 
            || in_array($agent->platform(), ['iOS', 'Android', 'AndroidOS']);
    }

    /**
     * Check if the device name indicates a mobile device.
     */
    private function isKnownMobileDevice(?string $device): bool
    {
        $knownMobileDevices = ['iPhone', 'Samsung', 'Huawei', 'Xiaomi', 'OnePlus', 'Pixel', 'Nokia'];
        return $device && in_array($device, $knownMobileDevices, true);
    }
}
