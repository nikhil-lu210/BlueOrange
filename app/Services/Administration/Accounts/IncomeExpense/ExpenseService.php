<?php

namespace App\Services\Administration\Accounts\IncomeExpense;

use App\Models\IncomeExpense\Expense;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class ExpenseService
{
    /**
     * Fetch active expense categories.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveCategories()
    {
        return IncomeExpenseCategory::query()
            ->select(['id', 'name', 'is_active'])
            ->whereIsActive(true)
            ->orderBy('name', 'asc')
            ->get();
    }

    /**
     * Get filtered expenses based on the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getFilteredExpenses(Request $request)
    {
        $query = Expense::query()->with(['category', 'creator'])->orderByDesc('created_at');

        $this->applyCategoryFilter($query, $request->input('category_id'));
        $this->applyDateFilter($query, $request);

        return $query->get();
    }

    /**
     * Apply the category filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $categoryId
     */
    private function applyCategoryFilter($query, $categoryId)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
    }

    /**
     * Apply the date filter to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Http\Request $request
     */
    private function applyDateFilter($query, Request $request)
    {
        if ($request->filled('for_month')) {
            $monthYear = Carbon::parse($request->input('for_month'));
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_expenses')) {
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }
    }


    /**
     * Store a new Expense record along with associated files.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function storeExpense(Request $request): void
    {
        DB::transaction(function () use ($request) {
            // Create the Expense record
            $expense = Expense::create([
                'creator_id' => Auth::id(),
                'category_id' => $request->category_id,
                'date' => $request->date,
                'title' => $request->title,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total' => $request->quantity * $request->price,
                'description' => $request->description,
            ]);

            // Store associated files if any
            if ($request->has('files') && is_array($request->file('files'))) {
                foreach ($request->file('files') as $file) {
                    $directory = 'income_expenses/expense';
                    store_file_media($file, $expense, $directory);
                }
            }
        }, 5);
    }

    /**
     * Update an existing expense record along with associated files.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\IncomeExpense\Expense $expense
     * @return void
     * @throws \Exception
     */
    public function updateExpense(Request $request, Expense $expense): void
    {
        DB::transaction(function () use ($request, $expense) {
            // Update the expense record
            $expense->update([
                'category_id' => $request->category_id,
                'date' => $request->date,
                'title' => $request->title,
                'quantity' => $request->quantity,
                'price' => $request->price,
                'total' => $request->quantity * $request->price,
                'description' => $request->description,
            ]);

            // Store associated files if any
            if ($request->has('files') && is_array($request->file('files'))) {
                foreach ($request->file('files') as $file) {
                    $directory = 'income_expenses/expense';
                    store_file_media($file, $expense, $directory);
                }
            }
        }, 5);
    }
}
