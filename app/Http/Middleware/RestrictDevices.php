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
        dd($agent, $agent->platform(), $this->isTrulyMobile($agent, $request), $agent->device());

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
        $parsedAgent = $this->parseUserAgent($request);

        // Combine agent library detection and manual parsing
        return $agent->isMobile() || $parsedAgent['isMobile'];
    }

    private function parseUserAgent(Request $request): array
    {
        $userAgent = $request->userAgent();

        return [
            'isMobile' => preg_match('/Mobile|iPhone|Android|Windows Phone|webOS|BlackBerry/i', $userAgent),
            'isDesktop' => preg_match('/Windows NT|Macintosh|Linux|X11/i', $userAgent),
            'userAgent' => $userAgent,
        ];
    }

}
