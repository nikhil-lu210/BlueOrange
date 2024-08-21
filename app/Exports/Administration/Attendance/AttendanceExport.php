<?php

namespace App\Exports\Administration\Attendance;

use App\Models\Attendance\Attendance;
use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceExport extends BaseExportSettings implements FromCollection
{
    protected $attendances;

    public function __construct($attendances)
    {
        $this->attendances = $attendances;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->attendances->map(function ($attendance) {
            return [
                'name' => $attendance->user->name,
                'clock_in_date' => show_date($attendance->clock_in_date),
                'shift' => show_time($attendance->employee_shift->start_time) . ' to ' . show_time($attendance->employee_shift->end_time),
                'clock_in' => show_time($attendance->clock_in),
                'clock_out' => show_time($attendance->clock_out),
                'total_time' => total_time($attendance->total_time),
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
            'Shift',
            'Clockin',
            'Clockout',
            'Total'
        ];
    }
}
