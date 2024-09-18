<?php

namespace App\Http\Requests\Administration\Profile;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
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
        $id = auth()->user()->id;
        return [
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => [
                'nullable', 
                'email',
                Rule::unique('users')->ignore($id)
            ],
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'The avatar must be a JPEG, JPG or PNG image file.',
            'avatar.max' => 'The avatar size should not more then 2MB.',
        ];
    }
}
