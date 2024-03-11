<?php

namespace App\Http\Controllers\Administration\Profile\Salary;

use App\Http\Controllers\Controller;
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

    public function monthly()
    {
        $user = $this->user;

        $monthly_salaries = $user->monthly_salaries;

        return view('administration.profile.includes.salary.history', compact(['user', 'monthly_salaries']));
    }

    public function show(Salary $salary)
    {
        $user = $this->user;
        // Make sure the requested salary belongs to the currently authenticated user
        if ($salary->user_id !== $user->id) {
            abort(403, 'THIS ACTION IS UNAUTHORIZED');
        }

        dd($user, $salary);
    }
}
