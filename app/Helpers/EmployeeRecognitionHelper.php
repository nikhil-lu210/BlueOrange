<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use App\Models\User\Employee\EmployeeRecognition;
use App\Services\Administration\EmployeeRecognition\EmployeeRecognitionService;

if (!function_exists('ers_badge_map')) {
    /**
     * Get the global badge map (code => [label, emoji, min, max]).
     */
    function ers_badge_map(): array
    {
        return [
            'platinum' => ['label' => 'Platinum Performer', 'emoji' => '🌟', 'min' => 90, 'max' => 100],
            'gold'     => ['label' => 'Gold Achiever',      'emoji' => '🥇', 'min' => 80, 'max' => 89],
            'silver'   => ['label' => 'Silver Contributor',  'emoji' => '🥈', 'min' => 70, 'max' => 79],
            'bronze'   => ['label' => 'Bronze Supporter',    'emoji' => '🥉', 'min' => 60, 'max' => 69],
            'rising'   => ['label' => 'Rising Star',         'emoji' => '💪', 'min' => 50, 'max' => 59],
            'learner'  => ['label' => 'Learner',             'emoji' => '🌱', 'min' => 0,  'max' => 49],
        ];
    }
}

if (!function_exists('ers_badge_for_score')) {
    /**
     * Calculate badge info for a score out of 100.
     */
    function ers_badge_for_score(int $score): array
    {
        $score = max(0, min(100, $score));
        foreach (ers_badge_map() as $code => $meta) {
            if ($score >= $meta['min'] && $score <= $meta['max']) {
                return [
                    'code'  => $code,
                    'label' => $meta['label'],
                    'emoji' => $meta['emoji'],
                    'min'   => $meta['min'],
                    'max'   => $meta['max'],
                ];
            }
        }
        // Fallback
        $meta = ers_badge_map()['learner'];
        return ['code' => 'learner', 'label' => $meta['label'], 'emoji' => $meta['emoji'], 'min' => 0, 'max' => 49];
    }
}

if (!function_exists('ers_badge_class')) {
    /**
     * Map badge code to a CSS class used in badges.
     */
    function ers_badge_class(string $code): string
    {
        return match ($code) {
            'platinum' => 'bg-success',
            'gold'     => 'bg-warning',
            'silver'   => 'bg-primary',
            'bronze'   => 'bg-danger',
            'rising'   => 'bg-dark',
            'learner'  => 'bg-label-dark',
            default    => 'bg-secondary',
        };
    }
}


if (!function_exists('badge_options')) {
    /**
     * Return available badge options.
     *
     * @return array
     */
    function badge_options(): array
    {
        return [
            'platinum' => '🌟 Platinum Performer',
            'gold'     => '🥇 Gold Achiever',
            'silver'   => '🥈 Silver Contributor',
            'bronze'   => '🥉 Bronze Supporter',
            'rising'   => '💪 Rising Star',
            'learner'  => '🌱 Learner',
        ];
    }
}

if (!function_exists('show_badge')) {
    /**
     * Display a badge by key.
     *
     * @param string|null $badge
     * @return string
     */
    function show_badge(?string $badge): string
    {
        $options = badge_options();

        return $badge && isset($options[$badge])
            ? $options[$badge]
            : '';
    }
}




if (!function_exists('ers_score_range_for_badge')) {
    /**
     * Get min/max score range for a badge code.
     */
    function ers_score_range_for_badge(string $badgeCode): array
    {
        $map = ers_badge_map();
        return isset($map[$badgeCode]) ? [$map[$badgeCode]['min'], $map[$badgeCode]['max']] : [0, 100];
    }
}

if (!function_exists('ers_is_recognition_window_open')) {
    /**
     * Check if recognition window is currently open according to service constants.
     */
    function ers_is_recognition_window_open(?Carbon $date = null): bool
    {
        $date = $date ?: now();
        $day = (int) $date->day;
        $start = EmployeeRecognitionService::WINDOW_START_DAY;
        $end   = EmployeeRecognitionService::WINDOW_END_DAY;
        return $day >= $start && $day <= $end;
    }
}


if (!function_exists('ers_top_team_performers')) {
    /**
     * Get top N performers for a team leader for a given month (default current month).
     */
    function ers_top_team_performers(User $teamLeader, ?Carbon $month = null, int $limit = 5)
    {
        $month = ($month ?: now())->copy()->startOfMonth();
        return EmployeeRecognition::with(['employee.employee', 'employee.roles', 'employee.media'])
            ->where('team_leader_id', $teamLeader->id)
            ->whereDate('month', $month->format('Y-m-d'))
            ->orderByDesc('total_score')
            ->limit($limit)
            ->get();
    }
}

if (!function_exists('ers_employee_running_or_last_month_recognition')) {
    /**
     * Get an employee's recognition for the running month (preferred) or the last month.
     * Optionally scoped to a specific team leader (e.g., active_team_leader).
     */
    function ers_employee_running_or_last_month_recognition(User $employee, ?User $teamLeader = null): ?EmployeeRecognition
    {
        $runMonth = now()->copy()->startOfMonth();
        $lastMonth = now()->copy()->subMonthNoOverflow()->startOfMonth();

        $query = EmployeeRecognition::with(['teamLeader.employee', 'teamLeader.roles', 'teamLeader.media'])
            ->where('employee_id', $employee->id);
        if ($teamLeader) {
            $query->where('team_leader_id', $teamLeader->id);
        }

        $current = (clone $query)->whereDate('month', $runMonth->format('Y-m-d'))
            ->orderByDesc('total_score')->first();
        if ($current) return $current;

        $previous = (clone $query)->whereDate('month', $lastMonth->format('Y-m-d'))
            ->orderByDesc('total_score')->first();
        return $previous;
    }
}


if (!function_exists('ers_can_view_user_recognitions')) {
    /**
     * Determine if the authenticated user can view the User Recognitions tab.
     * Rules: a) has active tl_employees OR b) has role Developer or Super Admin.
     */
    function ers_can_view_user_recognitions(User $authUser): bool
    {
        $isTl = $authUser->tl_employees()->wherePivot('is_active', true)->exists();
        $isPrivileged = method_exists($authUser, 'hasAnyRole') ? $authUser->hasAnyRole(['Developer','Super Admin']) : false;
        return $isTl || $isPrivileged;
    }
}


if (!function_exists('ers_criterion_color')) {
    /**
     * Map a 0–20 criterion score to a color key.
     */
    function ers_criterion_color(float $score): string
    {
        return $score >= 16 ? 'success' : ($score >= 12 ? 'primary' : ($score >= 8 ? 'warning' : ($score > 0 ? 'danger' : 'dark')));
    }
}


if (!function_exists('ers_average_color')) {
    /**
     * Map a 0–100 average score to a color key.
     */
    function ers_average_color(float $score): string
    {
        return $score >= 80 ? 'success' : ($score >= 60 ? 'primary' : ($score >= 40 ? 'warning' : ($score > 0 ? 'danger' : 'dark')));
    }
}

if (!function_exists('ers_badge_timeline')) {
    /**
     * Convenience wrapper to get badge timeline for an employee using the service.
     */
    function ers_badge_timeline(User $employee, ?int $year = null)
    {
        return app(EmployeeRecognitionService::class)->employeeBadgeTimeline($employee, $year);
    }
}
