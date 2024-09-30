<?php

namespace App\Http\Requests\Administration\Attendance;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Administration\Attendance\ClockInDateMatch;

class AttendanceUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'clock_in' => [
                'required',
                'date_format:Y-m-d H:i',
                new ClockInDateMatch($this->attendance), // Use the custom rule
            ],
            'clock_out' => [
                'nullable',
                'date_format:Y-m-d H:i'
            ],
        ];
    }
}
