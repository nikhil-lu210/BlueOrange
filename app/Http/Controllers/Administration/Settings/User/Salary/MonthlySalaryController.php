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
}
