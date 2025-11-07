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

        // Get certificate configuration
        $certificateTypes = config('certificate.types');
        $selectedType = $this->input('type');

        if (isset($certificateTypes[$selectedType])) {
            $typeConfig = $certificateTypes[$selectedType];

            // Add validation rules for required fields (excluding basic fields)
            $basicFields = ['user_id', 'type', 'issue_date'];
            $requiredFields = array_diff($typeConfig['required_fields'], $basicFields);

            foreach ($requiredFields as $field) {
                $rules[$field] = $this->getFieldValidationRule($field);
            }

            // Add validation rules for optional fields
            if (isset($typeConfig['optional_fields'])) {
                foreach ($typeConfig['optional_fields'] as $field) {
                    $rules[$field] = $this->getFieldValidationRule($field, false);
                }
            }
        }

        return $rules;
    }

    /**
     * Get validation rule for a specific field
     */
    private function getFieldValidationRule(string $field, bool $required = true): string
    {
        $baseRule = $required ? 'required' : 'nullable';

        switch ($field) {
            case 'salary':
                return $baseRule . '|numeric|min:0';

            case 'resignation_date':
            case 'resign_application_date':
            case 'resignation_approval_date':
            case 'release_date':
            case 'leave_starts_from':
            case 'leave_ends_on':
                return $this->getDateValidationRule($field, $required);

            case 'release_reason':
            case 'country_name':
            case 'visiting_purpose':
                return $baseRule . '|string|max:255';

            default:
                return $baseRule . '|string|max:255';
        }
    }

    /**
     * Get date validation rule with relationship constraints
     */
    private function getDateValidationRule(string $field, bool $required = true): string
    {
        $baseRule = $required ? 'required' : 'nullable';
        $rule = $baseRule . '|date';

        // Add relationship constraints for resignation workflow
        switch ($field) {
            case 'resignation_approval_date':
                if ($this->has('resign_application_date')) {
                    $rule .= '|after_or_equal:resign_application_date';
                }
                break;

            // case 'release_date':
            //     if ($this->has('resignation_approval_date')) {
            //         $rule .= '|after_or_equal:resignation_approval_date';
            //     } elseif ($this->has('resignation_date')) {
            //         $rule .= '|after_or_equal:resignation_date';
            //     }
            //     break;

            case 'leave_ends_on':
                if ($this->has('leave_starts_from')) {
                    $rule .= '|after:leave_starts_from';
                }
                break;
        }

        return $rule;
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

            'salary.required' => 'Please provide the salary amount.',
            'salary.numeric' => 'Salary must be a valid number.',
            'salary.min' => 'Salary cannot be negative.',
            'resignation_date.required' => 'Please provide the resignation date.',
            'resignation_date.date' => 'Please provide a valid resignation date.',
            'resign_application_date.required' => 'Please provide the resignation application date.',
            'resign_application_date.date' => 'Please provide a valid resignation application date.',
            'resignation_approval_date.required' => 'Please provide the resignation approval date.',
            'resignation_approval_date.date' => 'Please provide a valid resignation approval date.',
            'resignation_approval_date.after_or_equal' => 'Resignation approval date must be on or after the application date.',
            'release_date.required' => 'Please provide the release date.',
            'release_date.date' => 'Please provide a valid release date.',
            'release_date.after_or_equal' => 'Release date must be on or after the resignation approval date.',
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
            'salary' => 'salary',
            'resignation_date' => 'resignation date',
            'resign_application_date' => 'resignation application date',
            'resignation_approval_date' => 'resignation approval date',
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
