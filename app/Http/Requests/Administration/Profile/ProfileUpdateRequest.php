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
        $employeeId = auth()->user()->employee->id;
        
        return [
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            
            // for employees table
            'father_name' => ['sometimes', 'string'],
            'mother_name' => ['sometimes', 'string'],
            'personal_email' => [
                'sometimes', 
                'email',
                Rule::unique('employees')->ignore($employeeId)
            ],
            'personal_contact_no' => [
                'sometimes', 
                'string',
                Rule::unique('employees')->ignore($employeeId)
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
