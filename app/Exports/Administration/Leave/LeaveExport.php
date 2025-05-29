<?php

namespace App\Exports\Administration\Leave;

use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class LeaveExport extends BaseExportSettings implements FromCollection
{
    protected $leaves;

    public function __construct($leaves)
    {
        $this->leaves = $leaves;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->leaves->map(function ($leave) {
            return [
                'name' => $leave->user->alias_name.' ('.$leave->user->name.')',
                'date' => show_date($leave->date),
                'type' => $leave->type,
                'total_leave' => $leave->total_leave,
                'status' => $leave->status,
                'is_paid_leave' => $leave->is_paid_leave == true ? 'Paid' : 'Unpaid',
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
            'Name',
            'Date',
            'Type',
            'Total',
            'Status',
            'Is Paid',
        ];
    }
}
