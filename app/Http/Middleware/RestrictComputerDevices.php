<?php

namespace App\Http\Middleware;

use App\Models\Settings\Settings;
use Closure;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictComputerDevices
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

        $computerRestriction = Settings::where('key', 'computer_restriction')->value('value');
        // dd($computerRestriction, ($computerRestriction === 'enabled' && $agent->isDesktop()));

        if ($computerRestriction === 'enabled' && $agent->isDesktop()) {
            return response()->view('errors.restrctions.computer', [], 403);
        }

        return $next($request);
    }
}
