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
    public function show($salary_id, $userid, $id)
    {
        $monthly_salary = MonthlySalary::whereId($id)->whereSalaryId($salary_id)->firstOrFail();
        
        $salaryService = new SalaryService();
        $salary = $salaryService->getSalaryDetails($monthly_salary);

        return view('administration.accounts.salary.monthly.generate_pdf', compact(['monthly_salary', 'salary']));
    }
}
