<?php

namespace App\Http\Requests\Recognition;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecognitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Recognition\Recognition::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'category' => 'required|string|in:' . implode(',', config('recognition.categories')),
            'total_mark' => 'required|integer|min:' . config('recognition.marks.min') . '|max:' . config('recognition.marks.max'),
            'comment' => 'required|string|max:1000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select an employee to recognize.',
            'user_id.exists' => 'The selected employee does not exist.',
            'category.required' => 'Please select a recognition category.',
            'category.in' => 'The selected category is not valid.',
            'total_mark.required' => 'Please provide a recognition score.',
            'total_mark.integer' => 'The recognition score must be a number.',
            'total_mark.min' => 'The recognition score must be at least ' . config('recognition.marks.min') . '.',
            'total_mark.max' => 'The recognition score must not exceed ' . config('recognition.marks.max') . '.',
            'comment.required' => 'Please provide a comment for the recognition.',
            'comment.max' => 'The comment must not exceed 1000 characters.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'employee',
            'category' => 'recognition category',
            'total_mark' => 'recognition score',
            'comment' => 'recognition comment',
        ];
    }
}
