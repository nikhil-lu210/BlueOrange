<?php

namespace App\Http\Requests\Administration\Settings\Permission;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class PermissionStoreRequest extends FormRequest
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
            'permission_module_id' => [
                'required',
                'exists:permission_modules,id',
            ],
            'name' => [
                'required',
                'array',
                Rule::unique('permissions')->where(function ($query) {
                    return $query->where('permission_module_id', $this->input('permission_module_id'));
                }),
            ],
        ];
    }

    public function messages()
    {
        return [
            'permission_module_id.exists' => 'The selected permission module does not exist.',
            'name.unique' => 'A permission with this name already exists in the selected module.',
        ];
    }
}
