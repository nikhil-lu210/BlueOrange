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
                'clock_out' => $attendance->clock_out ? show_time($attendance->clock_out) : NULL,
                'total_time' => $attendance->type == 'Regular' ? $attendance->total_adjusted_time : $attendance->total_time,
                'type' => $attendance->type,
                'total_break_time' => $attendance->total_break_time,
                'total_over_break' => $attendance->total_over_break,
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
            'Total',
            'Type',
            'Total Break',
            'Over Break',
        ];
    }
}
