<?php

namespace App\Policies;

use App\Models\User;
use App\Models\User\Employee\EmployeeMonthlyEvaluation;

class EmployeeMonthlyEvaluationPolicy
{
    public function view(User $user, EmployeeMonthlyEvaluation $evaluation): bool
    {
        return $user->id === $evaluation->employee_id || $user->id === $evaluation->team_leader_id || $user->can('Employee Hiring Everything');
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
