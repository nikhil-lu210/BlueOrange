<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User\Employee\EmployeeRecognition;

class EmployeeRecognitionPolicy
{
    public function view(User $user, EmployeeRecognition $recognition): bool
    {
        return $user->id === $recognition->employee_id || $user->id === $recognition->team_leader_id || $user->can('Employee Hiring Everything');
    }

    public function create(User $user): bool
    {
        // Team leaders only (has team members)
        return $user->tl_employees()->wherePivot('is_active', true)->exists();
    }

    public function viewAny(User $user): bool
    {
        // Admin/HR roles, fallback to permission check; adjust as needed
        return $user->can('Employee Hiring Everything') || $user->can('User Read') || $user->hasRole('Admin');
    }
}
