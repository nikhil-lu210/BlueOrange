<?php

namespace App\Http\Requests\Administration\Settings\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
        $id = $this->route('user')->id;
        $employeeId = $this->route('user')->employee->id;

        return [
            'role_id' => ['sometimes', 'integer', 'exists:roles,id'],
            'avatar' => ['sometimes', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users')->ignore($id)
            ],

            // for employees table
            'joining_date' => ['sometimes', 'date_format:Y-m-d'],
            'alias_name' => ['sometimes', 'string'],
            'father_name' => ['sometimes', 'string'],
            'mother_name' => ['sometimes', 'string'],
            'birth_date' => ['sometimes', 'date_format:Y-m-d'],
            'personal_email' => [
                'sometimes',
                'email',
                Rule::unique('employees')->ignore($employeeId)
            ],
            'official_email' => ['nullable', 'email'],
            'personal_contact_no' => [
                'sometimes',
                'string',
                Rule::unique('employees')->ignore($employeeId)
            ],
            'official_contact_no' => ['nullable', 'string'],

            'religion_id' => ['sometimes', 'integer', 'exists:religions,id'],
            'gender' => ['nullable', 'string', 'in:Male,Female,Other'],
            'blood_group' => ['nullable', 'string'],

            // Academic information (can be ID or new name)
            'institute_id' => ['nullable', function ($attribute, $value, $fail) {
                if ($value && !str_starts_with($value, 'new:') && !is_numeric($value)) {
                    $fail('The institute must be a valid selection or new entry.');
                }
                if ($value && is_numeric($value) && !\App\Models\Education\Institute\Institute::where('id', $value)->exists()) {
                    $fail('The selected institute is invalid.');
                }
            }],
            'education_level_id' => ['nullable', function ($attribute, $value, $fail) {
                if ($value && !str_starts_with($value, 'new:') && !is_numeric($value)) {
                    $fail('The education level must be a valid selection or new entry.');
                }
                if ($value && is_numeric($value) && !\App\Models\Education\EducationLevel\EducationLevel::where('id', $value)->exists()) {
                    $fail('The selected education level is invalid.');
                }
            }],
            'passing_year' => ['nullable', 'integer', 'min:1950', 'max:' . (date('Y') + 10)],
            'note' => ['nullable', 'string'],
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
