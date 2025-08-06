<?php

namespace App\Services\Administration\Dashboard;
use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\User\Employee\EmployeeRecognition;

class EmployeeRecognitionService
{
    public function isEligibleToGiveRecognition(User $user): bool
    {
        // dd($user->tl_employees, $user->tl_employees->count());
        return !empty($user->tl_employees) && $user->tl_employees->count() > 0;
    }

    public function getRecentRecognitions(User $user, $limit = 5)
    {
        return EmployeeRecognition::where('recognizer_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function needsReminder(User $user): bool
    {
        $last = EmployeeRecognition::where('recognizer_id', $user->id)
            ->orderByDesc('created_at')
            ->first();
        if (!$last) return true;
        return Carbon::parse($last->created_at)->diffInDays(now()) > 15;
    }

    public function giveRecognition(array $data): EmployeeRecognition
    {
        $recognition = EmployeeRecognition::create($data);
        // TODO: Trigger announcement/notification
        return $recognition;
    }

    public function getAnnouncements($limit = 10)
    {
        return EmployeeRecognition::with(['employee', 'recognizer'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getRecognitionHistory(User $employee)
    {
        return EmployeeRecognition::where('employee_id', $employee->id)
            ->orderByDesc('created_at')
            ->get();
    }
}
