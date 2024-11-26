<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class IncomeExpenseCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = IncomeExpenseCategory::withCount(['incomes', 'expenses'])
                        ->withSum(['incomes as total_income' => function ($query) {
                            $query->select(DB::raw('SUM(total)'));
                        }], 'total')
                        ->withSum(['expenses as total_expense' => function ($query) {
                            $query->select(DB::raw('SUM(total)'));
                        }], 'total')
                        ->orderBy('name', 'asc')
                        ->get();

        return view('administration.accounts.income_expense.category.index', compact(['categories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            IncomeExpenseCategory::create([
                'name' => $request->name,
                'is_active' => $request->is_active ? true : false,
                'description' => $request->description ?? NULL,
            ]);

            toast('Income & Expense Category Stored Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomeExpenseCategory $category)
    {
        $category = IncomeExpenseCategory::whereId($category->id)
                                        ->withCount(['incomes', 'expenses'])
                                        ->withSum(['incomes as total_income' => function ($query) {
                                            $query->select(DB::raw('SUM(total)'));
                                        }], 'total')
                                        ->withSum(['expenses as total_expense' => function ($query) {
                                            $query->select(DB::raw('SUM(total)'));
                                        }], 'total')
                                        ->firstOrFail();
                                        
        return view('administration.accounts.income_expense.category.show', compact(['category']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomeExpenseCategory $category)
    {
        try {
            $category->update([
                'name' => $request->name,
                'is_active' => $request->is_active ? true : false,
                'description' => $request->description ?? NULL,
            ]);

            toast('Income & Expense Category Updated Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomeExpenseCategory $category)
    {
        //
    }
}
