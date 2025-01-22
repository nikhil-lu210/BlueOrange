<?php

namespace App\Http\Requests\Administration\Settings\Role;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RoleUpdateRequest extends FormRequest
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
    public function rules(): array
    {
        $roleId = $this->route('role')->id;
        return [
            'name' => [
                'sometimes',
                'string',
                Rule::unique('roles')->ignore($roleId)
                // Rule::unique('roles')->ignore($roleId)->where(function ($query) {
                //     $query->whereNull('deleted_at');
                // }),
            ],
            'permissions' => ['required', 'array'],
        ];
    }

    public function messages()
    {
        return [
            'permissions.required' => 'You did not select any permission. Please select any permission.'
        ];
    }
}
