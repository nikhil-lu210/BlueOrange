<?php

namespace App\Http\Controllers\Administration\Settings\User\Salary;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Salary\Salary;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Settings\User\Salary\SalaryUpdateRequest;

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
        return view('administration.settings.user.salary.create', compact(['user']));
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
     * Update the specified resource in storage.
     */
    public function update(SalaryUpdateRequest $request, User $user, Salary $salary)
    {
        // dd($request->all(), $user, $salary);
        try {
            DB::transaction(function() use ($request, $salary) {
                $salary->update([
                    'basic_salary' => $request->basic_salary,
                    'house_benefit' => $request->house_benefit,
                    'transport_allowance' => $request->transport_allowance,
                    'medical_allowance' => $request->medical_allowance,
                    'night_shift_allowance' => $request->night_shift_allowance,
                    'other_allowance' => $request->other_allowance
                ]);

                $totalSalary = $request->basic_salary + $request->house_benefit + $request->transport_allowance + $request->medical_allowance + $request->night_shift_allowance + $request->other_allowance;
                
                $salary->total = $totalSalary;
                $salary->save();
            }, 5);

            toast('Salary has been updated.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user, Salary $salary)
    {
        //
    }
}
