<?php

namespace App\Http\Controllers\Administration\Accounts\Salary;

use Illuminate\Http\Request;
use App\Models\Salary\Salary;
use App\Http\Controllers\Controller;
use App\Models\Salary\Monthly\MonthlySalary;

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
    public function show(Salary $salary)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Salary $salary)
    {
        //
    }
}
