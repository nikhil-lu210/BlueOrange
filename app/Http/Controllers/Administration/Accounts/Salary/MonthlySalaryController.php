<?php

namespace App\Http\Controllers\Administration\Accounts\Salary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Services\Administration\SalaryService\SalaryService;
use App\Services\Administration\Attendance\AttendanceService;

class MonthlySalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $monthly_salaries = MonthlySalary::with(['user', 'salary'])->orderByDesc('created_at')->get();

        return view('administration.accounts.salary.monthly.index', compact(['monthly_salaries']));
    }

    /**
     * Re-Generate Salary.
     */
    public function reGenerateSalary(MonthlySalary $monthly_salary)
    {
        if ($monthly_salary->status && $monthly_salary->status == 'Paid') {
            alert('Warning!', 'You cannot re-generate the salary which has been already marked as Paid.', 'warning');
            return redirect()->back();
        }
        $salaryService = new SalaryService();
        
        $salaryService->calculateMonthlySalary($monthly_salary->user, $monthly_salary->for_month);

        $updatedMonthlySalary = MonthlySalary::whereUserId($monthly_salary->user_id)
                                        ->whereSalaryId($monthly_salary->salary_id)
                                        ->whereForMonth($monthly_salary->for_month)
                                        ->latest()
                                        ->firstOrFail();
                                        
        toast('Salary of '.$updatedMonthlySalary->user->name.' Has Been Re-Generated Successfully.', 'success');
        return redirect()->route('administration.accounts.salary.monthly.show', ['monthly_salary' => $updatedMonthlySalary]);
    }

    /**
     * Display the specified resource.
     */
    public function show(MonthlySalary $monthly_salary)
    {
        $month = Carbon::parse($monthly_salary->for_month);

        // Get total worked hours from the attendances table
        $attendanceService = new AttendanceService();
        $totalWorkedRegular = $attendanceService->userTotalWorkingHour($monthly_salary->user, 'Regular', $month);
        $totalWorkedOvertime = $attendanceService->userTotalWorkingHour($monthly_salary->user, 'Overtime', $month);

        $salary = [
            'total_worked_regular' => $totalWorkedRegular,
            'total_worked_overtime' => $totalWorkedOvertime,
            'earnings' => $monthly_salary->monthly_salary_breakdowns()->whereType('Plus (+)')->get(),
            'deductions' => $monthly_salary->monthly_salary_breakdowns()->whereType('Minus (-)')->get(),
            'total_earning' => $monthly_salary->monthly_salary_breakdowns()->whereType('Plus (+)')->sum('total'),
            'total_deduction' => $monthly_salary->monthly_salary_breakdowns()->whereType('Minus (-)')->sum('total'),
        ];
        
        return view('administration.accounts.salary.monthly.show', compact(['monthly_salary', 'salary']));
        // return view('administration.accounts.salary.monthly.generate_pdf', compact(['monthly_salary', 'salary']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MonthlySalary $monthly_salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MonthlySalary $monthly_salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MonthlySalary $monthly_salary)
    {
        //
    }
}
