<?php

namespace App\Http\Requests\Administration\LearningHub;

use Illuminate\Foundation\Http\FormRequest;

class LearningTopicUpdateRequest extends FormRequest
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
            'recipients' => ['nullable', 'array'],
            'recipients.*' => ['integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20']
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
            'recipients.array' => 'The recipients must be an array of user IDs.',
            'recipients.*.integer' => 'Each recipient must be a valid user ID.',
            'recipients.*.exists' => 'One or more recipients do not exist.',
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'description.required' => 'The description is required.',
            'description.string' => 'The description must be a string.',
            'description.min' => 'The description must be at least 20 characters.'
        ];
    }
}
