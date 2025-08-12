<?php

declare(strict_types=1);

namespace App\Http\Requests\Administration\EmployeeRecognition;

use App\Services\Administration\EmployeeRecognition\RecognitionWindowService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreRecognitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && Gate::allows('Recognition Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'month' => 'required|date',
            'scores' => 'required|array',
            'scores.*.behavior' => 'required|integer|min:0|max:20',
            'scores.*.appreciation' => 'required|integer|min:0|max:20',
            'scores.*.leadership' => 'required|integer|min:0|max:20',
            'scores.*.loyalty' => 'required|integer|min:0|max:20',
            'scores.*.dedication' => 'required|integer|min:0|max:20',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'month' => 'recognition month',
            'scores.*.behavior' => 'behavior score',
            'scores.*.appreciation' => 'appreciation score',
            'scores.*.leadership' => 'leadership score',
            'scores.*.loyalty' => 'loyalty score',
            'scores.*.dedication' => 'dedication score',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'scores.required' => 'You must provide recognition scores for at least one employee.',
            'scores.*.behavior.required' => 'Behavior score is required for all employees.',
            'scores.*.appreciation.required' => 'Appreciation score is required for all employees.',
            'scores.*.leadership.required' => 'Leadership score is required for all employees.',
            'scores.*.loyalty.required' => 'Loyalty score is required for all employees.',
            'scores.*.dedication.required' => 'Dedication score is required for all employees.',
            'scores.*.behavior.min' => 'Behavior score must be at least :min.',
            'scores.*.behavior.max' => 'Behavior score cannot exceed :max.',
            'scores.*.appreciation.min' => 'Appreciation score must be at least :min.',
            'scores.*.appreciation.max' => 'Appreciation score cannot exceed :max.',
            'scores.*.leadership.min' => 'Leadership score must be at least :min.',
            'scores.*.leadership.max' => 'Leadership score cannot exceed :max.',
            'scores.*.loyalty.min' => 'Loyalty score must be at least :min.',
            'scores.*.loyalty.max' => 'Loyalty score cannot exceed :max.',
            'scores.*.dedication.min' => 'Dedication score must be at least :min.',
            'scores.*.dedication.max' => 'Dedication score cannot exceed :max.',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $canManageAll = Gate::allows('Recognition Everything');
            
            // Check if submission is within window for non-admin users
            if (!$canManageAll) {
                $windowService = app(RecognitionWindowService::class);
                if (!$windowService->isWithinWindow()) {
                    $validator->errors()->add('month', 'Recognition submission is only allowed within the configured window period.');
                }
            }
        });
    }
}