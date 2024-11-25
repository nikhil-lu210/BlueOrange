<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpense\Expense;
use App\Services\Administration\Accounts\IncomeExpense\ExpenseService;
use App\Http\Requests\Administration\Accounts\IncomeExpense\Expense\ExpenseStoreRequest;
use App\Http\Requests\Administration\Accounts\IncomeExpense\Expense\ExpenseUpdateRequest;

class ExpenseController extends Controller
{
    protected $expenseService;

    /**
     * Inject expenseService into the controller.
     */
    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = $this->expenseService->getActiveCategories();
        $expenses = $this->expenseService->getFilteredExpenses($request);

        $total_overall_expense = Expense::getTotalOverallExpense();
        $last_month_total_expense = Expense::getLastMonthTotalExpense();
        $current_month_total_expense = Expense::getCurrentMonthTotalExpense();

        $total = [
            'overall_expense' => Expense::getTotalOverallExpense(),
            'last_month_expense' => Expense::getLastMonthTotalExpense(),
            'current_month_expense' => Expense::getCurrentMonthTotalExpense(),
            'expense' => $expenses->sum('total'),
        ];

        return view('administration.accounts.income_expense.expense.index', compact(['categories', 'expenses', 'total']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->expenseService->getActiveCategories();

        return view('administration.accounts.income_expense.expense.create', compact(['categories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ExpenseStoreRequest $request)
    {
        try {
            $this->expenseService->storeExpense($request);

            toast('Expence Stored Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('administration.accounts.income_expense.expense.show', compact(['expense']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $categories = $this->expenseService->getActiveCategories();
        
        return view('administration.accounts.income_expense.expense.edit', compact(['categories', 'expense']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ExpenseUpdateRequest $request, Expense $expense)
    {
        try {
            $this->expenseService->updateExpense($request, $expense);

            toast('Expense Updated Successfully.', 'success');
            return redirect()->route('administration.accounts.income_expense.expense.show', ['expense' => $expense]);
        } catch (Exception $e) {
            alert('Error!', $e->getMessage(), 'error');
            return redirect()->back()->withInput()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        try {
            $expense->delete();
            
            toast('Expense deleted successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            alert('Oops! Error.', $e->getMessage(), 'error');
            return redirect()->back();
        }
    }
}
