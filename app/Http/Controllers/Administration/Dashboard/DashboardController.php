<?php

namespace App\Http\Controllers\Administration\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User\Employee\EmployeeRecognition;
use App\Services\Administration\Dashboard\DashboardService;
use Illuminate\Support\Facades\Gate;

class DashboardController extends Controller
{
    protected $dashboardService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ...existing code...
        $user = $this->dashboardService->getCurrentUser();
        $wish = $this->dashboardService->getRandomBirthdayWish();
        $attendanceStats = $this->dashboardService->getAttendanceStatistics($user);
        $totalWorkedDays = $attendanceStats['totalWorkedDays'];
        $totalRegularWork = $attendanceStats['totalRegularWork'];
        $totalOvertimeWork = $attendanceStats['totalOvertimeWork'];
        $totalRegularWorkingHour = $attendanceStats['totalRegularWorkingHour'];
        $totalOvertimeWorkingHour = $attendanceStats['totalOvertimeWorkingHour'];
        $activeAttendance = $this->dashboardService->getActiveAttendance($user);
        $attendances = $this->dashboardService->getCurrentMonthAttendances($user);
        $currentlyWorkingUsers = $this->dashboardService->getCurrentlyWorkingUsers();
        $onLeaveUsers = $this->dashboardService->getUsersOnLeaveToday();
        $absentUsers = $this->dashboardService->getAbsentUsers();
        $showEmployeeInfoUpdateModal = $this->dashboardService->shouldShowEmployeeInfoUpdateModal($user);
        $groupedBloodGroups = $this->dashboardService->getGroupedBloodGroups();
        $institutes = $this->dashboardService->getAllInstitutes();
        $educationLevels = $this->dashboardService->getAllEducationLevels();

        // ERS New Module: Monthly Recognitions quick widgets
        $isTeamLeader = $user->tl_employees()->wherePivot('is_active', true)->exists();
        $currentMonth = now()->startOfMonth();
        if (Gate::allows('Recognition Everything')) {
            $top10TeamRecognitions = EmployeeRecognition::with(['employee.roles', 'employee.media', 'teamLeader'])
                ->whereDate('month', $currentMonth->format('Y-m-d'))
                ->orderByDesc('total_score')
                ->take(10)
                ->get();
        } else {
            $top10TeamRecognitions = $isTeamLeader ? ers_top_team_performers($user, $currentMonth, 10) : collect();
        }

        $teamLeaderHasMonthlyRecognition = $isTeamLeader ? EmployeeRecognition::where('team_leader_id', $user->id)
            ->whereDate('month', $currentMonth->format('Y-m-d'))
            ->exists() : false;
        $employeeCurrentOrLastMonthRecognition = ers_employee_running_or_last_month_recognition($user, $user->active_team_leader);
        $employeeRecognitionBadge = $employeeCurrentOrLastMonthRecognition ? ers_badge_for_score((int) $employeeCurrentOrLastMonthRecognition->total_score) : null;

        // Missing recognitions for current month
        $missingRecognitions = collect();
        if ($isTeamLeader) {
            $teamMemberIds = $user->tl_employees()->wherePivot('is_active', true)->pluck('users.id');
            $recognizedIds = EmployeeRecognition::where('team_leader_id', $user->id)
                ->whereDate('month', $currentMonth->format('Y-m-d'))
                ->pluck('employee_id');
            $missingIds = $teamMemberIds->diff($recognizedIds);
            if ($missingIds->isNotEmpty()) {
                $missingRecognitions = User::whereIn('id', $missingIds)->get();
            }
        }

        return view('administration.dashboard.index', compact([
            'user',
            'wish',
            'totalWorkedDays',
            'totalRegularWork',
            'totalRegularWorkingHour',
            'totalOvertimeWorkingHour',
            'totalOvertimeWork',
            'activeAttendance',
            'attendances',
            'currentlyWorkingUsers',
            'onLeaveUsers',
            'absentUsers',
            'showEmployeeInfoUpdateModal',
            'groupedBloodGroups',
            'institutes',
            'educationLevels',
            // ERS New Module
            'isTeamLeader',
            'top10TeamRecognitions',
            'teamLeaderHasMonthlyRecognition',
            'employeeCurrentOrLastMonthRecognition',
            'employeeRecognitionBadge',
            'missingRecognitions',
        ]));
    }
}
