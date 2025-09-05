<?php

namespace App\Http\Requests\Administration\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\WorkScheduleItem\WorkScheduleItem;

class WorkScheduleUpdateRequest extends FormRequest
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
        $rules = [
            'work_items' => 'required|array|min:1',
        ];

        // Add validation rules for work items
        if ($this->has('work_items')) {
            foreach ($this->input('work_items') as $index => $item) {
                $rules["work_items.{$index}.start_time"] = 'required|date_format:H:i';
                $rules["work_items.{$index}.end_time"] = 'required|date_format:H:i|after:work_items.{$index}.start_time';
                $rules["work_items.{$index}.work_type"] = 'required|in:' . implode(',', WorkScheduleItem::getWorkTypes());
                $rules["work_items.{$index}.work_title"] = 'required|string|max:255';
            }
        }

        return $rules;
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'work_items.required' => 'Please add at least one work item.',
            'work_items.array' => 'Work items must be an array.',
            'work_items.min' => 'Please add at least one work item.',
            'work_items.*.start_time.required' => 'Start time is required for each work item.',
            'work_items.*.start_time.date_format' => 'Start time must be in HH:MM format.',
            'work_items.*.end_time.required' => 'End time is required for each work item.',
            'work_items.*.end_time.date_format' => 'End time must be in HH:MM format.',
            'work_items.*.end_time.after' => 'End time must be after start time.',
            'work_items.*.work_type.required' => 'Work type is required for each work item.',
            'work_items.*.work_type.in' => 'Invalid work type selected.',
            'work_items.*.work_title.required' => 'Work title is required for each work item.',
            'work_items.*.work_title.max' => 'Work title cannot exceed 255 characters.',
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
            'work_items' => 'work items',
        ];
    }
}
