<?php

namespace App\Exports\Administration\Accounts\IncomeExpense;

use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Number;

class ExpenseExport extends BaseExportSettings implements FromCollection
{
    protected $expenses;

    public function __construct($expenses)
    {
        $this->expenses = $expenses;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->expenses->map(function ($expense) {
            return [
                'date' => get_date_only($expense->date),
                'title' => $expense->title,
                'category' => $expense->category->name,
                'price' => format_currency($expense->price),
                'quantity' => $expense->quantity,
                'expense' => format_currency($expense->total),
                'creator' => $expense->creator->name
            ];
        });
    }

    /**
     * Define the headings for the export.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Date',
            'Reason',
            'Category',
            'Price',
            'Quantity',
            'Total',
            'Creator'
        ];
    }
}
