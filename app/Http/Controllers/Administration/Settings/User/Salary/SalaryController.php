<?php

namespace App\Http\Controllers\Administration\Settings\User\Salary;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Salary\Salary;
use Illuminate\Support\Number;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Settings\User\Salary\SalaryStoreRequest;
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
    public function store(SalaryStoreRequest $request, User $user)
    {
        // dd($request->all());
        $salary = null;
        try {
            DB::transaction(function() use ($request, $user, &$salary) {
                if ($user->current_salary) {
                    $user->current_salary->update([
                        'implemented_to' => date('Y-m-d'),
                        'status' => 'Inactive'
                    ]);
                }

                $salary = Salary::create([
                    'user_id' => $user->id,
                    'basic_salary' => $request->basic_salary,
                    'house_benefit' => $request->house_benefit,
                    'transport_allowance' => $request->transport_allowance,
                    'medical_allowance' => $request->medical_allowance,
                    'night_shift_allowance' => $request->night_shift_allowance,
                    'other_allowance' => $request->other_allowance,
                    'implemented_from' => date('Y-m-d'),
                    'total' => 0
                ]);
                // dd($salary);

                $totalSalary = $request->basic_salary + $request->house_benefit + $request->transport_allowance + $request->medical_allowance + $request->night_shift_allowance + $request->other_allowance;
                // dd($totalSalary);
                $salary->total = $totalSalary;
                $salary->save();
            }, 5);
            
            toast('User Salary has been Upgraded.', 'success');
            return redirect()->route('administration.settings.user.salary.show', ['user' => $user, 'salary' => $salary]);
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->withErrors($e->getMessage());
        }
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
        if ($salary->monthly_salaries->count() > 0) {
            toast('You cannot Update Salary as this Salary has already been paid one or more times.', 'warning');
            return redirect()->back();
        }
        
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
