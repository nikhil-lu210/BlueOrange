<?php

namespace App\Http\Requests\Administration\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveHistoryStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'type' => ['required', 'in:Earned,Casual,Sick'],
            'reason' => ['required', 'string'],
            'is_paid_leave' => ['nullable', 'boolean'],
            'leave_days.date' => ['required', 'array'],
            'leave_days.date.*' => ['date'],
            'total_leave.hour' => ['required', 'array'],
            'total_leave.hour.*' => ['integer', 'min:0', 'max:8'],
            'total_leave.min' => ['required', 'array'],
            'total_leave.min.*' => ['integer', 'min:0', 'max:59'],
            'total_leave.sec' => ['required', 'array'],
            'total_leave.sec.*' => ['integer', 'min:0', 'max:59'],
            'files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'leave_days.date.required' => 'Please provide at least one date for the leave days.',
            'leave_days.date.*.date' => 'Each date must be a valid date format.',
            'total_leave.hour.required' => 'Please provide total leave hours.',
            'total_leave.hour.*.integer' => 'Total leave hours must be an integer.',
            'total_leave.hour.*.min' => 'Total leave hours cannot be less than 0.',
            'total_leave.hour.*.max' => 'Total leave hours cannot exceed 8 hours.',
            'total_leave.min.required' => 'Please provide total leave minutes.',
            'total_leave.min.*.integer' => 'Total leave minutes must be an integer.',
            'total_leave.min.*.min' => 'Total leave minutes cannot be less than 0.',
            'total_leave.min.*.max' => 'Total leave minutes cannot exceed 59 minutes.',
            'total_leave.sec.required' => 'Please provide total leave seconds.',
            'total_leave.sec.*.integer' => 'Total leave seconds must be an integer.',
            'total_leave.sec.*.min' => 'Total leave seconds cannot be less than 0.',
            'total_leave.sec.*.max' => 'Total leave seconds cannot exceed 59 seconds.',
            'files.*.mimes' => 'Only jpg, jpeg, png, pdf, doc, and docx files are allowed.',
            'files.*.max' => 'Each file must not exceed 2MB.',
        ];
    }

}
