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
                'name' => $attendance->user->alias_name,
                'clock_in_date' => get_date_only($attendance->clock_in_date),
                'clock_in' => show_time($attendance->clock_in),
                'clock_out' => $attendance->clock_out ? show_time($attendance->clock_out) : NULL,
                'total_time' => $attendance->total_adjusted_time ?? $attendance->total_time,
                'type' => $attendance->type,
                'total_over_break' => $attendance->total_over_break,
                'clockin_medium' => $attendance->clockin_medium,
                'clockout_medium' => $attendance->clockout_medium ?? NULL,
                'clockin_scanner_id' => optional($attendance->clockin_scanner)->name,
                'clockout_scanner_id' => optional($attendance->clockout_scanner)->name,
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
            'Clockin',
            'Clockout',
            'Total',
            'Type',
            'Over Break',
            'Clockin Medium',
            'Clockout Medium',
            'Clockin Scanner',
            'Clockout Scanner',
        ];
    }
}
