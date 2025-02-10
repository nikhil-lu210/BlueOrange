<?php

namespace App\Http\Requests\Administration\Attendance\Issue;

use Carbon\Carbon;
use App\Models\Attendance\Attendance;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceIssueUpdateRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */    
    public function rules()
    {
        $rules = [
            'status'                => ['required', 'in:Approved,Rejected'],
            // 'clock_in'              => ['required', 'date_format:Y-m-d H:i'],
            // 'clock_out'             => ['nullable', 'date_format:Y-m-d H:i', 'after:clock_in'],
            // 'type'                  => ['required', 'string', 'in:Regular,Overtime'],
            // 'reason'                => ['required', 'string', 'min:10'],
        ];

        if ($this->status === 'Approved') {
            $rules['clock_in_date'] = ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:' . now()->format('Y-m-d')];

            // Ensure clock_in date matches clock_in_date
            $rules['clock_in'][] = function ($attribute, $value, $fail) {
                $clockInDate = Carbon::parse($value)->format('Y-m-d');
                if ($clockInDate !== $this->clock_in_date) {
                    $fail('The clock-in date-time must match the requested clock-in date.');
                }
            };
        }

        if ($this->status === 'Rejected') {
            $rules['note'] = ['required', 'string'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'title.required'                 => 'The title field is required.',
            'title.string'                   => 'The title must be a valid string.',
            'title.max'                      => 'The title may not be greater than 255 characters.',

            'attendance_issue_type.required' => 'Please select whether the issue is for old or new attendance.',
            'attendance_issue_type.in'       => 'Invalid selection for attendance issue type.',

            'clock_in_date.required'         => 'The clock-in date is required.',
            'clock_in_date.date'             => 'The clock-in date must be a valid date.',
            'clock_in_date.date_format'      => 'The clock-in date format must be YYYY-MM-DD.',
            'clock_in_date.before_or_equal'  => 'The clock-in date cannot be in the future.',

            'attendance_id.required'         => 'Please select an attendance record.',
            'attendance_id.exists'           => 'The selected attendance record does not exist.',

            'clock_in.required'              => 'The clock-in time is required and must be in YYYY-MM-DD HH:MM format.',
            'clock_in.date_format'           => 'The clock-in time format must be YYYY-MM-DD HH:MM.',

            'clock_out.date_format'          => 'The clock-out time format must be YYYY-MM-DD HH:MM.',
            'clock_out.after'                => 'The clock-out time must be after the clock-in time.',

            'status.required'                  => 'The attendance issue status is required.',
            'status.in'                        => 'Invalid attendance issue status selected.',

            'note.required'                => 'A note for the attendance issue rejection is required.',
            'note.string'                  => 'The note must be a valid string.',
        ];
    }
}
