<?php

namespace App\Http\Controllers\Application\Accounts\Salary;

use App\Http\Controllers\Controller;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Services\Administration\SalaryService\SalaryService;

class MonthlySalaryController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show($payslip_id, $userid, $id)
    {
        $id = decrypt($id);
        $monthly_salary = MonthlySalary::whereId($id)->wherePayslipId($payslip_id)->firstOrFail();
        // dd($monthly_salary);

        $salaryService = new SalaryService();
        $salary = $salaryService->getSalaryDetails($monthly_salary);

        return view('administration.accounts.salary.monthly.generate_pdf', compact(['monthly_salary', 'salary']));
    }
}
