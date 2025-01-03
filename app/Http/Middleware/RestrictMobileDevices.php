<?php

namespace App\Http\Middleware;

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

        if ($agent->isMobile()) {
            // return response()->json([
            //     'message' => 'Access from mobile devices is restricted.'
            // ], 403);

            return response()->view('errors.mobile_restriction', [], 403);
        }

        return $next($request);
    }
}
