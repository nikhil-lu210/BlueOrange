<?php

namespace App\Http\Controllers\Administration\Settings\User\Salary;

use App\Http\Controllers\Controller;
use App\Models\Salary\Salary;
use App\Models\User;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $salaries = Salary::whereUserId($user->id)->get();
        
        return view('administration.settings.user.salary.index', compact(['user', 'salaries']));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
