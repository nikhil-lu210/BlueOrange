<?php

namespace App\Http\Requests\Administration\Profile\Security;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Http\FormRequest;

class PasswordUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules()
    {
        return [
            'old_password' => [
                'required',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, $this->user()->password)) {
                        return $fail('The current password is incorrect.');
                    }
                },
            ],
            'new_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (Hash::check($value, $this->user()->password)) {
                        return $fail('The new password cannot be the same as the old password.');
                    }
                },
            ],
        ];
    }

    public function messages()
    {
        return [
            'new_password.confirmed' => 'The new password confirmation does not match.',
        ];
    }
}
