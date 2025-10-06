<?php

namespace App\Http\Requests\Administration\Leave;

use Illuminate\Foundation\Http\FormRequest;
use App\Services\Administration\Leave\LeaveValidationService;
use Illuminate\Validation\ValidationException;

class LeaveHistoryStoreRequest extends FormRequest
{
    protected $leaveValidationService;

    public function __construct(LeaveValidationService $leaveValidationService)
    {
        parent::__construct();
        $this->leaveValidationService = $leaveValidationService;
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by the route middleware and controller
        // Additional checks are performed in the validation methods
        return auth()->check();
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
            'reason' => ['required', 'string', 'min:10', 'max:1000'],
            'is_paid_leave' => ['nullable', 'boolean'],
            'leave_days.date' => ['required', 'array', 'min:1'],
            'leave_days.date.*' => ['date'],
            'total_leave.hour' => ['required', 'array'],
            'total_leave.hour.*' => ['integer', 'min:0', 'max:8'],
            'total_leave.min' => ['required', 'array'],
            'total_leave.min.*' => ['integer', 'min:0', 'max:59'],
            'total_leave.sec' => ['required', 'array'],
            'total_leave.sec.*' => ['integer', 'min:0', 'max:59'],
            'files.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf,doc,docx', 'max:2048'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateLeaveBalance($validator);
            $this->validateDuplicateDates($validator);
            $this->validateBusinessRules($validator);
        });
    }

    /**
     * Validate leave balance availability.
     */
    protected function validateLeaveBalance($validator)
    {
        if (!$this->has('type') || !$this->has('total_leave.hour') || !$this->has('leave_days.date')) {
            return;
        }

        $user = auth()->user();
        $leaveType = $this->input('type');

        // Calculate total leave requested across all days
        $totalSeconds = 0;
        $leaveDays = $this->input('leave_days.date', []);

        foreach ($this->input('total_leave.hour', []) as $index => $hours) {
            $minutes = $this->input("total_leave.min.{$index}", 0);
            $seconds = $this->input("total_leave.sec.{$index}", 0);

            // Validate that each day has some leave time
            if ($hours == 0 && $minutes == 0 && $seconds == 0) {
                $validator->errors()->add("total_leave.hour.{$index}", 'Each leave day must have at least some leave time.');
                continue;
            }

            $totalSeconds += ($hours * 3600) + ($minutes * 60) + $seconds;
        }

        if ($totalSeconds === 0) {
            $validator->errors()->add('total_leave', 'Total leave time cannot be zero.');
            return;
        }

        $totalLeaveFormatted = sprintf('%02d:%02d:%02d',
            floor($totalSeconds / 3600),
            floor(($totalSeconds % 3600) / 60),
            $totalSeconds % 60
        );

        try {
            $validation = $this->leaveValidationService->validateLeaveBalance(
                $user,
                $leaveType,
                $totalLeaveFormatted,
                $leaveDays[0] ?? now()->format('Y-m-d') // Use first date for year calculation
            );

            if (!$validation['is_sufficient']) {
                $validator->errors()->add('type', $validation['message']);
            }
        } catch (\Exception $e) {
            $validator->errors()->add('leave_balance', 'Unable to validate leave balance: ' . $e->getMessage());
        }
    }

    /**
     * Validate no duplicate dates in the same request.
     */
    protected function validateDuplicateDates($validator)
    {
        $dates = $this->input('leave_days.date', []);
        $uniqueDates = array_unique($dates);

        if (count($dates) !== count($uniqueDates)) {
            $validator->errors()->add('leave_days.date', 'Duplicate dates are not allowed in the same leave request.');
        }
    }

    /**
     * Validate business rules.
     */
    protected function validateBusinessRules($validator)
    {
        $user = auth()->user();
        $leaveType = $this->input('type');
        $dates = $this->input('leave_days.date', []);

                // Check for existing leave requests on the same dates
        // User cannot create leave on the same day if there's any Pending or Approved leave
        // But can create multiple leaves if existing ones are Canceled or Rejected
        foreach ($dates as $index => $date) {
            /** @var \App\Models\User $user */
            $existingActiveLeave = $user->leave_histories()
                ->where('date', $date)
                ->whereIn('status', ['Pending', 'Approved'])
                ->first();

            if ($existingActiveLeave) {
                $status = $existingActiveLeave->status;
                $validator->errors()->add("leave_days.date.{$index}", "You already have a {$status} leave request for {$date}. Please cancel or wait for approval before creating a new request.");
            }
        }

        // Validate sick leave requires files
        if ($leaveType === 'Sick' && (!$this->hasFile('files') || empty($this->file('files')))) {
            $validator->errors()->add('files', 'Sick leave requires prescription or medical certificate.');
        }
    }

    public function messages(): array
    {
        return [
            'leave_days.date.required' => 'Please provide at least one date for the leave days.',
            'leave_days.date.*.date' => 'Each date must be a valid date format.',
            'total_leave.hour.required' => 'Please provide total leave hours.',
            'total_leave.hour.*.integer' => 'Total leave hours must be an integer.',
            'total_leave.hour.*.min' => 'Total leave hours cannot be less than 0.',
            'total_leave.hour.*.max' => 'Total leave hours cannot exceed 8 hours.',
            'total_leave.min.required' => 'Please provide total leave minutes.',
            'total_leave.min.*.integer' => 'Total leave minutes must be an integer.',
            'total_leave.min.*.min' => 'Total leave minutes cannot be less than 0.',
            'total_leave.min.*.max' => 'Total leave minutes cannot exceed 59 minutes.',
            'total_leave.sec.required' => 'Please provide total leave seconds.',
            'total_leave.sec.*.integer' => 'Total leave seconds must be an integer.',
            'total_leave.sec.*.min' => 'Total leave seconds cannot be less than 0.',
            'total_leave.sec.*.max' => 'Total leave seconds cannot exceed 59 seconds.',
            'files.*.mimes' => 'Only jpg, jpeg, png, pdf, doc, and docx files are allowed.',
            'files.*.max' => 'Each file must not exceed 2MB.',
        ];
    }

}
