<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings\Settings;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpRange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the allowed IP ranges from the settings
        $ipRanges = Settings::where('key', 'allowed_ip_ranges')->value('value');
        $allowedIpRanges = json_decode($ipRanges, true) ?? [];
        $userIp = $request->ip();

        // Get the user role (assuming user is authenticated)
        $userRole = auth()->check() ? auth()->user()->roles[0]->name : null;

        // Allow local IPs or users with 'Developer' role
        if (in_array($userIp, ['127.0.0.1', '::1']) || $userRole === 'Developer') {
            return $next($request);
        }

        // Check if the user's IP is allowed
        if (!$this->isIpAllowed($userIp, $allowedIpRanges)) {
            return response()->view('errors.restrictions.ip_range', [], 403);
        }

        return $next($request);
    }


    /**
     * Check if the given IP is within any of the allowed CIDR ranges.
     */
    private function isIpAllowed(string $ip, array $allowedRanges): bool
    {
        foreach ($allowedRanges as $range) {
            if ($this->ipInRange($ip, $range['ip_address'], (int)$range['range'])) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if an IP is in a CIDR range.
     */
    private function ipInRange(string $ip, string $subnet, int $bits): bool
    {
        $ipDecimal = ip2long($ip);
        $subnetDecimal = ip2long($subnet);
        $mask = -1 << (32 - $bits);

        return ($ipDecimal & $mask) === ($subnetDecimal & $mask);
    }
}
