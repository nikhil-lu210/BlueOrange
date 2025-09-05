<?php

namespace App\Services\Administration\WorkSchedule;

use App\Models\User;
use App\Models\Weekend\Weekend;
use App\Models\WorkSchedule\WorkSchedule;
use App\Models\WorkScheduleItem\WorkScheduleItem;
use App\Models\EmployeeShift\EmployeeShift;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Exception;

class WorkScheduleService
{
    /**
     * Get all work schedules with filters
     */
    public function getWorkSchedules($request)
    {
        $query = WorkSchedule::withDetails()
            ->active()
            ->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->forUser($request->user_id);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->forDateRange($request->start_date, $request->end_date);
        }

        // Filter by weekday
        if ($request->filled('weekday')) {
            $query->forWeekday($request->weekday);
        }

        return $query->get();
    }

    /**
     * Get work schedules for a specific user
     */
    public function getUserWorkSchedules(User $user, $request = null)
    {
        $query = WorkSchedule::withDetails()
            ->forUser($user->id)
            ->active()
            ->latest();

        if ($request && $request->filled('start_date') && $request->filled('end_date')) {
            $query->forDateRange($request->start_date, $request->end_date);
        }

        return $query->get();
    }

    /**
     * Get available weekdays (excluding inactive weekends)
     */
    public function getAvailableWeekdays(): array
    {
        $activeWeekends = Weekend::getActiveWeekendDays();
        $allWeekdays = WorkSchedule::getWeekdays();

        return array_diff($allWeekdays, $activeWeekends);
    }

    /**
     * Get user's active shift
     */
    public function getUserActiveShift(User $user): ?EmployeeShift
    {
        return EmployeeShift::where('user_id', $user->id)
            ->where('status', 'Active')
            ->where('implemented_from', '<=', now())
            ->where(function ($query) {
                $query->whereNull('implemented_to')
                    ->orWhere('implemented_to', '>=', now());
            })
            ->first();
    }

    /**
     * Create work schedules for selected weekdays
     */
    public function createWorkSchedules(array $data, User $creator): array
    {
        $createdSchedules = [];

        DB::transaction(function () use ($data, $creator, &$createdSchedules) {
            $user = User::findOrFail($data['user_id']);
            $employeeShift = $this->getUserActiveShift($user);

            if (!$employeeShift) {
                throw new Exception('No active shift found for the selected employee.');
            }

            // Validate shift time constraints based on mode
            $sameScheduleForAll = isset($data['same_schedule_for_all']) && $data['same_schedule_for_all'];
            
            if ($sameScheduleForAll) {
                if (isset($data['work_items']) && is_array($data['work_items'])) {
                    $this->validateShiftConstraints($data['work_items'], $employeeShift);
                }
            } else {
                // Validate each weekday's work items
                foreach ($data['weekdays'] as $weekday) {
                    $weekdayWorkItems = $data['weekday_work_items'][$weekday] ?? [];
                    if (!empty($weekdayWorkItems)) {
                        $this->validateShiftConstraints($weekdayWorkItems, $employeeShift);
                    }
                }
            }

            // Deactivate existing schedules for selected weekdays
            $this->deactivateExistingSchedules($user->id, $employeeShift->id, $data['weekdays']);

            // Create new schedules for each weekday
            foreach ($data['weekdays'] as $weekday) {
                $schedule = WorkSchedule::create([
                    'user_id' => $user->id,
                    'employee_shift_id' => $employeeShift->id,
                    'weekday' => $weekday,
                    'is_active' => true,
                ]);

                // Create work schedule items based on mode
                if ($sameScheduleForAll) {
                    // Same schedule for all weekdays
                    if (isset($data['work_items']) && is_array($data['work_items'])) {
                        $this->createWorkScheduleItems($schedule, $data['work_items'], true);
                    }
                } else {
                    // Individual schedule for each weekday
                    $weekdayWorkItems = $data['weekday_work_items'][$weekday] ?? [];
                    $this->createWorkScheduleItems($schedule, $weekdayWorkItems, false);
                }

                $createdSchedules[] = $schedule->load('workScheduleItems');
            }
        });

        return $createdSchedules;
    }

    /**
     * Update an existing work schedule
     */
    public function updateWorkSchedule(WorkSchedule $workSchedule, array $data): WorkSchedule
    {
        DB::transaction(function () use ($workSchedule, $data) {
            $employeeShift = $workSchedule->employeeShift;

            // Validate shift time constraints
            $this->validateShiftConstraints($data['work_items'], $employeeShift);

            // Delete existing work schedule items
            $workSchedule->workScheduleItems()->delete();

            // Create new work schedule items
            $this->createWorkScheduleItems($workSchedule, $data['work_items'], true);

            $workSchedule->load('workScheduleItems');
        });

        return $workSchedule;
    }

    /**
     * Deactivate a work schedule
     */
    public function deactivateWorkSchedule(WorkSchedule $workSchedule): WorkSchedule
    {
        $workSchedule->update(['is_active' => false]);
        return $workSchedule;
    }

    /**
     * Get work schedule report data for Gantt chart
     */
    public function getWorkScheduleReportData($request): array
    {
        $weekdayFilter = $request->get('weekday_filter');
        $userFilter = $request->get('user_filter');

        $query = WorkSchedule::withDetails()->active();

        // Apply filters
        if ($weekdayFilter) {
            $query->forWeekday($weekdayFilter);
        }

        if ($userFilter) {
            $query->forUser($userFilter);
        }

        // Order by employee alias_name
        $query->join('users', 'work_schedules.user_id', '=', 'users.id')
              ->join('employees', 'users.id', '=', 'employees.user_id')
              ->orderBy('employees.alias_name', 'ASC')
              ->select('work_schedules.*');

        $schedules = $query->get()->groupBy('user_id');

        $reportData = [];
        $workTypeColors = [
            'Client' => '#28a745',
            'Internal' => '#007bff',
            'Bench' => '#ffc107'
        ];

        foreach ($schedules as $userId => $userSchedules) {
            $user = $userSchedules->first()->user;
            $userData = [
                'user_id' => $userId,
                'user_name' => $user->alias_name ?? $user->name,
                'schedules' => []
            ];

            foreach ($userSchedules as $schedule) {
                foreach ($schedule->workScheduleItems as $item) {
                    $userData['schedules'][] = [
                        'id' => $item->id,
                        'weekday' => $schedule->weekday,
                        'start_time' => $item->start_time,
                        'end_time' => $item->end_time,
                        'work_type' => $item->work_type,
                        'work_title' => $item->work_title,
                        'duration' => $item->duration_minutes,
                        'color' => $workTypeColors[$item->work_type] ?? '#6c757d'
                    ];
                }
            }

            $reportData[] = $userData;
        }

        return $reportData;
    }

    /**
     * Validate shift time constraints
     */
    private function validateShiftConstraints(array $workItems, EmployeeShift $employeeShift): void
    {
        $shiftStartTime = Carbon::createFromFormat('H:i:s', $employeeShift->start_time);
        $shiftEndTime = Carbon::createFromFormat('H:i:s', $employeeShift->end_time);
        
        // Check if shift is overnight (end time is "before" start time)
        $isOvernightShift = $shiftEndTime->lt($shiftStartTime);
        
        if ($isOvernightShift) {
            // For overnight shifts, add 24 hours to end time for calculation
            $shiftEndTime->addDay();
        }
        
        $shiftTotalMinutes = $shiftEndTime->diffInMinutes($shiftStartTime);

        $totalWorkMinutes = 0;
        $timeRanges = [];

        foreach ($workItems as $item) {
            $startTime = Carbon::createFromFormat('H:i', $item['start_time']);
            $endTime = Carbon::createFromFormat('H:i', $item['end_time']);

            // Check if work item is overnight
            $isOvernightWork = $endTime->lt($startTime);
            
            // Create copies for duration calculation
            $startTimeForDuration = $startTime->copy();
            $endTimeForDuration = $endTime->copy();
            
            if ($isOvernightWork) {
                // For overnight work items, add 24 hours to end time for calculation
                $endTimeForDuration->addDay();
            }

            // Validate against shift times (handle overnight shifts properly)
            $shiftStartOriginal = Carbon::createFromFormat('H:i:s', $employeeShift->start_time);
            $shiftEndOriginal = Carbon::createFromFormat('H:i:s', $employeeShift->end_time);
            
            if ($isOvernightShift) {
                // For overnight shifts, work items must be within the shift range
                // Shift: 23:00-07:00, valid work items: 23:00-01:00, 01:00-07:00, etc.
                
                if ($isOvernightWork) {
                    // For overnight work items, both start and end should be within shift range
                    // Start should be >= shift start, End should be <= shift end
                    if ($startTime->lt($shiftStartOriginal) || $endTime->gt($shiftEndOriginal)) {
                        throw new Exception('Work time must be within shift time range.');
                    }
                } else {
                    // For regular work items in overnight shift (e.g., 02:00-07:00 in 23:00-07:00 shift)
                    // The work item is on the "next day" part of the overnight shift
                    // Start should be <= shift end, End should be <= shift end
                    if ($startTime->gt($shiftEndOriginal) || $endTime->gt($shiftEndOriginal)) {
                        throw new Exception('Work time must be within shift time range.');
                    }
                }
            } else {
                // For regular shifts, standard validation
                if ($startTime->lt($shiftStartOriginal) || $endTime->gt($shiftEndOriginal)) {
                    throw new Exception('Work time must be within shift time range.');
                }
            }

            // Store original times for overlap checking
            $originalStart = Carbon::createFromFormat('H:i', $item['start_time']);
            $originalEnd = Carbon::createFromFormat('H:i', $item['end_time']);
            
            // Check for overlapping times
            foreach ($timeRanges as $range) {
                if ($this->timeRangesOverlap(['start' => $originalStart, 'end' => $originalEnd], $range)) {
                    throw new Exception('Work time ranges cannot overlap.');
                }
            }

            $timeRanges[] = ['start' => $originalStart, 'end' => $originalEnd];
            $totalWorkMinutes += $endTimeForDuration->diffInMinutes($startTimeForDuration);
        }

        // Validate total work time doesn't exceed shift time
        if ($totalWorkMinutes > $shiftTotalMinutes) {
            throw new Exception('Total work time cannot exceed shift total time.');
        }
    }

    /**
     * Check if two time ranges overlap (handling overnight shifts)
     */
    private function timeRangesOverlap(array $range1, array $range2): bool
    {
        $start1 = $range1['start'];
        $end1 = $range1['end'];
        $start2 = $range2['start'];
        $end2 = $range2['end'];
        
        // Check if either range is overnight
        $isOvernight1 = $end1->lt($start1);
        $isOvernight2 = $end2->lt($start2);
        
        if ($isOvernight1 && $isOvernight2) {
            // Both are overnight - they overlap if they share any time
            return true; // For simplicity, consider all overnight ranges as potentially overlapping
        } elseif ($isOvernight1) {
            // Range 1 is overnight, range 2 is not
            return $start2->lt($end1) || $end2->gt($start1);
        } elseif ($isOvernight2) {
            // Range 2 is overnight, range 1 is not
            return $start1->lt($end2) || $end1->gt($start2);
        } else {
            // Neither is overnight - standard overlap check
            return $start1->lt($end2) && $end1->gt($start2);
        }
    }

    /**
     * Deactivate existing schedules for selected weekdays
     */
    private function deactivateExistingSchedules(int $userId, int $employeeShiftId, array $weekdays): void
    {
        WorkSchedule::where('user_id', $userId)
            ->where('employee_shift_id', $employeeShiftId)
            ->whereIn('weekday', $weekdays)
            ->active()
            ->update(['is_active' => false]);
    }

    /**
     * Create work schedule items
     */
    private function createWorkScheduleItems(WorkSchedule $schedule, array $workItems, bool $sameForAll): void
    {
        foreach ($workItems as $item) {
            // Validate required fields
            if (empty($item['start_time']) || empty($item['end_time']) || empty($item['work_type']) || empty($item['work_title'])) {
                throw new Exception('Work item data is incomplete. All fields are required.');
            }

            WorkScheduleItem::create([
                'work_schedule_id' => $schedule->id,
                'start_time' => $item['start_time'],
                'end_time' => $item['end_time'],
                'work_type' => $item['work_type'],
                'work_title' => $item['work_title'],
                'duration_minutes' => $this->calculateDuration($item['start_time'], $item['end_time']),
            ]);
        }
    }

    /**
     * Calculate duration in minutes between two times
     */
    private function calculateDuration(string $startTime, string $endTime): int
    {
        try {
            // Handle different time formats
            $start = Carbon::createFromFormat('H:i', $startTime);
            $end = Carbon::createFromFormat('H:i', $endTime);
            
            if (!$start || !$end) {
                throw new Exception("Invalid time format: start='{$startTime}', end='{$endTime}'");
            }

            // Check if this is an overnight shift (end time is "before" start time)
            if ($end->lt($start)) {
                // For overnight shifts, add 24 hours to end time for calculation
                $end->addDay();
            }

            return $end->diffInMinutes($start);
        } catch (Exception $e) {
            throw new Exception("Error calculating duration: " . $e->getMessage());
        }
    }

    /**
     * Get Gantt chart cell data for a specific user and weekday
     */
    public function getGanttCellData(array $userData, string $weekday): array
    {
        $cells = [];
        
        for ($hour = 0; $hour <= 23; $hour++) {
            $cellData = [
                'hour' => $hour,
                'bgClass' => '',
                'title' => '',
                'hasSchedule' => false,
                'workType' => ''
            ];
            
            foreach ($userData['schedules'] as $schedule) {
                $startTime = $schedule['start_time']->format('H:i');
                $endTime = $schedule['end_time']->format('H:i');
                $startHour = (int) substr($startTime, 0, 2);
                $startMinute = (int) substr($startTime, 3, 2);
                $endHour = (int) substr($endTime, 0, 2);
                $endMinute = (int) substr($endTime, 3, 2);

                // Check if this schedule overlaps with this 1-hour period
                $overlaps = false;
                
                // Handle overnight shifts (end time is earlier than start time)
                if ($endHour < $startHour) {
                    // Overnight shift: check if current hour falls within the overnight period
                    // For overnight shifts, include both the start hour and end hour
                    if ($hour >= $startHour || $hour <= $endHour) {
                        $overlaps = true;
                    }
                } else {
                    // Regular shift: check if current hour falls within the period
                    // For regular shifts, include both the start hour and end hour
                    if ($hour >= $startHour && $hour <= $endHour) {
                        $overlaps = true;
                    }
                }

                if ($overlaps) {
                    $cellData['hasSchedule'] = true;
                    $cellData['bgClass'] = $this->getWorkTypeBgClass($schedule['work_type']);
                    $cellData['title'] = $schedule['work_type'] . ': ' . $schedule['work_title'] . ' (' . $startTime . ' - ' . $endTime . ')';
                    $cellData['workType'] = $schedule['work_type'];
                    break; // Use the first overlapping schedule
                }
            }
            
            $cells[] = $cellData;
        }
        
        return $cells;
    }

    /**
     * Get Bootstrap background class for work type
     */
    private function getWorkTypeBgClass(string $workType): string
    {
        switch (strtolower($workType)) {
            case 'client':
            case 'client work':
                return 'bg-success';
            case 'internal':
            case 'internal work':
                return 'bg-warning';
            case 'bench':
            case 'bench work':
                return 'bg-primary';
            default:
                return 'bg-secondary';
        }
    }

}
