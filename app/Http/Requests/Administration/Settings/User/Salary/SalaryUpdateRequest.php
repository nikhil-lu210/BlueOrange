<?php

namespace App\Http\Requests\Administration\Settings\User\Salary;

use Illuminate\Foundation\Http\FormRequest;

class SalaryUpdateRequest extends FormRequest
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
            'basic_salary' => ['required', 'numeric'],
            'house_benefit' => ['required', 'numeric'],
            'transport_allowance' => ['required', 'numeric'],
            'medical_allowance' => ['required', 'numeric'],
            'night_shift_allowance' => ['nullable', 'numeric']
        ];        
    }
}
