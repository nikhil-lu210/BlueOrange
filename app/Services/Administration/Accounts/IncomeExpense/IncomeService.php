<?php

namespace App\Services\Administration\Accounts\IncomeExpense;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\IncomeExpense\Income;
use Illuminate\Support\Facades\Auth;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class IncomeService
{
    /**
     * Fetch active income categories.
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
     * Get filtered incomes based on the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Support\Collection
     */
    public function getFilteredIncomes(Request $request)
    {
        $query = Income::query()->with(['category', 'creator'])->orderByDesc('created_at');

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
            $monthYear = Carbon::createFromFormat('F Y', $request->input('for_month'));
            $query->whereYear('date', $monthYear->year)
                  ->whereMonth('date', $monthYear->month);
        } elseif (!$request->has('filter_incomes')) {
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d')
            ]);
        }
    }


    /**
     * Store a new income record along with associated files.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function storeIncome(Request $request): void
    {
        DB::transaction(function () use ($request) {
            // Create the income record
            $income = Income::create([
                'creator_id' => Auth::id(),
                'category_id' => $request->category_id,
                'date' => $request->date,
                'source' => $request->source,
                'total' => $request->total,
                'description' => $request->description,
            ]);

            // Store associated files if any
            if ($request->has('files') && is_array($request->files)) {
                foreach ($request->files as $file) {
                    $directory = 'income_expenses/income';
                    store_file_media($file, $income, $directory);
                }
            }
        }, 5);
    }

    /**
     * Update an existing income record along with associated files.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\IncomeExpense\Income $income
     * @return void
     * @throws \Exception
     */
    public function updateIncome(Request $request, Income $income): void
    {
        DB::transaction(function () use ($request, $income) {
            // Update the income record
            $income->update([
                'category_id' => $request->category_id,
                'date' => $request->date,
                'source' => $request->source,
                'total' => $request->total,
                'description' => $request->description,
            ]);

            // Store associated files if any
            if ($request->has('files') && is_array($request->files)) {
                foreach ($request->files as $file) {
                    $directory = 'income_expenses/income';
                    store_file_media($file, $income, $directory);
                }
            }
        }, 5);
    }
}
