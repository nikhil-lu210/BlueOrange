<?php

namespace App\Http\Requests\Administration\Attendance\Issue;

use Carbon\Carbon;
use Illuminate\Validation\Rule;
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
            'status'    => ['required', 'in:Approved,Rejected'],
        ];

        if ($this->status === 'Approved') {
            $rules['user_id'] = ['required', 'exists:users,id'];
            $rules['type'] = ['required', 'in:Regular,Overtime'];
            $rules['clock_in_date'] = ['required', 'date', 'date_format:Y-m-d', 'before_or_equal:' . now()->format('Y-m-d')];

            $rules['clock_in'] = [
                'required',
                'date_format:Y-m-d H:i',
                'before_or_equal:' . now()->format('Y-m-d H:i'),
                function ($_, $value, $fail) {
                    $clockInDate = Carbon::parse($value)->format('Y-m-d');
                    if ($clockInDate !== $this->clock_in_date) {
                        $fail('The clock-in date-time must match the requested clock-in date.');
                    }
                }
            ];

            $rules['clock_out'] = [
                'required',
                'date_format:Y-m-d H:i',
                'after:clock_in'
            ];

            $rules['attendance_id'] = [
                'nullable',
                Rule::exists('attendances', 'id')->where(function ($query) {
                    return $query->where('user_id', $this->user_id);
                }),
            ];

            // Add custom validation for Regular attendance duplication
            if ($this->type === 'Regular' && !$this->attendance_id) {
                $rules['type'][] = function ($_, $__, $fail) {
                    $existingRegularAttendance = \App\Models\Attendance\Attendance::where('user_id', $this->user_id)
                        ->where('clock_in_date', $this->clock_in_date)
                        ->where('type', 'Regular')
                        ->first();

                    if ($existingRegularAttendance) {
                        $fail('Cannot create new Regular attendance. A Regular attendance already exists for this date. Please request to update the existing attendance record instead.');
                    }
                };
            }
        }

        if ($this->status === 'Rejected') {
            $rules['note'] = ['required', 'string'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'status.required'            => 'The attendance issue status is required.',
            'status.in'                  => 'Invalid attendance issue status selected.',

            'user_id.required'           => 'The user field is required.',
            'user_id.exists'             => 'The selected user does not exist.',

            'clock_in_date.required'     => 'The clock-in date is required.',
            'clock_in_date.date'         => 'The clock-in date must be a valid date.',
            'clock_in_date.date_format'  => 'The clock-in date format must be YYYY-MM-DD.',
            'clock_in_date.before_or_equal' => 'The clock-in date cannot be in the future.',

            'clock_in.required'          => 'The clock-in time is required.',
            'clock_in.date_format'       => 'The clock-in time format must be YYYY-MM-DD HH:MM.',
            'clock_in.before_or_equal'   => 'The clock-in time cannot be in the future.',

            'clock_out.required'          => 'The clock-out time is required.',
            'clock_out.date_format'      => 'The clock-out time format must be YYYY-MM-DD HH:MM.',
            'clock_out.after'            => 'The clock-out time must be after the clock-in time.',

            'attendance_id.exists'       => 'The selected attendance record does not exist or does not belong to the user.',

            'note.required'              => 'A note for the attendance issue rejection is required.',
            'note.string'                => 'The note must be a valid string.',
        ];
    }
}
