<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Administration\Dashboard\DashboardService;

class DashboardController extends Controller
{
    protected $dashboardService;
    protected $employeeRecognitionService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
        $this->employeeRecognitionService = app(\App\Services\Administration\Dashboard\EmployeeRecognitionService::class);
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

        // Employee Recognition System (ERS) integration
        $canGiveRecognition = $this->dashboardService->isEligibleForRecognition($user);
        $recentRecognitions = $canGiveRecognition ? $this->dashboardService->getRecentRecognitions($user) : collect();
        $showRecognitionReminder = $canGiveRecognition ? $this->dashboardService->needsRecognitionReminder($user) : false;
        $recognitionAnnouncements = $this->dashboardService->getRecognitionAnnouncements();

        // dd($recognitionAnnouncements, $canGiveRecognition);

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
            // ERS
            'canGiveRecognition',
            'recentRecognitions',
            'showRecognitionReminder',
            'recognitionAnnouncements',
        ]));
    }

    /**
     * Store a new employee recognition.
     */
    public function storeRecognition(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:users,id',
            'category_ratings' => 'required|array',
            'category_ratings.*' => 'integer|min:1|max:5',
            'category_comments' => 'nullable|array',
            'category_comments.*' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        if (!$this->dashboardService->isEligibleForRecognition($user)) {
            return redirect()->back()->with('error', __('You are not eligible to give recognition.'));
        }

        $recognitions = [];
        foreach ($request->category_ratings as $category => $points) {
            if ($points > 0) {
                $recognitions[] = [
                    'employee_id'   => $request->employee_id,
                    'recognizer_id' => $user->id,
                    'category'      => $category,
                    'points'        => $points,
                    'comment'       => $request->category_comments[$category] ?? null,
                ];
            }
        }

        foreach ($recognitions as $data) {
            $this->employeeRecognitionService->giveRecognition($data);
        }

        // Optionally, trigger notification/announcement here

        toast('Recognition submitted successfully.', 'success');
        return redirect()->back();
    }
}
