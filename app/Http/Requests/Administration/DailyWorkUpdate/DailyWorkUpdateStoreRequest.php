<?php

namespace App\Http\Requests\Administration\DailyWorkUpdate;

use Illuminate\Foundation\Http\FormRequest;

class DailyWorkUpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('Daily Work Update Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'before_or_equal:today', 'unique:daily_work_updates,date,NULL,id,user_id,' . auth()->id()],
            'work_update' => ['required', 'string', 'min:50'],
            'progress' => ['required', 'integer', 'between:0,100'],
            'note_issue' => ['nullable', 'string'],
            'files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xlsx,csv,zip', 'max:2048'], // Max 2MB per file
        ];
    }


    public function messages(): array
    {
        return [
            'date.required' => 'The work update date is required.',
            'date.before_or_equal' => 'The date cannot be in the future.',
            'date.unique' => 'You have already submitted a work update for this date.',
            'work_update.required' => 'Please provide your daily work update.',
            'work_update.min' => 'Work update description must be at least 50 characters long.',
            'progress.required' => 'Progress percentage is required.',
            'progress.between' => 'Progress must be between 0 and 100.',
            'files.*.mimes' => 'Only JPG, PNG, PDF, DOC, DOCX, XLSX, CSV, and ZIP files are allowed.',
            'files.*.max' => 'Each file should not exceed 2MB in size.',
        ];
    }
}
