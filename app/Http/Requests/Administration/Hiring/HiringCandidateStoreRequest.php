<?php

namespace App\Http\Requests\Administration\Hiring;

use Illuminate\Foundation\Http\FormRequest;

class HiringCandidateStoreRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255'
            ],
            'email' => [
                'required',
                'email',
                'unique:hiring_candidates,email',
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
            'resume' => [
                'required',
                'file',
                'mimes:pdf,doc,docx',
                'max:5120' // 5MB
            ],
            'files.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:5120' // 5MB per file
            ],

            // Stage 1 - Basic Interview
            'stage1_evaluator' => [
                'required',
                'exists:users,id'
            ],
            'stage1_scheduled_at' => [
                'required',
                'date',
                'after:now'
            ],

            // Stage 2 - Workshop
            'stage2_evaluator' => [
                'nullable',
                'exists:users,id'
            ],
            'stage2_scheduled_at' => [
                'nullable',
                'date',
                'after:stage1_scheduled_at'
            ],

            // Stage 3 - Final Interview
            'stage3_evaluator' => [
                'nullable',
                'exists:users,id'
            ],
            'stage3_scheduled_at' => [
                'nullable',
                'date',
                'after:stage2_scheduled_at'
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
            'resume.required' => 'Resume file is required.',
            'resume.file' => 'Resume must be a valid file.',
            'resume.mimes' => 'Resume must be a PDF, DOC, or DOCX file.',
            'resume.max' => 'Resume file size cannot exceed 5MB.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.mimes' => 'Files must be PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'files.*.max' => 'Each file cannot exceed 5MB.',

            // Stage assignment messages
            'stage1_evaluator.required' => 'Basic interview evaluator is required.',
            'stage1_evaluator.exists' => 'Selected basic interview evaluator is invalid.',
            'stage1_scheduled_at.required' => 'Basic interview date and time is required.',
            'stage1_scheduled_at.after' => 'Basic interview must be scheduled for a future date.',
            'stage2_evaluator.exists' => 'Selected workshop evaluator is invalid.',
            'stage2_scheduled_at.after' => 'Workshop must be scheduled after basic interview.',
            'stage3_evaluator.exists' => 'Selected final interview evaluator is invalid.',
            'stage3_scheduled_at.after' => 'Final interview must be scheduled after workshop.',
        ];
    }
}
