<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Administration\Dashboard\DashboardService;
use App\Models\User\Employee\EmployeeRecognition;

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
        $tlTop5 = $isTeamLeader ? ers_top_team_performers($user, $currentMonth, 5) : collect();
        $tlHasMonthEval = $isTeamLeader ? EmployeeRecognition::where('team_leader_id', $user->id)
            ->whereDate('month', $currentMonth->format('Y-m-d'))->exists() : false;
        $employeeEval = ers_employee_running_or_last_month_recognition($user, $user->active_team_leader);
        $employeeBadge = $employeeEval ? ers_badge_for_score((int)$employeeEval->total_score) : null;

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
            'tlTop5',
            'tlHasMonthEval',
            'employeeEval',
            'employeeBadge',
        ]));
    }

    }
