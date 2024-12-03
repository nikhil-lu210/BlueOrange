<?php

namespace App\Http\Controllers\Administration\Accounts\IncomeExpense;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\IncomeExpense\Income;
use App\Models\IncomeExpense\Expense;

class IncomeExpenseStatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $total = (object) [
            'income' => Income::sum('total'),
            'expense' => Expense::sum('total'),
        ];

        $currentYear = now()->year;
        $monthlyIncome = Income::selectRaw('MONTH(date) as month, SUM(total) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')->toArray();

        $monthlyExpenses = Expense::selectRaw('MONTH(date) as month, SUM(total) as total')
            ->whereYear('date', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')->toArray();

        // Fill missing months with 0
        $monthlyIncome = array_replace(array_fill(1, 12, 0), $monthlyIncome);
        $monthlyExpenses = array_replace(array_fill(1, 12, 0), $monthlyExpenses);

        // dd($total, $monthlyIncome, $monthlyExpenses);
        return view('administration.accounts.income_expense.statistics.index', compact([
            'total',
            'monthlyIncome',
            'monthlyExpenses',
        ]));
    }
}
