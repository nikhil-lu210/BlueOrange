<?php

namespace App\Http\Requests\Administration\Vault;

use Illuminate\Foundation\Http\FormRequest;

class VaultStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Vault Create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['nullable', 'url'],
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:1'],
            'note' => ['nullable', 'string'],
            'viewers' => ['nullable', 'array'],
            'viewers.*' => ['exists:users,id'],
        ];
    }

    /**
     * Custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'url.url' => 'The URL must be a valid web address.',
            'username.required' => 'The username is required.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 1 characters.',
            'viewers.*.exists' => 'The selected viewer is invalid.',
        ];
    }
}
