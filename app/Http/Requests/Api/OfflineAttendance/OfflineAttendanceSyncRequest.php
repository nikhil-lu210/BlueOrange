<?php

namespace App\Http\Requests\Api\OfflineAttendance;

use Illuminate\Foundation\Http\FormRequest;

class OfflineAttendanceSyncRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'attendances' => [
                'required',
                'array',
                'min:1'
            ],
            'attendances.*.user_id' => [
                'sometimes',
                'integer'
            ],
            'attendances.*.userid' => [
                'sometimes',
                'string'
            ],
            'attendances.*.type' => [
                'sometimes',
                'string',
                'in:Regular,Overtime'
            ],
            'attendances.*.entry_date_time' => [
                'sometimes',
                'date',
                'before_or_equal:now'
            ],
            'attendances.*.clock_in_date' => [
                'sometimes',
                'date'
            ],
            'attendances.*.clock_in' => [
                'sometimes',
                'date'
            ],
            'attendances.*.clock_out' => [
                'sometimes',
                'date'
            ],
            'attendances.*.timestamp' => [
                'sometimes',
                'date'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'attendances.required' => 'Attendance records are required.',
            'attendances.array' => 'Attendance records must be an array.',
            'attendances.min' => 'At least one attendance record is required.',
            'attendances.*.user_id.integer' => 'User ID must be an integer.',
            'attendances.*.user_id.exists' => 'The specified user does not exist.',
            'attendances.*.userid.string' => 'User ID must be a string.',
            'attendances.*.userid.exists' => 'The specified user does not exist.',
            'attendances.*.type.in' => 'Attendance type must be either Regular or Overtime.',
            'attendances.*.entry_date_time.date' => 'Entry date time must be a valid date.',
            'attendances.*.entry_date_time.before_or_equal' => 'Entry date time cannot be in the future.',
            'attendances.*.clock_in_date.date' => 'Clock in date must be a valid date.',
            'attendances.*.clock_in.date' => 'Clock in time must be a valid date.',
            'attendances.*.clock_out.date' => 'Clock out time must be a valid date.',
            'attendances.*.timestamp.date' => 'Timestamp must be a valid date.'
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $attendances = $this->input('attendances', []);

            foreach ($attendances as $index => $attendance) {
                // Ensure at least one user identifier is provided
                if (!isset($attendance['user_id']) && !isset($attendance['userid'])) {
                    $validator->errors()->add(
                        "attendances.{$index}",
                        'Either user_id or userid must be provided.'
                    );
                }

                // Ensure at least one datetime field is provided
                $datetimeFields = ['entry_date_time', 'clock_in_date', 'clock_in', 'clock_out', 'timestamp'];
                $hasDatetime = false;

                foreach ($datetimeFields as $field) {
                    if (isset($attendance[$field])) {
                        $hasDatetime = true;
                        break;
                    }
                }

                if (!$hasDatetime) {
                    $validator->errors()->add(
                        "attendances.{$index}",
                        'At least one datetime field must be provided.'
                    );
                }
            }
        });
    }
}
