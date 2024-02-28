<?php

namespace App\Http\Controllers\Administration\Settings\User\Salary;

use App\Http\Controllers\Controller;
use App\Models\Salary\Salary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Number;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        // dd(Number::currency(1000, in: 'BDT'));
        $salaries = Salary::whereUserId($user->id)->get();

        return view('administration.settings.user.salary.index', compact(['user', 'salaries']));
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
    public function show(User $user, Salary $salary)
    {
        return view('administration.settings.user.salary.show', compact(['user', 'salary']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Salary $salary)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user, Salary $salary)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Salary $salary)
    {
        //
    }
}
