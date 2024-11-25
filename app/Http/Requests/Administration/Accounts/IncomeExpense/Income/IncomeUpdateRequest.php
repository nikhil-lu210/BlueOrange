<?php

namespace App\Http\Requests\Administration\Accounts\IncomeExpense\Income;

use Illuminate\Foundation\Http\FormRequest;

class IncomeUpdateRequest extends FormRequest
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
            'category_id' => ['sometimes','integer','exists:income_expense_categories,id'],
            'date' => ['sometimes','date'],
            'source' => ['sometimes','string','min:5', 'max:200'],
            'total' => ['sometimes', 'numeric', 'min:0.01'],
            'description' => ['sometimes','string','min:10'],
        ];
    }
}
