<?php

namespace App\Http\Requests\Administration\Event;

use Illuminate\Foundation\Http\FormRequest;

class EventStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('Event Create');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:meeting,training,celebration,conference,workshop,other',
            'status' => 'required|in:Draft,Published,Cancelled,Completed',
            'is_all_day' => 'boolean',
            'color' => 'nullable|string|regex:/^#[0-9A-F]{6}$/i',
            'max_participants' => 'nullable|integer|min:1',
            'is_public' => 'boolean',
            'reminder_before' => 'nullable|integer|min:1',
            'reminder_unit' => 'nullable|in:minutes,hours,days',
            'participants' => 'nullable|array',
            'participants.*' => 'exists:users,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required.',
            'start_date.required' => 'Start date is required.',
            'start_date.after_or_equal' => 'Start date must be today or a future date.',
            'end_date.required' => 'End date is required.',
            'end_date.after_or_equal' => 'End date must be the same as or after start date.',
            'end_time.after' => 'End time must be after start time.',
            'event_type.required' => 'Event type is required.',
            'event_type.in' => 'Please select a valid event type.',
            'status.required' => 'Event status is required.',
            'status.in' => 'Please select a valid event status.',
            'color.regex' => 'Please enter a valid hex color code.',
            'max_participants.min' => 'Maximum participants must be at least 1.',
            'reminder_before.min' => 'Reminder time must be at least 1.',
            'reminder_unit.in' => 'Please select a valid reminder unit.',
            'participants.*.exists' => 'One or more selected participants are invalid.',
        ];
    }
}
