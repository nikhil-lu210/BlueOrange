<?php

namespace App\Http\Controllers\Administration\Dashboard;

use Exception;
use Carbon\Carbon;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;
use App\Models\Leave\LeaveHistory;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardCalendarController extends Controller
{
    /**
     * Get active weekend days for the calendar
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getWeekendDays()
    {
        try {
            // Get active weekend days from the Weekend model
            $activeWeekendDays = Weekend::getActiveWeekendDays();

            return response()->json($activeWeekendDays);
        } catch (Exception $e) {
            Log::error('Error fetching weekend days: ' . $e->getMessage());
            return response()->json([], 500);
        }
    }

    /**
     * Get calendar events for the dashboard
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getEvents(Request $request)
    {
        try {
            $start = $request->input('start');
            $end = $request->input('end');

            // Validate date inputs
            if (!$start || !$end) {
                return response()->json(['error' => 'Start and end dates are required'], 400);
            }

            // Parse dates to ensure they're valid
            try {
                // Just validate the dates by parsing them
                Carbon::parse($start);
                Carbon::parse($end);
            } catch (Exception $e) {
                return response()->json(['error' => 'Invalid date format'], 400);
            }

            $events = [];

            // Get task deadlines (assigned by me or assigned to me)
            $this->addTaskEvents($events, $start, $end);

            // Get holidays
            $this->addHolidayEvents($events, $start, $end);

            // Get approved leaves
            $this->addLeaveEvents($events, $start, $end);

            // Add weekends
            $this->addWeekendEvents($events, $start, $end);

            return response()->json($events);
        } catch (Exception $e) {
            Log::error('Calendar events error: ' . $e->getMessage());

            // Return a friendly error message
            return response()->json(['error' => 'An error occurred while fetching calendar events'], 500);
        }
    }

    /**
     * Add task deadline events to the events array
     *
     * @param array $events
     * @param string $start
     * @param string $end
     * @return void
     */
    private function addTaskEvents(&$events, $start, $end)
    {
        $userId = Auth::id();

        // Tasks created by the current user
        $createdTasks = Task::where('creator_id', $userId)
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$start, $end])
            ->get();

        foreach ($createdTasks as $task) {
            $events[] = [
                'id' => 'task_' . $task->id,
                'title' => 'Task: ' . $task->title,
                'start' => $task->deadline->format('Y-m-d'),
                'allDay' => true,
                'backgroundColor' => '#ffc107', // Warning color for tasks
                'borderColor' => '#ffc107',
                'extendedProps' => [
                    'type' => 'task',
                    'status' => $task->status,
                    'description' => $task->description,
                    'taskid' => $task->taskid
                ]
            ];
        }

        // Tasks assigned to the current user
        $assignedTasks = Task::whereHas('users', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [$start, $end])
            ->get();

        foreach ($assignedTasks as $task) {
            // Only add if not already added (to avoid duplicates if user created and is assigned to the same task)
            if (!$createdTasks->contains($task->id)) {
                $events[] = [
                    'id' => 'task_' . $task->id,
                    'title' => 'Task: ' . $task->title,
                    'start' => $task->deadline->format('Y-m-d'),
                    'allDay' => true,
                    'backgroundColor' => '#ffc107', // Warning color for tasks
                    'borderColor' => '#ffc107',
                    'extendedProps' => [
                        'type' => 'task',
                        'status' => $task->status,
                        'description' => $task->description,
                        'taskid' => $task->taskid
                    ]
                ];
            }
        }
    }

    /**
     * Add holiday events to the events array
     *
     * @param array $events
     * @param string $start
     * @param string $end
     * @return void
     */
    private function addHolidayEvents(&$events, $start, $end)
    {
        $holidays = Holiday::where('is_active', true)
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($holidays as $holiday) {
            $events[] = [
                'id' => 'holiday_' . $holiday->id,
                'title' => 'Holiday: ' . $holiday->name,
                'start' => $holiday->date,
                'allDay' => true,
                'backgroundColor' => '#0d6efd', // Primary color for holidays
                'borderColor' => '#0d6efd',
                'extendedProps' => [
                    'type' => 'holiday',
                    'description' => $holiday->description
                ]
            ];
        }
    }

    /**
     * Add approved leave events to the events array
     *
     * @param array $events
     * @param string $start
     * @param string $end
     * @return void
     */
    private function addLeaveEvents(&$events, $start, $end)
    {
        $userId = Auth::id();

        // Get approved leaves for the current user
        $leaves = LeaveHistory::where('user_id', $userId)
            ->where('status', 'Approved')
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($leaves as $leave) {
            $events[] = [
                'id' => 'leave_' . $leave->id,
                'title' => 'Leave: ' . $leave->type,
                'start' => $leave->date,
                'allDay' => true,
                'backgroundColor' => '#dc3545', // Danger color for leaves
                'borderColor' => '#dc3545',
                'extendedProps' => [
                    'type' => 'leave',
                    'reason' => $leave->reason,
                    'is_paid' => $leave->is_paid_leave
                ]
            ];
        }
    }

    /**
     * Add weekend events to the events array
     *
     * @param array $events
     * @param string $start
     * @param string $end
     * @return void
     */
    private function addWeekendEvents(&$events, $start, $end)
    {
        try {
            // Get active weekend days from the Weekend model
            $activeWeekendDays = Weekend::getActiveWeekendDays();

            $startDate = Carbon::parse($start);
            $endDate = Carbon::parse($end);

            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                // Check if the current day is in the active weekend days
                $dayName = $date->format('l'); // Returns day name (Monday, Tuesday, etc.)

                if (in_array($dayName, $activeWeekendDays)) {
                    $events[] = [
                        'id' => 'weekend_' . $date->format('Y-m-d'),
                        'title' => 'Weekend',
                        'start' => $date->format('Y-m-d'),
                        'allDay' => true,
                        'backgroundColor' => '#212529', // Dark color for weekends
                        'borderColor' => '#212529',
                        'extendedProps' => [
                            'type' => 'weekend'
                        ]
                    ];
                }
            }
        } catch (Exception $e) {
            Log::error('Error fetching weekends: ' . $e->getMessage());
        }
    }
}
