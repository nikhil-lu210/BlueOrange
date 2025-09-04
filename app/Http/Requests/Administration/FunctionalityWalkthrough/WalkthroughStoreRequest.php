<?php

namespace App\Http\Requests\Administration\FunctionalityWalkthrough;

use Illuminate\Foundation\Http\FormRequest;

class WalkthroughStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('Functionality Walkthrough Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'assigned_roles' => 'nullable|array',
            'assigned_roles.*' => 'exists:roles,id',
            'steps' => 'required|array|min:1',
            'steps.*.step_title' => 'required|string|max:255',
            'steps.*.step_description' => 'required|string',
            'steps.*.files' => 'nullable|array',
            'steps.*.files.*' => 'file|max:10240', // 10MB max
            'files' => 'nullable|array',
            'files.*' => 'file|max:10240', // 10MB max
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
            'title.required' => 'The walkthrough title is required.',
            'title.max' => 'The walkthrough title may not be greater than 255 characters.',
            'steps.required' => 'At least one step is required.',
            'steps.min' => 'At least one step is required.',
            'steps.*.step_title.required' => 'Each step must have a title.',
            'steps.*.step_title.max' => 'Step title may not be greater than 255 characters.',
            'steps.*.step_description.required' => 'Each step must have a description.',
            'steps.*.files.*.file' => 'Each file must be a valid file.',
            'steps.*.files.*.max' => 'Each file may not be greater than 10MB.',
            'files.*.file' => 'Each file must be a valid file.',
            'files.*.max' => 'Each file may not be greater than 10MB.',
        ];
    }
}
