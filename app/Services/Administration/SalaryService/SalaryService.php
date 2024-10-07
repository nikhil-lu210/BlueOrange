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

class SalaryService
{
    public function calculateMonthlySalary($userId, $month = null)
    {
        // If no month is provided, use the previous month
        if ($month === null) {
            $month = Carbon::now()->subMonth(); // Get the previous month (Sep 2024 if now is Oct 2024)
        } else {
            $month = Carbon::parse($month); // Parse the provided month if not null
        }

        // Wrap everything in a transaction
        DB::beginTransaction();
        
        try {
            // Get all weekend days for the month (e.g., 'Saturday', 'Sunday')
            $activeWeekends = Weekend::where('is_active', true)->pluck('day')->toArray();
            
            // Get holidays for the month
            $holidays = Holiday::whereYear('date', $month->year)
                               ->whereMonth('date', $month->month)
                               ->where('is_active', true)
                               ->pluck('date')
                               ->toArray();

            // Total days in the month
            $daysInMonth = Carbon::parse($month)->daysInMonth;
            
            // Get all dates in the month
            $dates = collect(range(1, $daysInMonth))->map(function ($day) use ($month) {
                return Carbon::createFromDate($month->year, $month->month, $day);
            });

            // Calculate total workable days (excluding weekends and holidays)
            $workableDays = $dates->filter(function ($date) use ($activeWeekends, $holidays) {
                return !in_array($date->format('l'), $activeWeekends) && !in_array($date->toDateString(), $holidays);
            })->count();

            // Get the employee's shift
            $shift = EmployeeShift::where('user_id', $userId)
                                  ->where('status', 'Active')
                                  ->first();

            // Calculate daily working hours
            $dailyWorkHours = Carbon::parse($shift->start_time)->diffInSeconds(Carbon::parse($shift->end_time));

            // Total workable hours for the month
            $totalWorkableSeconds = $workableDays * $dailyWorkHours;
            
            // Get employee salary
            $salary = Salary::where('user_id', $userId)
                            ->where('status', 'Active')
                            ->first();

            // Calculate hourly rate
            $hourlyRate = $salary->total / ($totalWorkableSeconds / 3600);

            // Get total regular and overtime hours from attendances
            $totalRegularTimeInSeconds = Attendance::where('user_id', $userId)
                                                   ->where('type', 'Regular')
                                                   ->whereYear('clock_in_date', $month->year)
                                                   ->whereMonth('clock_in_date', $month->month)
                                                   ->sum(DB::raw('TIME_TO_SEC(total_adjusted_time)'));

            $totalOvertimeInSeconds = Attendance::where('user_id', $userId)
                                                ->where('type', 'Overtime')
                                                ->whereYear('clock_in_date', $month->year)
                                                ->whereMonth('clock_in_date', $month->month)
                                                ->sum(DB::raw('TIME_TO_SEC(total_adjusted_time)'));

            // Calculate total payable salary
            $totalPayable = (($totalRegularTimeInSeconds / 3600) * $hourlyRate) +
                            (($totalOvertimeInSeconds / 3600) * $hourlyRate);

            // Get total over break time
            $totalOverBreakInSeconds = DB::table('daily_breaks')
                ->where('user_id', $userId)
                ->whereYear('date', $month->year)
                ->whereMonth('date', $month->month)
                ->sum(DB::raw('TIME_TO_SEC(over_break)'));

            // Calculate over break penalty
            $overBreakPenaltyAmount = round(($totalOverBreakInSeconds / 3600) * 6.59, 2);

            // Update total payable salary by subtracting over break penalty
            $totalPayable -= $overBreakPenaltyAmount;

            // Format month to 'Y-m' string (e.g., '2024-09')
            $forMonthString = $month->format('Y-m');

            // Check if there is already a record for this user and month in 'Y-m' format
            $existingSalary = MonthlySalary::where('user_id', $userId)
                                           ->where('for_month', $forMonthString) // Compare as string 'Y-m'
                                           ->first();

            if ($existingSalary) {
                // Update the existing record
                $existingSalary->update([
                    'total_payable' => round($totalPayable, 2),
                    'status' => 'Pending',
                ]);

                $monthlySalaryId = $existingSalary->id;
            } else {
                // Create a new record if none exists
                $newMonthlySalary = MonthlySalary::create([
                    'user_id' => $userId,
                    'salary_id' => $salary->id,
                    'for_month' => $forMonthString, // Store 'Y-m' format
                    'total_payable' => round($totalPayable, 2),
                    'status' => 'Pending',
                ]);

                $monthlySalaryId = $newMonthlySalary->id;
            }

            // Now insert into monthly_salary_breakdowns table
            // 1st Breakdown for Regular Work
            $existingRegularBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
                ->where('reason', 'LIKE', 'Regular Work%')
                ->first();

            $regularWorkAmount = round(($totalRegularTimeInSeconds / 3600) * $hourlyRate, 2);

            if ($existingRegularBreakdown) {
                // Update the existing regular work breakdown
                $existingRegularBreakdown->update([
                    'total' => $regularWorkAmount,
                ]);
            } else {
                // Create new breakdown for regular work
                MonthlySalaryBreakdown::create([
                    'monthly_salary_id' => $monthlySalaryId,
                    'type' => 'Plus (+)',
                    'reason' => 'Regular Work (' . gmdate('H:i:s', $totalRegularTimeInSeconds) . ')',
                    'total' => $regularWorkAmount,
                ]);
            }

            // 2nd Breakdown for Overtime Work, only if overtime exists
            if ($totalOvertimeInSeconds > 0) {
                $existingOvertimeBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
                    ->where('reason', 'LIKE', 'Overtime Work%')
                    ->first();

                $overtimeWorkAmount = round(($totalOvertimeInSeconds / 3600) * $hourlyRate, 2);

                if ($existingOvertimeBreakdown) {
                    // Update the existing overtime work breakdown
                    $existingOvertimeBreakdown->update([
                        'total' => $overtimeWorkAmount,
                    ]);
                } else {
                    // Create new breakdown for overtime work
                    MonthlySalaryBreakdown::create([
                        'monthly_salary_id' => $monthlySalaryId,
                        'type' => 'Plus (+)',
                        'reason' => 'Overtime Work (' . gmdate('H:i:s', $totalOvertimeInSeconds) . ')',
                        'total' => $overtimeWorkAmount,
                    ]);
                }
            }

            // 3rd Breakdown for Overbreak Penalty
            if ($totalOverBreakInSeconds > 0) {
                $existingOverbreakBreakdown = MonthlySalaryBreakdown::where('monthly_salary_id', $monthlySalaryId)
                    ->where('reason', 'LIKE', 'Overbreak Penalty%')
                    ->first();

                if ($existingOverbreakBreakdown) {
                    // Update existing overbreak penalty entry
                    $existingOverbreakBreakdown->update([
                        'total' => $overBreakPenaltyAmount,
                        'reason' => 'Overbreak Penalty (' . gmdate('H:i:s', $totalOverBreakInSeconds) . ')',
                    ]);
                } else {
                    // Create a new entry for overbreak penalty
                    MonthlySalaryBreakdown::create([
                        'monthly_salary_id' => $monthlySalaryId,
                        'type' => 'Minus (-)',
                        'reason' => 'Overbreak Penalty (' . gmdate('H:i:s', $totalOverBreakInSeconds) . ')',
                        'total' => $overBreakPenaltyAmount,
                    ]);
                }
            }

            // Commit the transaction
            DB::commit();

            return round($totalPayable, 2);

        } catch (Exception $e) {
            // Rollback on error
            DB::rollBack();
            throw $e;
        }
    }
}
