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
}
