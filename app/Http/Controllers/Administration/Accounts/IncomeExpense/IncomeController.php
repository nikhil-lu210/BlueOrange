<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Accounts\IncomeExpense\Income\IncomeStoreRequest;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\IncomeExpenseCategory;
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

        return view('administration.accounts.income_expense.income.index', compact(['categories', 'incomes']));
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
        $this->incomeService->getActiveCategories();
        
        return view('administration.accounts.income_expense.income.edit', compact(['categories', 'income']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Income $income)
    {
        // dd($income->toArray(), $request->all());
        $request->validate([
            'category_id' => ['sometimes','integer','exists:income_expense_categories,id'],
            'date' => ['sometimes','date'],
            'source' => ['sometimes','string','min:5', 'max:200'],
            'total' => ['sometimes', 'numeric', 'min:0.01'],
            'description' => ['sometimes','string','min:10'],
        ]);

        try {
            DB::transaction(function () use ($request, $income) {
                $income->update([
                    'category_id' => $request->category_id,
                    'date' => $request->date,
                    'source' => $request->source,
                    'total' => $request->total,
                    'description' => $request->description
                ]);

                // Check and store associated files if provided in the 'files' key
                if (isset($request['files']) && !empty($request['files'])) {
                    foreach ($request['files'] as $file) {
                        $directory = 'income_expenses/income';
                        store_file_media($file, $income, $directory);
                    }
                }
            }, 5);
            
            toast('Income Updated Successfully.', 'success');
            return redirect()->route('administration.accounts.income_expense.income.show', ['income' => $income]);
        } catch (Exception $e) {
            // dd($e->getMessage());
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
