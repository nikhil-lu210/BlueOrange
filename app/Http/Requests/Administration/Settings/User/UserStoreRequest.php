<?php

namespace App\Http\Requests\Administration\Settings\User;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
        Validator::extend('unique_userid', function ($attribute, $value, $parameters, $validator) {
            $userIdWithPrefix = 'UID' . $value;
            $existingUserId = DB::table($parameters[0])
                ->where($parameters[1], $userIdWithPrefix)
                ->first();

            return $existingUserId === null;
        });

        return [
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'userid' => [
                'required',
                'string',
                'unique_userid:users,userid', // Custom validation rule
            ],
            'role_id' => [
                'required',
                'integer',
                'exists:roles,id',
            ],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i'],

            // for employees table
            'joining_date' => ['required', 'date_format:Y-m-d'],
            'alias_name' => ['required', 'string'],
            'father_name' => ['required', 'string'],
            'mother_name' => ['required', 'string'],
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'personal_email' => ['required', 'email', 'unique:employees'],
            'official_email' => ['nullable', 'email'],
            'personal_contact_no' => ['required', 'string', 'unique:employees'],
            'official_contact_no' => ['nullable', 'string'],

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
        ];
    }

    public function messages()
    {
        return [
            'userid.unique_userid' => 'The User ID already exists in database. It should be Unique.',
            'avatar.mimes' => 'The avatar must be a JPEG, JPG or PNG image file.',
            'avatar.max' => 'The avatar size should not more then 2MB.',
        ];
    }
}
