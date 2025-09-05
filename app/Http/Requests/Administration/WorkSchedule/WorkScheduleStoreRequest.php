<?php

namespace App\Http\Requests\Administration\WorkSchedule;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\WorkSchedule\WorkSchedule;
use App\Models\WorkScheduleItem\WorkScheduleItem;

class WorkScheduleStoreRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'weekdays' => 'required|array|min:1',
            'weekdays.*' => 'required|string|in:' . implode(',', WorkSchedule::getWeekdays()),
            'same_schedule_for_all' => 'boolean',
        ];

        // Add validation rules based on mode
        // Check if same_schedule_for_all is present and truthy
        $sameScheduleForAll = $this->has('same_schedule_for_all') && $this->input('same_schedule_for_all');
        
        if ($sameScheduleForAll) {
            // Same schedule for all weekdays - validate work_items
            $rules['work_items'] = 'required|array|min:1';
            
            if ($this->has('work_items') && is_array($this->input('work_items'))) {
                foreach ($this->input('work_items') as $index => $item) {
                    $rules["work_items.{$index}.start_time"] = 'required|date_format:H:i';
                    $rules["work_items.{$index}.end_time"] = 'required|date_format:H:i|after:work_items.{$index}.start_time';
                    $rules["work_items.{$index}.work_type"] = 'required|in:' . implode(',', WorkScheduleItem::getWorkTypes());
                    $rules["work_items.{$index}.work_title"] = 'required|string|max:255';
                }
            }
        } else {
            // Individual schedule for each weekday - validate weekday_work_items only
            $selectedWeekdays = $this->input('weekdays', []);
            
            foreach ($selectedWeekdays as $weekday) {
                $rules["weekday_work_items.{$weekday}"] = 'required|array|min:1';
                
                if ($this->has("weekday_work_items.{$weekday}")) {
                    foreach ($this->input("weekday_work_items.{$weekday}") as $index => $item) {
                        $rules["weekday_work_items.{$weekday}.{$index}.start_time"] = 'required|date_format:H:i';
                        $rules["weekday_work_items.{$weekday}.{$index}.end_time"] = 'required|date_format:H:i|after:weekday_work_items.{$weekday}.{$index}.start_time';
                        $rules["weekday_work_items.{$weekday}.{$index}.work_type"] = 'required|in:' . implode(',', WorkScheduleItem::getWorkTypes());
                        $rules["weekday_work_items.{$weekday}.{$index}.work_title"] = 'required|string|max:255';
                    }
                }
            }
        }

        return $rules;
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // If in individual mode, remove work_items validation errors
            $sameScheduleForAll = $this->has('same_schedule_for_all') && $this->input('same_schedule_for_all');
            
            if (!$sameScheduleForAll) {
                $errors = $validator->errors();
                $workItemErrors = $errors->get('work_items');
                if (!empty($workItemErrors)) {
                    $errors->forget('work_items');
                }
                
                // Remove individual work_items field errors
                foreach ($errors->keys() as $key) {
                    if (str_starts_with($key, 'work_items.')) {
                        $errors->forget($key);
                    }
                }
            }
        });
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Please select an employee.',
            'user_id.exists' => 'The selected employee does not exist.',
            'weekdays.required' => 'Please select at least one weekday.',
            'weekdays.array' => 'Weekdays must be an array.',
            'weekdays.min' => 'Please select at least one weekday.',
            'weekdays.*.required' => 'Each weekday is required.',
            'weekdays.*.in' => 'Invalid weekday selected.',
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
            'weekday_work_items.*.required' => 'Please add at least one work item for each selected weekday.',
            'weekday_work_items.*.array' => 'Work items must be an array.',
            'weekday_work_items.*.min' => 'Please add at least one work item for each selected weekday.',
            'weekday_work_items.*.*.start_time.required' => 'Start time is required for each work item.',
            'weekday_work_items.*.*.start_time.date_format' => 'Start time must be in HH:MM format.',
            'weekday_work_items.*.*.end_time.required' => 'End time is required for each work item.',
            'weekday_work_items.*.*.end_time.date_format' => 'End time must be in HH:MM format.',
            'weekday_work_items.*.*.end_time.after' => 'End time must be after start time.',
            'weekday_work_items.*.*.work_type.required' => 'Work type is required for each work item.',
            'weekday_work_items.*.*.work_type.in' => 'Invalid work type selected.',
            'weekday_work_items.*.*.work_title.required' => 'Work title is required for each work item.',
            'weekday_work_items.*.*.work_title.max' => 'Work title cannot exceed 255 characters.',
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
            'user_id' => 'employee',
            'weekdays' => 'weekdays',
            'work_items' => 'work items',
        ];
    }
}
