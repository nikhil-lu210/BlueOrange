<?php

namespace App\Http\Requests\Administration\Settings\System\Holiday;

use Illuminate\Foundation\Http\FormRequest;

class HolidayStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'date' => 'required|date_format:Y-m-d',
            'description' => 'required|string',
            'is_active' => 'nullable',
        ];
    }
}
