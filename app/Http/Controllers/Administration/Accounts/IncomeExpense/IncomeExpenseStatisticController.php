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
    public function index(Request $request)
    {
        if ($request->input('for_year')) {
            $request->validate([
                'for_year' => ['required', 'digits:4', 'integer', 'min:1900', 'max:' . date('Y')],
            ]);
        }        

        $total = (object) [
            'income' => Income::sum('total'),
            'expense' => Expense::sum('total'),
        ];

        $year = $request->input('for_year') ?? now()->year;
        $monthlyIncome = Income::selectRaw('MONTH(date) as month, SUM(total) as total')
            ->whereYear('date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')->toArray();

        $monthlyExpenses = Expense::selectRaw('MONTH(date) as month, SUM(total) as total')
            ->whereYear('date', $year)
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
