<?php

namespace App\Http\Requests\Administration\Leave;

use Illuminate\Foundation\Http\FormRequest;

class LeaveAllowedStoreRequest extends FormRequest
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
            'earned_leave_hour' => ['required', 'integer', 'min:0', 'max:240'],
            'earned_leave_min' => ['required', 'integer', 'min:0', 'max:59'],
            'earned_leave_sec' => ['required', 'integer', 'min:0', 'max:59'],
            
            'sick_leave_hour' => ['required', 'integer', 'min:0', 'max:240'],
            'sick_leave_min' => ['required', 'integer', 'min:0', 'max:59'],
            'sick_leave_sec' => ['required', 'integer', 'min:0', 'max:59'],
            
            'casual_leave_hour' => ['required', 'integer', 'min:0', 'max:240'],
            'casual_leave_min' => ['required', 'integer', 'min:0', 'max:59'],
            'casual_leave_sec' => ['required', 'integer', 'min:0', 'max:59'],
            
            'implemented_from_month' => ['required', 'integer', 'min:1', 'max:12'],
            'implemented_from_date' => ['required', 'integer', 'min:1', 'max:31'],
            'implemented_to_month' => ['required', 'integer', 'min:1', 'max:12'],
            'implemented_to_date' => ['required', 'integer', 'min:1', 'max:31'],
        ];
    }
}
