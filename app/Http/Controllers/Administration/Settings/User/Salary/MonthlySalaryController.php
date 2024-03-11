<?php

namespace App\Http\Controllers\Administration\Settings\User\Salary;

use App\Http\Controllers\Controller;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\User;
use Illuminate\Http\Request;

class MonthlySalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $monthly_salaries = $user->monthly_salaries;
        
        return view('administration.settings.user.salary.monthly.index', compact(['user', 'monthly_salaries']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user, MonthlySalary $monthly_salary)
    {
        return view('administration.settings.user.salary.monthly.show', compact(['user', 'monthly_salary']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, MonthlySalary $monthly_salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, MonthlySalary $monthly_salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, MonthlySalary $monthly_salary)
    {
        //
    }
}
