<?php

namespace App\Exports\Administration\Recognition;

use App\Models\Recognition\Recognition;
use App\Exports\Global\BaseExportSettings;
use Maatwebsite\Excel\Concerns\FromCollection;

class RecognitionReportExport extends BaseExportSettings implements FromCollection
{
    protected $recognitions;

    public function __construct($recognitions)
    {
        $this->recognitions = $recognitions->load([
            'user',
            'recognizer'
        ]);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->recognitions->map(function ($recognition) {
            return [
                'employee_name' => $recognition->user->alias_name,
                'employee_id' => $recognition->user->userid ?? 'N/A',
                'category' => $recognition->category,
                'score' => $recognition->total_mark,
                'max_score' => config('recognition.marks.max'),
                'comment' => strip_tags($recognition->comment),
                'recognizer_name' => $recognition->recognizer->alias_name,
                'recognizer_id' => $recognition->recognizer->userid ?? 'N/A',
                'recognition_date' => show_date($recognition->created_at),
                'created_at' => show_date_time($recognition->created_at),
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
            'Employee Name',
            'Employee ID',
            'Category',
            'Score',
            'Max Score',
            'Comment',
            'Recognizer Name',
            'Recognizer ID',
            'Recognition Date',
            'Created At',
        ];
    }
}
