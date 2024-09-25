<?php

namespace App\Exports\Administration\DailyBreak;

use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class DailyBreakExport extends BaseExportSettings implements FromCollection
{
    protected $breaks;

    public function __construct($breaks)
    {
        $this->breaks = $breaks;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->breaks->map(function ($break) {
            return [
                'name' => $break->user->name,
                'date' => show_date($break->date),
                'break_in_at' => show_time($break->break_in_at),
                'break_out_at' => show_time($break->break_out_at),
                'total_time' => total_time($break->total_time),
                'over_break' => total_time($break->over_break),
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
            'Break Started',
            'Break Stopped',
            'Total',
            'Over Break',
        ];
    }
}
