<?php

namespace App\Http\Requests\Administration\Hiring;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HiringCandidateUpdateRequest extends FormRequest
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
        $candidateId = $this->route('hiring_candidate')->id ?? null;

        return [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('hiring_candidates', 'email')->ignore($candidateId),
                'max:255'
            ],
            'phone' => [
                'required',
                'string',
                'min:10',
                'max:20'
            ],
            'expected_role' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'expected_salary' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999.99'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'status' => [
                'required',
                'in:shortlisted,in_progress,rejected,hired'
            ],
            'files.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120' // 5MB per file
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
            'name.required' => 'Candidate name is required.',
            'name.min' => 'Candidate name must be at least 2 characters.',
            'name.max' => 'Candidate name cannot exceed 255 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 characters.',
            'phone.max' => 'Phone number cannot exceed 20 characters.',
            'expected_role.required' => 'Expected role is required.',
            'expected_role.min' => 'Expected role must be at least 2 characters.',
            'expected_salary.numeric' => 'Expected salary must be a valid number.',
            'expected_salary.min' => 'Expected salary cannot be negative.',
            'expected_salary.max' => 'Expected salary is too high.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.mimes' => 'Files must be PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'files.*.max' => 'Each file cannot exceed 5MB.',
        ];
    }
}
