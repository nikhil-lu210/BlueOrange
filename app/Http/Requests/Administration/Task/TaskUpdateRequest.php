<?php

namespace App\Http\Requests\Administration\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $task = $this->route('task');

        $canUpdate = (
            $task->creator_id == auth()->user()->id || 
            auth()->user()->hasRole('Developer') || 
            auth()->user()->can('Task Update')
        );
        
        return $canUpdate;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'deadline' => ['nullable', 'date_format:Y-m-d'],
            'priority' => ['required', 'string', 'in:Low,Medium,Average,High'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'description.required' => 'The description is required.',
            'description.min' => 'The description must be at least 20 characters long.',
            'deadline.date_format' => 'The deadline must be in the format YYYY-MM-DD.',
            'priority.in' => 'The priority must be one of: Low, Medium, Average, High.',
        ];
    }
}
