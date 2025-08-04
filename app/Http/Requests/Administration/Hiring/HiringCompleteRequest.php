<?php

namespace App\Http\Requests\Administration\Hiring;

use Illuminate\Foundation\Http\FormRequest;

class HiringCompleteRequest extends FormRequest
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
            'userid' => [
                'required',
                'string',
                'unique:users,userid',
                'min:3',
                'max:20',
                'regex:/^[a-zA-Z0-9_-]+$/'
            ],
            'first_name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'last_name' => [
                'required',
                'string',
                'min:2',
                'max:100'
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'max:255'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed'
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8'
            ],
            'joining_date' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'alias_name' => [
                'nullable',
                'string',
                'max:100'
            ],
            'official_email' => [
                'nullable',
                'email',
                'max:255'
            ],
            'official_contact_no' => [
                'nullable',
                'string',
                'max:20'
            ],
            'role_id' => [
                'required',
                'exists:roles,id'
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
            'userid.required' => 'User ID is required.',
            'userid.unique' => 'This User ID is already taken.',
            'userid.regex' => 'User ID can only contain letters, numbers, hyphens, and underscores.',
            'first_name.required' => 'First name is required.',
            'first_name.min' => 'First name must be at least 2 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.min' => 'Last name must be at least 2 characters.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'Password confirmation is required.',
            'joining_date.required' => 'Joining date is required.',
            'joining_date.date' => 'Please provide a valid joining date.',
            'joining_date.before_or_equal' => 'Joining date cannot be in the future.',
            'official_email.email' => 'Please provide a valid official email address.',
            'role_id.required' => 'Role selection is required.',
            'role_id.exists' => 'Selected role is invalid.',
        ];
    }
}
