<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Log;
use Exception;
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
     * Display the dashboard with all required data.
     */
    public function index()
    {
        try {
            $user = $this->dashboardService->getCurrentUser();
            
            $dashboardData = $this->getDashboardData($user);
            
            return view('administration.dashboard.index', $dashboardData);
        } catch (Exception $e) {
            Log::error("Dashboard Loading Error", [
                'user' => auth()->check() ? auth()->user()->alias_name : 'Guest',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);            
            
            return view('administration.dashboard.index', [
                'user' => auth()->user(),
                'error' => 'Unable to load dashboard data. Please refresh the page.'
            ]);
        }
    }

    /**
     * Get all dashboard data organized by category.
     */
    private function getDashboardData($user): array
    {
        return array_merge(
            $this->getUserData($user),
            $this->getAttendanceData($user),
            $this->getUserStatusData(),
            $this->getModalData($user),
            $this->getRecognitionData($user),
            $this->getBirthdayData()
        );
    }

    /**
     * Get core user data.
     */
    private function getUserData($user): array
    {
        return [
            'user' => $user,
            'wish' => $this->dashboardService->getRandomBirthdayWish(),
        ];
    }

    /**
     * Get attendance-related data.
     */
    private function getAttendanceData($user): array
    {
        $attendanceStats = $this->dashboardService->getAttendanceStatistics($user);
        
        return array_merge($attendanceStats, [
            'activeAttendance' => $this->dashboardService->getActiveAttendance($user),
            'attendances' => $this->dashboardService->getCurrentMonthAttendances($user),
        ]);
    }

    /**
     * Get user status data (working, on leave, absent).
     */
    private function getUserStatusData(): array
    {
        return [
            'currentlyWorkingUsers' => $this->dashboardService->getCurrentlyWorkingUsers(),
            'onLeaveUsers' => $this->dashboardService->getUsersOnLeaveToday(),
            'absentUsers' => $this->dashboardService->getAbsentUsers(),
        ];
    }

    /**
     * Get modal-related data.
     */
    private function getModalData($user): array
    {
        return [
            'showEmployeeInfoUpdateModal' => $this->dashboardService->shouldShowEmployeeInfoUpdateModal($user),
            'groupedBloodGroups' => $this->dashboardService->getGroupedBloodGroups(),
            'institutes' => $this->dashboardService->getAllInstitutes(),
            'educationLevels' => $this->dashboardService->getAllEducationLevels(),
        ];
    }

    /**
     * Get recognition-related data.
     */
    private function getRecognitionData($user): array
    {
        return [
            'canRecognize' => $this->dashboardService->canRecognize($user),
            'autoShowRecognitionModal' => $this->dashboardService->shouldAutoShowRecognitionModal($user, 15),
            'latestRecognition' => $this->dashboardService->getLatestRecognitionForUser($user, 15),
            'recognitionData' => $this->dashboardService->getUnreadRecognitionNotifications(),
        ];
    }

    /**
     * Get birthday-related data.
     */
    private function getBirthdayData(): array
    {
        return [
            'upcomingBirthdays' => $this->dashboardService->getUpcomingBirthdays(30),
        ];
    }
}
