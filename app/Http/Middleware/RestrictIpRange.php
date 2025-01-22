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
        // Skip restriction if the user is marked as unrestricted
        if ($request->attributes->get('unrestricted_user')) {
            return $next($request);
        }
        
        // Get the allowed IP ranges from the settings
        $ipRanges = Settings::where('key', 'allowed_ip_ranges')->value('value');
        $allowedIpRanges = json_decode($ipRanges, true) ?? [];
        $userIp = $request->ip();
        // dd($allowedIpRanges, $allowedIpRanges === []);

        // Get the user role (assuming user is authenticated)
        $userRole = auth()->check() ? auth()->user()->roles[0]->name : null;

        // Allow local IPs or users with 'Developer' role
        if (in_array($userIp, ['127.0.0.1', '::1']) || $userRole === 'Developer') {
            return $next($request);
        }

        // If no IP ranges are set (empty array), allow all requests
        if (empty($allowedIpRanges)) {
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
            // // For Testing IP Details 
            // $this->getIpRangeDetails($range['ip_address'], $range['range']);

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


    /**
     * For testing an IP Address
     * 1) Network Address
     * 2) Subnet Mask 
     * 3) Broadcast Address 
     * 4) First Usable Host 
     * 5) Second Usable Host 
     * 6) Last Usable Host 
     * 7) Total Usable Host
     */
    private function getIpRangeDetails(string $ipAddress, int $cidr): array
    {
        // Convert IP and CIDR to binary format
        $ipBinary = ip2long($ipAddress);
        $mask = -1 << (32 - $cidr);

        // Network Address
        $networkAddress = long2ip($ipBinary & $mask);

        // Subnet Mask
        $subnetMask = long2ip($mask);

        // Broadcast Address
        $broadcastAddress = long2ip(($ipBinary & $mask) | (~$mask));

        // First Usable Host (skip the network address)
        $firstUsableHost = long2ip(($ipBinary & $mask) + 1);

        // Second Usable Host
        $secondUsableHost = long2ip(($ipBinary & $mask) + 2);

        // Last Usable Host (just before the broadcast address)
        $lastUsableHost = long2ip((ip2long($broadcastAddress) - 1));

        // Total Usable Hosts
        $totalUsableHosts = pow(2, (32 - $cidr)) - 2; // Subtract 2 for network and broadcast address

        dd([
            'network_address' => $networkAddress,
            'subnet_mask' => $subnetMask,
            'broadcast_address' => $broadcastAddress,
            'first_usable_host' => $firstUsableHost,
            'second_usable_host' => $secondUsableHost,
            'last_usable_host' => $lastUsableHost,
            'total_usable_hosts' => $totalUsableHosts,
        ]);
    }
}
