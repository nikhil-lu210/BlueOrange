<?php

namespace App\Http\Controllers\Administration\WorkSchedule;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WorkSchedule\WorkSchedule;
use App\Services\Administration\WorkSchedule\WorkScheduleService;
use App\Http\Requests\Administration\WorkSchedule\WorkScheduleStoreRequest;
use App\Http\Requests\Administration\WorkSchedule\WorkScheduleUpdateRequest;

class WorkScheduleController extends Controller
{
    protected $workScheduleService;

    public function __construct(WorkScheduleService $workScheduleService)
    {
        $this->workScheduleService = $workScheduleService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workSchedules = $this->workScheduleService->getWorkSchedules($request);

        // Get users for filter dropdown
        $users = User::with('employee')->get();

        // Get weekdays for filter dropdown
        $weekdays = WorkSchedule::getWeekdays();

        return view('administration.work_schedule.index', compact('workSchedules', 'users', 'weekdays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get users for dropdown
        $users = User::with('employee')->get();

        // Get available weekdays (excluding inactive weekends)
        $availableWeekdays = $this->workScheduleService->getAvailableWeekdays();

        // Get work types
        $workTypes = WorkSchedule::getWorkTypes();

        return view('administration.work_schedule.create', compact('users', 'availableWeekdays', 'workTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkScheduleStoreRequest $request)
    {
        try {
            $createdSchedules = $this->workScheduleService->createWorkSchedules($request->validated(), auth()->user());

            $message = count($createdSchedules) > 1
                ? 'Work schedules have been created successfully for ' . count($createdSchedules) . ' weekdays.'
                : 'Work schedule has been created successfully.';

            return redirect()->route('administration.work_schedule.index')
                ->with('success', $message);

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WorkSchedule $workSchedule)
    {
        $workSchedule->load(['user', 'employeeShift', 'workScheduleItems']);

        return view('administration.work_schedule.show', compact('workSchedule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkSchedule $workSchedule)
    {
        $workSchedule->load(['user', 'employeeShift', 'workScheduleItems']);

        // Get work types
        $workTypes = WorkSchedule::getWorkTypes();

        return view('administration.work_schedule.edit', compact('workSchedule', 'workTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkScheduleUpdateRequest $request, WorkSchedule $workSchedule)
    {
        try {
            $updatedSchedule = $this->workScheduleService->updateWorkSchedule($workSchedule, $request->validated());

            return redirect()->route('administration.work_schedule.show', $workSchedule)
                ->with('success', 'Work schedule has been updated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkSchedule $workSchedule)
    {
        try {
            $this->workScheduleService->deactivateWorkSchedule($workSchedule);

            return redirect()->route('administration.work_schedule.index')
                ->with('success', 'Work schedule has been deactivated successfully.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display work schedule report (Gantt chart view)
     */
    public function report(Request $request)
    {
        $weekdayFilter = $request->get('weekday_filter');
        $userFilter = $request->get('user_filter');

        $reportData = $this->workScheduleService->getWorkScheduleReportData($request);
        $users = User::whereHas('employee')->get();
        $workScheduleService = $this->workScheduleService;

        return view('administration.work_schedule.report', compact('reportData', 'users', 'workScheduleService'));
    }

    /**
     * Get user's active shift via AJAX
     */
    public function getUserShift(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $activeShift = $this->workScheduleService->getUserActiveShift($user);

        if (!$activeShift) {
            return response()->json([
                'success' => false,
                'message' => 'No active shift found for this employee.'
            ]);
        }

        return response()->json([
            'success' => true,
            'shift' => [
                'id' => $activeShift->id,
                'start_time' => $activeShift->start_time,
                'end_time' => $activeShift->end_time,
                'total_time' => $activeShift->total_time,
            ]
        ]);
    }
}
