<?php

namespace App\Services\Administration\Accounts\IncomeExpense;

use Carbon\Carbon;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\IncomeExpenseCategory;

class IncomeExportService
{
    public function export($request)
    {
        // Building the query based on filters
        $query = Income::with(['category', 'creator'])->orderByDesc('date');

        // Initialize variables for filename parts
        $categoryName = '';
        $monthYear = '';
        
        // Handle category_id filter
        if ($request->has('category_id') && !is_null($request->category_id)) {
            $query->where('category_id', $request->category_id);
            $category = IncomeExpenseCategory::whereId($request->category_id)->first();
            $categoryName = $category ? '_of_' . strtolower(str_replace(' ', '_', $category->name)) : '';
        }

        // Handle for_month filter
        if ($request->has('for_month') && !is_null($request->for_month)) {
            $monthYearDate = Carbon::createFromFormat('F Y', $request->for_month);
            $query->whereYear('date', $monthYearDate->year)
                ->whereMonth('date', $monthYearDate->month);
            $monthYear = '_for_' . $monthYearDate->format('m_Y');
        } else {
            if (!$request->has('filter_incomes')) {
                $query->whereBetween('date', [
                    Carbon::now()->startOfMonth()->format('Y-m-d'),
                    Carbon::now()->endOfMonth()->format('Y-m-d'),
                ]);
            }
        }

        // Get the filtered incomes
        $incomes = $query->get();

        if ($incomes->isEmpty()) {
            return null; // Indicate no incomes found
        }

        return [
            'incomes' => $incomes,
            'fileName' => $this->generateFileName($categoryName, $monthYear),
        ];
    }

    /**
     * Generate the file name for the export file.
     *
     * @param string $categoryName
     * @param string $monthYear
     * @return string
     */
    private function generateFileName($categoryName, $monthYear)
    {
        $downloadMonth = $monthYear ? $monthYear : '_' . date('m_Y');
        return 'income_backup' . $categoryName . $downloadMonth . '.xlsx';
    }
}
