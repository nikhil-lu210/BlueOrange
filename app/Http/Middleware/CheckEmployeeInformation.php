<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\Administration\Dashboard\DashboardService;

class CheckEmployeeInformation
{
    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Skip check if user is not authenticated or doesn't have employee record
        if (!$user || !$user->employee) {
            return $next($request);
        }

        // Skip check for dashboard routes and profile update routes
        if ($this->shouldSkipCheck($request)) {
            return $next($request);
        }

        // Check if user has incomplete employee information
        if ($this->dashboardService->shouldShowEmployeeInfoUpdateModal($user)) {
            toast('Please Update your missing information from the modal that will appear on the dashboard to continue accessing other features.', 'info');
            return redirect()->route('administration.dashboard.index');
        }

        return $next($request);
    }

    /**
     * Determine if the middleware check should be skipped for this request.
     */
    private function shouldSkipCheck(Request $request): bool
    {
        $skipRoutes = [
            // Dashboard routes
            'administration.dashboard.*',

            // Profile update routes
            'administration.my.profile.*',

            // API routes that might be called from dashboard
            'administration.dashboard.calendar.*',

            // Logout route
            'logout',

            // File/media routes
            'administration.file_media.*',

            // Notification routes
            'administration.notification.*',

            // Localization routes
            'administration.localization.*',

            // Security routes
            'administration.my.profile.security.*',
        ];

        foreach ($skipRoutes as $pattern) {
            if ($request->routeIs($pattern)) {
                return true;
            }
        }

        // Also skip for AJAX requests to avoid breaking modal functionality
        if ($request->ajax()) {
            return true;
        }

        // Skip for JSON requests (API calls)
        if ($request->expectsJson()) {
            return true;
        }

        return false;
    }
}
