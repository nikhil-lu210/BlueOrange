<?php

namespace App\Http\Requests\Administration\Certificate;

use App\Models\Certificate\Certificate;
use Illuminate\Foundation\Http\FormRequest;

class CertificateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Certificate Create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:' . implode(',', Certificate::getTypes()),
            'issue_date' => 'required|date',
        ];

        // Add conditional validation based on certificate type (following form comments)
        switch ($this->input('type')) {
            case 'Appointment Letter':
                // Joining Date and Salary are required for Appointment Letter
                $rules = array_merge($rules, [
                    'joining_date' => 'required|date',
                    'salary' => 'required|numeric|min:0',
                ]);
                break;

            case 'Employment Certificate':
                // Joining Date and Salary are required for Employment Certificate
                $rules = array_merge($rules, [
                    'joining_date' => 'required|date',
                    'salary' => 'required|numeric|min:0',
                ]);
                break;

            case 'Experience Letter':
                // Only Resignation Date is required for Experience Letter
                $rules = array_merge($rules, [
                    'resignation_date' => 'required|date',
                ]);
                break;

            case 'Release Letter':
                // Release Date and Release Reason are required for Release Letter
                $rules = array_merge($rules, [
                    'release_date' => 'required|date',
                    'release_reason' => 'required|string|max:255',
                ]);
                break;

            case 'NOC/No Objection Letter':
                // Country Name, Visiting Purpose, Leave Starts From are required
                // Leave Ends On is optional for NOC Letter
                $rules = array_merge($rules, [
                    'country_name' => 'required|string|max:255',
                    'visiting_purpose' => 'required|string|max:255',
                    'leave_starts_from' => 'required|date',
                    'leave_ends_on' => 'nullable|date|after:leave_starts_from',
                ]);
                break;
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select an employee.',
            'user_id.exists' => 'The selected employee does not exist.',
            'type.required' => 'Please select a certificate type.',
            'type.in' => 'The selected certificate type is invalid.',
            'issue_date.required' => 'Please provide the issue date.',
            'issue_date.date' => 'Please provide a valid issue date.',
            'joining_date.required' => 'Please provide the joining date.',
            'joining_date.date' => 'Please provide a valid joining date.',
            'salary.required' => 'Please provide the salary amount.',
            'salary.numeric' => 'Salary must be a valid number.',
            'salary.min' => 'Salary cannot be negative.',
            'resignation_date.required' => 'Please provide the resignation date.',
            'resignation_date.date' => 'Please provide a valid resignation date.',
            'resignation_date.after' => 'Resignation date must be after joining date.',
            'release_date.required' => 'Please provide the release date.',
            'release_date.date' => 'Please provide a valid release date.',
            'release_date.after' => 'Release date must be after joining date.',
            'release_reason.required' => 'Please provide the release reason.',
            'release_reason.max' => 'Release reason cannot exceed 255 characters.',
            'country_name.required' => 'Please provide the country name.',
            'country_name.max' => 'Country name cannot exceed 255 characters.',
            'visiting_purpose.required' => 'Please provide the visiting purpose.',
            'visiting_purpose.max' => 'Visiting purpose cannot exceed 255 characters.',
            'leave_starts_from.required' => 'Please provide the leave start date.',
            'leave_starts_from.date' => 'Please provide a valid leave start date.',
            'leave_ends_on.date' => 'Please provide a valid leave end date.',
            'leave_ends_on.after' => 'Leave end date must be after leave start date.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'user_id' => 'employee',
            'type' => 'certificate type',
            'issue_date' => 'issue date',
            'joining_date' => 'joining date',
            'salary' => 'salary',
            'resignation_date' => 'resignation date',
            'release_date' => 'release date',
            'release_reason' => 'release reason',
            'country_name' => 'country name',
            'visiting_purpose' => 'visiting purpose',
            'leave_starts_from' => 'leave start date',
            'leave_ends_on' => 'leave end date',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and prepare data before validation
        if ($this->has('salary')) {
            $this->merge([
                'salary' => $this->input('salary') ? (float) str_replace(',', '', $this->input('salary')) : null,
            ]);
        }

        // Trim string fields
        $stringFields = ['release_reason', 'country_name', 'visiting_purpose'];
        foreach ($stringFields as $field) {
            if ($this->has($field)) {
                $this->merge([
                    $field => $this->input($field) ? trim($this->input($field)) : null,
                ]);
            }
        }
    }
}
