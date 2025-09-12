<?php

namespace App\Http\Requests\Administration\Settings\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => ['required', 'integer', 'exists:permissions,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Role name is required.',
            'name.unique' => 'A role with this name already exists.',
            'name.max' => 'Role name cannot exceed 255 characters.',
            'permissions.required' => 'At least one permission must be selected.',
            'permissions.min' => 'At least one permission must be selected.',
            'permissions.*.exists' => 'One or more selected permissions are invalid.',
        ];
    }
}
