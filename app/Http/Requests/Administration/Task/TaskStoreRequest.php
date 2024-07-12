<?php

namespace App\Http\Requests\Administration\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('Task Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'users' => ['nullable', 'array'],
            'users.*' => ['integer', 'exists:users,id'],
            'title' => ['required', 'string'],
            'description' => ['required', 'string', 'min:20'],
            'deadline' => ['nullable', 'date_format:Y-m-d'],
            'priority' => ['required', 'string', 'in:Low,Medium,Average,High'],
            'files.*' => ['nullable', 'max:5000'] // 'mimes:jpeg,jpg,png,pdf,zip,csv,sql',
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
            'users.array' => 'The users must be an array of user IDs.',
            'users.*.integer' => 'Each user must be a valid user ID.',
            'users.*.exists' => 'One or more users do not exist.',
            'title.required' => 'The title is required.',
            'description.required' => 'The description is required.',
            'description.min' => 'The description must be at least 20 characters long.',
            'deadline.date_format' => 'The deadline must be in the format YYYY-MM-DD.',
            'priority.in' => 'The priority must be one of: Low, Medium, Average, High.',
            // 'files.*.mimes' => 'Each file must be one of: jpeg, jpg, png, pdf, zip, csv, sql.',
            'files.*.max' => 'Each file may not be greater than 5000 KB in size.'
        ];
    }
}
