<?php

namespace App\Http\Controllers\Administration\Accounts\Salary;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Holiday\Holiday;
use App\Models\Weekend\Weekend;
use App\Http\Controllers\Controller;
use App\Models\Attendance\Attendance;
use App\Models\Salary\Monthly\MonthlySalary;
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
