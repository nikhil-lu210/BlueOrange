<?php

namespace App\Http\Requests\Administration\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveApprovalRequest extends FormRequest
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
            'type' => ['required', 'in:Earned,Casual,Sick'],
            'is_paid_leave' => ['nullable', 'in:Paid,Unpaid'],
        ];
    }
}
