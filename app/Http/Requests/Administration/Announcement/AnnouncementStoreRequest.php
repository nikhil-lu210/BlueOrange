<?php

namespace App\Http\Requests\Administration\Announcement;

use Illuminate\Foundation\Http\FormRequest;

class AnnouncementStoreRequest extends FormRequest
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
            'title' => ['required', 'string'],
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
            'description.required' => 'The description is required.',
            'description.min' => 'The description must be at least 20 characters long.'
        ];
    }
}
