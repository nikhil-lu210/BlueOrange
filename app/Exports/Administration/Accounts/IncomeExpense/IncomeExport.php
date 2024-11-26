<?php

namespace App\Exports\Administration\Accounts\IncomeExpense;

use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class IncomeExport extends BaseExportSettings implements FromCollection
{
    protected $incomes;

    public function __construct($incomes)
    {
        $this->incomes = $incomes;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->incomes->map(function ($income) {
            return [
                'date' => get_date_only($income->date),
                'source' => $income->source,
                'category' => $income->category->name,
                'income' => format_currency($income->total),
                'creator' => $income->creator->name
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
            'Source',
            'Category',
            'Total',
            'Creator'
        ];
    }
}
