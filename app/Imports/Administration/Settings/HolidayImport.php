<?php

namespace App\Imports\Administration\Settings;

use App\Models\Holiday\Holiday;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class HolidayImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            DB::transaction(function () use ($row) {
                $date = Carbon::parse($row['date'])->format('Y-m-d');
                $name = $row['name'];
                $description = $row['description'];

                // Create or update holiday
                Holiday::updateOrCreate(
                    ['date' => $date],
                    [
                        'name' => $name,
                        'description' => $description,
                    ]
                );
            });
        }
    }

    /**
     * Define validation rules for the import.
     */
    public function rules(): array
    {
        return [
            '*.date' => 'required|date|unique:holidays,date',
            '*.name' => 'required|string',
            '*.description' => 'required|string',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function customValidationMessages()
    {
        return [
            '*.date.unique' => 'The Holiday for :input is already registered.',
        ];
    }
}
