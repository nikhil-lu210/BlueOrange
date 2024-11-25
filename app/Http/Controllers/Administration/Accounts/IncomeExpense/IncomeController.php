<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class IncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = IncomeExpenseCategory::select(['id', 'name', 'is_active'])->whereIsActive(true)->orderBy('name', 'asc')->get();

        $incomes = $this->getFilteredIncomes($request);
        
        return view('administration.accounts.income_expense.income.index', compact(['categories', 'incomes']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = IncomeExpenseCategory::select(['id', 'name', 'is_active'])->whereIsActive(true)->orderBy('name', 'asc')->get();

        return view('administration.accounts.income_expense.income.create', compact(['categories']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request['files']);
        $request->validate([
            'category_id' => ['required','integer','exists:income_expense_categories,id'],
            'date' => ['required','date'],
            'source' => ['required','string','min:5', 'max:200'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required','string','min:10'],
        ]);
        
        try {
            DB::transaction(function () use ($request) {
                $income = Income::create([
                    'creator_id' => auth()->id(),
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
            
            toast('Income Stored Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            // dd($e->getMessage());
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
        $categories = IncomeExpenseCategory::select(['id', 'name', 'is_active'])->whereIsActive(true)->orderBy('name', 'asc')->get();
        
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

    /**
     * Helper method to filter Incomes
     */
    private function getFilteredIncomes(Request $request)
    {
        $query = Income::query()->with(['category', 'creator'])->orderByDesc('created_at');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('for_month')) {
            $monthYear = Carbon::createFromFormat('F Y', $request->for_month);
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_incomes')) {
            // Default to current month
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }

        return $query->get();
    }
}
