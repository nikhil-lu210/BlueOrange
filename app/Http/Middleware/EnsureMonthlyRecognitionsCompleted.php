<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\Administration\EmployeeRecognition\EmployeeRecognitionService;

class EnsureMonthlyRecognitionsCompleted
{
    public function __construct(protected EmployeeRecognitionService $service)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $enforcement = config('ers.enforcement', 'none');
        if ($enforcement === 'none') {
            return $next($request);
        }

        $user = $request->user();
        if (!$user) {
            return $next($request);
        }

        // Only enforce for team leaders within the window
        $isTeamLeader = $user->tl_employees()->wherePivot('is_active', true)->exists();
        if (!$isTeamLeader || !$this->service->withinRecognitionWindow()) {
            return $next($request);
        }

        $currentMonth = now()->startOfMonth();
        $teamMemberIds = $user->tl_employees()->wherePivot('is_active', true)->pluck('users.id');
        $recognizedIds = \App\Models\User\Employee\EmployeeRecognition::where('team_leader_id', $user->id)
            ->whereDate('month', $currentMonth->format('Y-m-d'))
            ->pluck('employee_id');
        $missingIds = $teamMemberIds->diff($recognizedIds);

        if ($missingIds->isEmpty()) {
            return $next($request);
        }

        // Allow access to recognition routes and auth/logout always
        $allowedRoutes = [
            'administration.employee_recognition.index',
            'administration.employee_recognition.store',
            'administration.employee_recognition.leaderboard',
            'logout',
        ];

        if ($enforcement === 'soft') {
            // For soft, just flash a banner message and continue
            session()->flash('warning', __('Monthly recognitions are due. Please complete them by the 5th.'));
            return $next($request);
        }

        // Hard lock: redirect all non-allowed routes to the recognition index
        if (!in_array(optional($request->route())->getName(), $allowedRoutes, true)) {
            return redirect()->route('administration.employee_recognition.index', ['month' => $currentMonth->format('Y-m-d')])
                ->with('warning', __('Monthly recognitions are due. Please complete them by the 5th.'));
        }

        return $next($request);
    }
}
