<?php

namespace App\Http\Middleware;

use App\Models\Settings\Settings;
use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictMobileDevices
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /**
         * Referance: https://github.com/jenssegers/agent
         */
        $agent = new Agent();

        $mobileRestriction = Settings::where('key', 'mobile_restriction')->value('value');
        // dd($mobileRestriction, ($mobileRestriction === 'enabled' && $agent->isMobile()));

        if ($mobileRestriction === 'enabled' && $agent->isMobile() && auth()->user()->roles[0]->name !== 'Developer') {
            return response()->view('errors.mobile_restriction', [], 403);
        }

        return $next($request);
    }
}
