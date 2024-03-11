<?php

namespace App\Http\Controllers\Administration\Profile\Salary;

use App\Http\Controllers\Controller;
use App\Models\Salary\Monthly\MonthlySalary;
use App\Models\Salary\Salary;
use Auth;

class SalaryController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->user = Auth::user();

            return $next($request);
        });
    }

    public function index()
    {
        $user = $this->user;

        $salaries = $user->salaries;

        return view('administration.profile.includes.salary.index', compact(['user', 'salaries']));
    }

    public function show(Salary $salary)
    {
        $user = $this->user;
        // Make sure the requested salary belongs to the currently authenticated user
        if ($salary->user_id !== $user->id) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED');
        }

        return view('administration.profile.includes.salary.show', compact(['user', 'salary']));
    }

    public function monthly()
    {
        $user = $this->user;

        $monthly_salaries = $user->monthly_salaries;

        return view('administration.profile.includes.salary.monthly.index', compact(['user', 'monthly_salaries']));
    }

    public function monthlyShow(MonthlySalary $monthly_salary)
    {
        $user = $this->user;
        // Make sure the requested salary belongs to the currently authenticated user
        if ($monthly_salary->user_id !== $user->id) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED');
        }

        // dd($user, $monthly_salary);

        return view('administration.profile.includes.salary.monthly.show', compact(['user', 'monthly_salary']));
    }
}
