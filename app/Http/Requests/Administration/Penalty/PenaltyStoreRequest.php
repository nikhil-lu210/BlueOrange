<?php

namespace App\Http\Requests\Administration\Penalty;

use App\Models\Penalty\Penalty;
use Illuminate\Foundation\Http\FormRequest;

class PenaltyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('Penalty Everything') || auth()->user()->can('Penalty Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $penaltyTypes = Penalty::getPenaltyTypes();
        
        return [
            'user_id' => [
                'required',
                'exists:users,id'
            ],
            'attendance_id' => [
                'required',
                'exists:attendances,id'
            ],
            'type' => [
                'required',
                'in:' . implode(',', $penaltyTypes)
            ],
            'total_time' => [
                'required',
                'integer',
                'min:1',
                'max:1440' // Maximum 24 hours in minutes
            ],
            'reason' => [
                'required',
                'string',
                'min:10',
                'max:1000'
            ],
            'files.*' => [
                'nullable',
                'file',
                'max:5120', // 5MB max per file
                'mimes:jpg,jpeg,png,pdf,doc,docx'
            ]
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select an employee.',
            'user_id.exists' => 'The selected employee is invalid.',
            'attendance_id.required' => 'Please select an attendance record.',
            'attendance_id.exists' => 'The selected attendance record is invalid.',
            'type.required' => 'Please select a penalty type.',
            'type.in' => 'The selected penalty type is invalid.',
            'total_time.required' => 'Please enter the penalty time.',
            'total_time.integer' => 'Penalty time must be a number.',
            'total_time.min' => 'Penalty time must be at least 1 minute.',
            'total_time.max' => 'Penalty time cannot exceed 24 hours (1440 minutes).',
            'reason.required' => 'Please provide a reason for the penalty.',
            'reason.min' => 'Reason must be at least 10 characters.',
            'reason.max' => 'Reason cannot exceed 1000 characters.',
            'files.*.max' => 'Each file must not exceed 5MB.',
            'files.*.mimes' => 'Files must be of type: jpg, jpeg, png, pdf, doc, docx.'
        ];
    }
}
