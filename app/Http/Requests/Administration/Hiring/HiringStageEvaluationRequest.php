<?php

namespace App\Http\Requests\Administration\Hiring;

use Illuminate\Foundation\Http\FormRequest;

class HiringStageEvaluationRequest extends FormRequest
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
            'hiring_candidate_id' => [
                'required',
                'exists:hiring_candidates,id'
            ],
            'hiring_stage_id' => [
                'required',
                'exists:hiring_stages,id'
            ],
            'assigned_to' => [
                'required',
                'exists:users,id'
            ],
            'status' => [
                'required',
                'in:pending,in_progress,completed,passed,failed'
            ],
            'notes' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'feedback' => [
                'nullable',
                'string',
                'max:2000'
            ],
            'rating' => [
                'nullable',
                'integer',
                'min:1',
                'max:10'
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
            'hiring_candidate_id.required' => 'Candidate selection is required.',
            'hiring_candidate_id.exists' => 'Selected candidate is invalid.',
            'hiring_stage_id.required' => 'Stage selection is required.',
            'hiring_stage_id.exists' => 'Selected stage is invalid.',
            'assigned_to.required' => 'Evaluator assignment is required.',
            'assigned_to.exists' => 'Selected evaluator is invalid.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status selected.',
            'notes.max' => 'Notes cannot exceed 2000 characters.',
            'feedback.max' => 'Feedback cannot exceed 2000 characters.',
            'rating.integer' => 'Rating must be a number.',
            'rating.min' => 'Rating must be at least 1.',
            'rating.max' => 'Rating cannot exceed 10.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.mimes' => 'Files must be PDF, DOC, DOCX, JPG, JPEG, or PNG.',
            'files.*.max' => 'Each file cannot exceed 5MB.',
        ];
    }
}
