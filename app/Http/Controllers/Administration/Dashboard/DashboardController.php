<?php

namespace App\Http\Controllers\Administration\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\Administration\Dashboard\DashboardService;

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
        // Get the current authenticated user
        $user = $this->dashboardService->getCurrentUser();

        // Get a random birthday wish
        $wish = $this->dashboardService->getRandomBirthdayWish();

        // Get attendance statistics
        $attendanceStats = $this->dashboardService->getAttendanceStatistics($user);
        $totalWorkedDays = $attendanceStats['totalWorkedDays'];
        $totalRegularWork = $attendanceStats['totalRegularWork'];
        $totalOvertimeWork = $attendanceStats['totalOvertimeWork'];
        $totalRegularWorkingHour = $attendanceStats['totalRegularWorkingHour'];
        $totalOvertimeWorkingHour = $attendanceStats['totalOvertimeWorkingHour'];

        // Get active attendance and current month attendances
        $activeAttendance = $this->dashboardService->getActiveAttendance($user);
        $attendances = $this->dashboardService->getCurrentMonthAttendances($user);

        // Get users who are currently working, on leave, or absent
        $currentlyWorkingUsers = $this->dashboardService->getCurrentlyWorkingUsers();
        $onLeaveUsers = $this->dashboardService->getUsersOnLeaveToday();
        $absentUsers = $this->dashboardService->getAbsentUsers();

        // Check if the employee info update modal should be shown
        $showEmployeeInfoUpdateModal = $this->dashboardService->shouldShowEmployeeInfoUpdateModal($user);

        // Get grouped blood groups for the dropdown
        $groupedBloodGroups = $this->dashboardService->getGroupedBloodGroups();

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
        ]));
    }
}
