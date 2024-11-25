<?php

namespace App\Http\Requests\Administration\Accounts\IncomeExpense\Expense;

use Illuminate\Foundation\Http\FormRequest;

class ExpenseStoreRequest extends FormRequest
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
            'category_id' => ['required', 'integer', 'exists:income_expense_categories,id'],
            'date' => ['required', 'date'],
            'title' => ['required', 'string', 'min:5', 'max:200'],
            'quantity' => ['required', 'numeric', 'min:1'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'min:10'],
        ];
    }
}
