<?php

namespace App\Services\Administration\SalaryService;

use Exception;
use Carbon\Carbon;
use App\Models\Salary\Salary;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;
use Illuminate\Support\Facades\DB;
use App\Models\Attendance\Attendance;
use App\Models\EmployeeShift\EmployeeShift;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\Salary\Monthly\MonthlySalaryBreakdown;
use App\Models\User;

class SalaryService
{
    public function calculateMonthlySalary(User $user, $month = null)
    {
        if (!$user->current_salary) { // App\Models\User\Traits
            // Skip to the next user if no salary record found
            return;
        }

        $month = $this->getMonth($month);

        DB::beginTransaction();

        try {
            $activeWeekends = $this->getActiveWeekends();
            $holidays = $this->getHolidaysForMonth($month);

            $workableDays = $this->calculateWorkableDays($month, $activeWeekends, $holidays);
            $shift = $this->getEmployeeShift($user);
            $dailyWorkHours = $this->getDailyWorkHours($shift);
            $totalWorkableSeconds = $this->calculateTotalWorkableSeconds($workableDays, $dailyWorkHours);
            
            $salary = $this->getEmployeeSalary($user);
            $hourlyRate = $this->calculateHourlyRate($salary, $totalWorkableSeconds);

            $totalRegularTimeInSeconds = $this->getTotalRegularTimeInSeconds($user, $month);
            $totalOvertimeInSeconds = $this->getTotalOvertimeInSeconds($user, $month);
            $totalPayable = $this->calculateTotalPayable($totalRegularTimeInSeconds, $totalOvertimeInSeconds, $hourlyRate);

            $totalOverBreakInSeconds = $this->getTotalOverBreakInSeconds($user, $month);
            $overBreakPenaltyAmount = $this->calculateOverBreakPenalty($totalOverBreakInSeconds, $hourlyRate);
            $totalPayable -= $overBreakPenaltyAmount;

            // Calculate total weekends for the specific month
            $totalWeekends = $this->calculateTotalWeekendsForMonth($month);

            // Update or create the monthly salary record, including the new fields
            $monthlySalaryId = $this->updateOrCreateMonthlySalary($user, $salary, $month, $totalPayable, $workableDays, $totalWeekends, count($holidays), $hourlyRate);
            $this->updateMonthlySalaryBreakdowns($monthlySalaryId, $totalRegularTimeInSeconds, $totalOvertimeInSeconds, $totalOverBreakInSeconds, $hourlyRate, $overBreakPenaltyAmount);

            DB::commit();
            return round($totalPayable, 2);
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function getMonth($month)
    {
        return $month === null ? Carbon::now()->subMonth() : Carbon::parse($month);
    }

    private function getActiveWeekends()
    {
        return Weekend::where('is_active', true)->pluck('day')->toArray();
    }

    private function calculateTotalWeekendsForMonth($month)
    {
        // Get the start and end date of the month
        $startOfMonth = $month->copy()->startOfMonth();
        $endOfMonth = $month->copy()->endOfMonth();
        
        // Initialize the count of weekends
        $totalWeekends = 0;

        // Loop through each day of the month
        for ($date = $startOfMonth; $date->lte($endOfMonth); $date->addDay()) {
            // Check if the day is Saturday (6) or Sunday (0)
            if ($date->isSaturday() || $date->isSunday()) {
                $totalWeekends++;
            }
        }

        return $totalWeekends;
    }

    private function getHolidaysForMonth($month)
    {
        return Holiday::whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->where('is_active', true)
            ->pluck('date')
            ->toArray();
    }

    private function calculateWorkableDays($month, $activeWeekends, $holidays)
    {
        $daysInMonth = $month->daysInMonth;
        $dates = collect(range(1, $daysInMonth))->map(function ($day) use ($month) {
            return Carbon::createFromDate($month->year, $month->month, $day);
        });

        return $dates->filter(function ($date) use ($activeWeekends, $holidays) {
            return !in_array($date->format('l'), $activeWeekends) && !in_array($date->toDateString(), $holidays);
        })->count();
    }

    private function getEmployeeShift($user)
    {
        return $user->current_shift; // App\Models\User\Traits
    }

    private function getDailyWorkHours($shift)
    {
        return Carbon::parse($shift->start_time)->diffInSeconds(Carbon::parse($shift->end_time));
    }

    private function calculateTotalWorkableSeconds($workableDays, $dailyWorkHours)
    {
        return $workableDays * $dailyWorkHours;
    }

    private function getEmployeeSalary($user)
    {
        return $user->current_salary; // App\Models\User\Traits
    }

    private function calculateHourlyRate($salary, $totalWorkableSeconds)
    {
        return $salary->total / ($totalWorkableSeconds / 3600);
    }

    private function getTotalRegularTimeInSeconds($user, $month)
    {
        return Attendance::where('user_id', $user->id)
            ->where('type', 'Regular')
            ->whereYear('clock_in_date', $month->year)
            ->whereMonth('clock_in_date', $month->month)
            ->sum(DB::raw('TIME_TO_SEC(total_adjusted_time)'));
    }

    private function getTotalOvertimeInSeconds($user, $month)
    {
        return Attendance::where('user_id', $user->id)
            ->where('type', 'Overtime')
            ->whereYear('clock_in_date', $month->year)
            ->whereMonth('clock_in_date', $month->month)
            ->sum(DB::raw('TIME_TO_SEC(total_adjusted_time)'));
    }

    private function calculateTotalPayable($totalRegularTimeInSeconds, $totalOvertimeInSeconds, $hourlyRate)
    {
        return (($totalRegularTimeInSeconds / 3600) * $hourlyRate) +
               (($totalOvertimeInSeconds / 3600) * $hourlyRate);
    }

    private function getTotalOverBreakInSeconds($user, $month)
    {
        return DB::table('daily_breaks')
            ->where('user_id', $user->id)
            ->whereYear('date', $month->year)
            ->whereMonth('date', $month->month)
            ->sum(DB::raw('TIME_TO_SEC(over_break)'));
    }

    private function calculateOverBreakPenalty($totalOverBreakInSeconds, $hourlyRate)
    {
        // Convert total overbreak seconds to hours
        $totalOverBreakInHours = $totalOverBreakInSeconds / 3600; // Total overbreak in hours

        // Calculate penalty amount
        $overBreakPenaltyAmount = $totalOverBreakInHours * $hourlyRate; // Penalty based on hourly rate

        return round($overBreakPenaltyAmount, 2); // Return rounded to 2 decimal places
    }

    private function updateOrCreateMonthlySalary($user, $salary, $month, $totalPayable, $totalWorkableDays, $totalWeekends, $totalHolidays, $hourlyRate)
    {
        $forMonthString = $month->format('Y-m');

        // Hard delete any existing salary record for the given user and month
        MonthlySalary::where('user_id', $user->id)
            ->where('for_month', $forMonthString)
            ->forceDelete(); // Use forceDelete() for hard delete

        // Create a new monthly salary record
        $newMonthlySalary = MonthlySalary::create([
            'user_id' => $user->id,
            'salary_id' => $salary->id,
            'for_month' => $forMonthString,
            'total_payable' => round($totalPayable, 2),
            'status' => 'Pending',
            'total_workable_days' => $totalWorkableDays,
            'total_weekends' => $totalWeekends,        
            'total_holidays' => $totalHolidays,        
            'hourly_rate' => $hourlyRate,              
        ]);

        return $newMonthlySalary->id; // Return the ID of the newly created record
    }

    private function updateMonthlySalaryBreakdowns($monthlySalaryId, $totalRegularTimeInSeconds, $totalOvertimeInSeconds, $totalOverBreakInSeconds, $hourlyRate, $overBreakPenaltyAmount)
    {
        $this->updateOrCreateRegularBreakdown($monthlySalaryId, $totalRegularTimeInSeconds, $hourlyRate);
        $this->updateOrCreateOvertimeBreakdown($monthlySalaryId, $totalOvertimeInSeconds, $hourlyRate);
        $this->updateOrCreateOverbreakBreakdown($monthlySalaryId, $totalOverBreakInSeconds, $overBreakPenaltyAmount);
    }

    private function updateOrCreateRegularBreakdown($monthlySalaryId, $totalRegularTimeInSeconds, $hourlyRate)
    {
        $existingRegularBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
            ->where('reason', 'LIKE', 'Regular Work%')
            ->first();

        $regularWorkAmount = round(($totalRegularTimeInSeconds / 3600) * $hourlyRate, 2);

        if ($existingRegularBreakdown) {
            $existingRegularBreakdown->update([
                'total' => $regularWorkAmount,
            ]);
        } else {
            MonthlySalaryBreakdown::create([
                'monthly_salary_id' => $monthlySalaryId,
                'type' => 'Plus (+)',
                'reason' => 'Regular Work (' . gmdate('H:i:s', $totalRegularTimeInSeconds) . ')',
                'total' => $regularWorkAmount,
            ]);
        }
    }

    private function updateOrCreateOvertimeBreakdown($monthlySalaryId, $totalOvertimeInSeconds, $hourlyRate)
    {
        if ($totalOvertimeInSeconds > 0) {
            $existingOvertimeBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
                ->where('reason', 'LIKE', 'Overtime Work%')
                ->first();

            $overtimeWorkAmount = round(($totalOvertimeInSeconds / 3600) * $hourlyRate, 2);

            if ($existingOvertimeBreakdown) {
                $existingOvertimeBreakdown->update([
                    'total' => $overtimeWorkAmount,
                ]);
            } else {
                MonthlySalaryBreakdown::create([
                    'monthly_salary_id' => $monthlySalaryId,
                    'type' => 'Plus (+)',
                    'reason' => 'Overtime Work (' . gmdate('H:i:s', $totalOvertimeInSeconds) . ')',
                    'total' => $overtimeWorkAmount,
                ]);
            }
        }
    }

    private function updateOrCreateOverbreakBreakdown($monthlySalaryId, $totalOverBreakInSeconds, $overBreakPenaltyAmount)
    {
        if ($totalOverBreakInSeconds > 0) {
            $existingOverBreakBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
                ->where('reason', 'LIKE', 'Over Break%')
                ->first();

            if ($existingOverBreakBreakdown) {
                $existingOverBreakBreakdown->update([
                    'total' => $overBreakPenaltyAmount,
                ]);
            } else {
                MonthlySalaryBreakdown::create([
                    'monthly_salary_id' => $monthlySalaryId,
                    'type' => 'Minus (-)',
                    'reason' => 'Over Break (' . gmdate('H:i:s', $totalOverBreakInSeconds) . ')',
                    'total' => $overBreakPenaltyAmount,
                ]);
            }
        }
    }
}
