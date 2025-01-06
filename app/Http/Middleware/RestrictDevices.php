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
        /**
         * Reference: https://github.com/jenssegers/agent
         */
        $agent = new Agent();

        $mobileRestriction = Settings::where('key', 'mobile_restriction')->value('value');
        $computerRestriction = Settings::where('key', 'computer_restriction')->value('value');

        $userRole = auth()->check() ? auth()->user()->roles[0]->name : null;

        // Detect if the device is a mobile
        if ($mobileRestriction && $this->isTrulyMobile($agent, $request) && $userRole !== 'Developer') {
            return response()->view('errors.restrictions.mobile', [], 403);
        }

        // Detect if the device is a desktop
        if ($computerRestriction && !$this->isTrulyMobile($agent, $request) && $userRole !== 'Developer') {
            return response()->view('errors.restrictions.computer', [], 403);
        }

        return $next($request);
    }

    /**
     * Determine if the device is truly a mobile device.
     */
    private function isTrulyMobile(Agent $agent, Request $request): bool
    {
        $userAgent = $request->userAgent();

        // Check if the device is a known mobile device
        $isMobile = $agent->isMobile() || preg_match('/Mobile|iPhone|Android|AndroidOS|Windows Phone|webOS|BlackBerry/i', $userAgent) || in_array($agent->platform(), ['iOS', 'Android', 'AndroidOS']);

        // Exclude desktop modes on mobile devices
        $isDesktopMode = preg_match('/Linux|Macintosh|Windows NT/i', $userAgent) && !$agent->isTablet();

        return $isMobile && !$isDesktopMode;
    }
}
