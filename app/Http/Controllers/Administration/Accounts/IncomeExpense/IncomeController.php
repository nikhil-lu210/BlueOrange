<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Accounts\IncomeExpense\Income\IncomeStoreRequest;
use App\Http\Requests\Administration\Accounts\IncomeExpense\Income\IncomeUpdateRequest;
use App\Models\IncomeExpense\Income;
use App\Services\Administration\Accounts\IncomeExpense\IncomeService;

class IncomeController extends Controller
{
    protected $incomeService;

    /**
     * Inject IncomeService into the controller.
     */
    public function __construct(IncomeService $incomeService)
    {
        $this->incomeService = $incomeService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->incomeService->getActiveCategories();
        $incomes = $this->incomeService->getFilteredIncomes($request);

        $total_overall_income = Income::getTotalOverallIncome();
        $last_month_total_income = Income::getLastMonthTotalIncome();
        $current_month_total_income = Income::getCurrentMonthTotalIncome();

        $total = [
            'overall_income' => Income::getTotalOverallIncome(),
            'last_month_income' => Income::getLastMonthTotalIncome(),
            'current_month_income' => Income::getCurrentMonthTotalIncome(),
            'income' => $incomes->sum('total'),
        ];

        return view('administration.accounts.income_expense.income.index', compact(['categories', 'incomes', 'total']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->incomeService->getActiveCategories();

        return view('administration.accounts.income_expense.income.create', compact(['categories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(IncomeStoreRequest $request)
    {
        try {
            $this->incomeService->storeIncome($request);

            toast('Income Stored Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Income $income)
    {
        return view('administration.accounts.income_expense.income.show', compact(['income']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Income $income)
    {
        $categories = $this->incomeService->getActiveCategories();
        
        return view('administration.accounts.income_expense.income.edit', compact(['categories', 'income']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(IncomeUpdateRequest $request, Income $income)
    {
        try {
            $this->incomeService->updateIncome($request, $income);

            toast('Income Updated Successfully.', 'success');
            return redirect()->route('administration.accounts.income_expense.income.show', ['income' => $income]);
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Income $income)
    {
        try {
            $income->delete();
            
            toast('Income deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
