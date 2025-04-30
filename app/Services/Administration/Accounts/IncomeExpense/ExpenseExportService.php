<?php

namespace App\Services\Administration\Accounts\IncomeExpense;

use App\Models\IncomeExpense\Expense;
use Carbon\Carbon;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class ExpenseExportService
{
    /**
     * Export expenses based on request filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|null
     */
    public function export($request)
    {
        $query = $this->buildExpenseQuery($request);

        $filters = $this->extractFilters($request);
        $expenses = $query->get();

        if ($expenses->isEmpty()) {
            return null; // No expenses found
        }

        return [
            'expenses' => $expenses,
            'fileName' => $this->generateFileName($filters['categoryName'], $filters['monthYear']),
        ];
    }

    /**
     * Build the query for fetching expenses based on filters.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildExpenseQuery($request)
    {
        $query = Expense::with(['category', 'creator'])->orderByDesc('date');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('for_month')) {
            $monthYearDate = Carbon::parse($request->for_month);
            $query->whereYear('date', $monthYearDate->year)
                  ->whereMonth('date', $monthYearDate->month);
        } elseif (!$request->has('filter_expenses')) {
            $query->whereBetween('date', [
                Carbon::now()->startOfMonth()->format('Y-m-d'),
                Carbon::now()->endOfMonth()->format('Y-m-d'),
            ]);
        }

        return $query;
    }

    /**
     * Extract filters for category and month year from the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    private function extractFilters($request)
    {
        $categoryName = '';
        $monthYear = '';

        if ($request->filled('category_id')) {
            $category = IncomeExpenseCategory::find($request->category_id);
            $categoryName = $category ? '_of_' . strtolower(str_replace(' ', '_', $category->name)) : '';
        }

        if ($request->filled('for_month')) {
            $monthYearDate = Carbon::parse($request->for_month);
            $monthYear = '_for_' . $monthYearDate->format('m_Y');
        }

        return [
            'categoryName' => $categoryName,
            'monthYear' => $monthYear,
        ];
    }

    /**
     * Generate the file name for the export.
     *
     * @param string $categoryName
     * @param string $monthYear
     * @return string
     */
    private function generateFileName($categoryName, $monthYear)
    {
        $downloadMonth = $monthYear ?: '_' . date('m_Y');
        return 'expense_backup' . $categoryName . $downloadMonth . '.xlsx';
    }
}
