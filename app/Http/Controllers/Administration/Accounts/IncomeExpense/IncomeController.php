<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Carbon\Carbon;
use Illuminate\Http\Request;
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

    /**
     * Helper method to filter Incomes
     */
    private function getFilteredIncomes(Request $request)
    {
        $query = Income::query()->with(['category'])->orderByDesc('created_at');

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
