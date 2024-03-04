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
        $this->middleware('auth'); // Ensure the user is authenticated
        $this->user = Auth::user();   
    }

    public function index()
    {
        $user = $this->user;
        $user = $user->with(['current_salary'])->firstOrFail();

        dd($user);
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
