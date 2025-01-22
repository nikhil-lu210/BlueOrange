<?php

namespace App\Http\Requests\Administration\Accounts\IncomeExpense\Income;

use Illuminate\Foundation\Http\FormRequest;

class IncomeStoreRequest extends FormRequest
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
            'source' => ['required', 'string', 'min:5', 'max:200'],
            'total' => ['required', 'numeric', 'min:0.01'],
            'description' => ['required', 'string', 'min:10'],
            'files.*' => [
                            'nullable',
                            'file',
                            'mimes:jpg,jpeg,png,gif,webp,pdf,xls,xlsx,doc,docx,txt,csv,zip,rar',
                            'max:10240' // Max size in kilobytes (10 MB here)
                        ],
        ];
    }
}
