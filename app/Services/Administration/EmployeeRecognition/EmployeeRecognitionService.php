<?php

namespace App\Services\Administration\EmployeeRecognition;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\User\Employee\EmployeeRecognition;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;

class EmployeeRecognitionService
{
    public function canTeamLeaderEvaluate(User $teamLeader, User $employee): bool
    {
        // Team leader can only evaluate their active team members
        return $teamLeader->tl_employees()
            ->where('users.id', $employee->id)
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function withinRecognitionWindow(Carbon $date = null): bool
    {
        $date = $date ?: now();
        $day = (int) $date->day;

        $start = (int) config('ers.recognition_window.start_day');
        $end   = (int) config('ers.recognition_window.end_day');

        return $day >= $start && $day <= $end;
    }


    public function upsertEmployeeRecognition(User $teamLeader, User $employee, array $scores, Carbon $month): EmployeeRecognition
    {
        $isAdmin = Gate::allows('Recognition Everything');
        if (!$isAdmin && !$this->canTeamLeaderEvaluate($teamLeader, $employee)) {
            throw ValidationException::withMessages(['employee_id' => 'You can only recognize your own team members.']);
        }
        if (!$isAdmin && !$this->withinRecognitionWindow()) {
            throw ValidationException::withMessages(['month' => 'Recognitions are only allowed within the configured submission window.']);
        }

        $month = $month->copy()->startOfMonth();

        // Ensure once per month per employee per leader
        $existing = EmployeeRecognition::where('employee_id', $employee->id)
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->first();

        $payload = [
            'employee_id'    => $employee->id,
            'team_leader_id' => $teamLeader->id,
            'month'          => $month->format('Y-m-d'),
            'behavior'       => (int)($scores['behavior'] ?? 0),
            'appreciation'   => (int)($scores['appreciation'] ?? 0),
            'leadership'     => (int)($scores['leadership'] ?? 0),
            'loyalty'        => (int)($scores['loyalty'] ?? 0),
            'dedication'     => (int)($scores['dedication'] ?? 0),
        ];
        $payload['total_score'] = $payload['behavior'] + $payload['appreciation'] + $payload['leadership'] + $payload['loyalty'] + $payload['dedication'];

        if ($existing) {
            if ($existing->isLocked()) {
                throw ValidationException::withMessages(['month' => "This month's recognition is locked and cannot be updated."]);
            }
            $existing->update($payload);
            $recognition = $existing->refresh();
        } else {
            $recognition = EmployeeRecognition::create($payload);
        }

        // Badge is derived from total_score; computed dynamically (no persistence needed)
        return $recognition;
    }

    public function isMonthLocked(User $teamLeader, Carbon $month): bool
    {
        $month = $month->copy()->startOfMonth();
        return EmployeeRecognition::where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->whereNotNull('locked_at')
            ->exists();
    }

    public function adminBrowseRecognitions(?int $year = null, ?int $month = null, ?int $teamLeaderId = null, ?int $employeeId = null)
    {
        $q = EmployeeRecognition::with(['employee', 'teamLeader'])
            ->when($year, fn($qq) => $qq->forYear($year))
            ->when($month, function ($qq) use ($month) {
                $start = Carbon::createFromDate(now()->year, 1, 1)->month((int)$month)->startOfMonth();
                $qq->forMonth($start);
            })
            ->when($teamLeaderId, fn($qq) => $qq->where('team_leader_id', (int)$teamLeaderId))
            ->when($employeeId, fn($qq) => $qq->where('employee_id', (int)$employeeId))
            ->orderByDesc('month')
            ->orderByDesc('total_score');
        return $q->get();
    }

    public function notifyEmployeesOfRecognition(User $teamLeader, Carbon $month): void
    {
        $month = $month->copy()->startOfMonth();
        $rows = EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->get();

        // If an EmployeeRecognitionPublished notification exists, send it; otherwise no-op
        $notificationClass = '\\App\\Notifications\\Administration\\Recognition\\EmployeeRecognitionPublished';
        if (class_exists($notificationClass)) {
            foreach ($rows as $row) {
                $employee = $row->employee;
                if ($employee) {
                    $employee->notify(new $notificationClass($row));
                }
            }
        }
    }

    public function lockMonthForTeamLeader(User $teamLeader, Carbon $month): void
    {
        $month = $month->copy()->startOfMonth();
        EmployeeRecognition::where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->whereNull('locked_at')
            ->update(['locked_at' => now()]);
    }

    public function computeRank(int $score): ?string
    {
        if ($score >= 90) return 'first';
        if ($score >= 70) return 'second';
        if ($score >= 60) return 'third';
        return null;
    }

    // Leaderboard helpers
    public function monthlyLeaderboard(User $teamLeader, Carbon $month, ?string $badgeCode = null)
    {
        $month = $month->copy()->startOfMonth();
        return EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->when($badgeCode, function ($q) use ($badgeCode) {
                [$min, $max] = $this->getScoreRangeForBadgeCode($badgeCode);
                $q->whereBetween('total_score', [$min, $max]);
            })
            ->orderByDesc('total_score')
            ->get();
    }

    public function quarterlyLeaderboard(User $teamLeader, int $year, int $quarter)
    {
        return EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forQuarter($year, $quarter)
            ->select('employee_id', DB::raw('AVG(total_score) as avg_score'), DB::raw('SUM(total_score) as total'))
            ->groupBy('employee_id')
            ->orderByDesc('avg_score')
            ->get();
    }

    public function yearlyLeaderboard(User $teamLeader, int $year)
    {
        return EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forYear($year)
            ->select('employee_id', DB::raw('AVG(total_score) as avg_score'), DB::raw('SUM(total_score) as total'))
            ->groupBy('employee_id')
            ->orderByDesc('avg_score')
            ->get();
    }

    // Admin analytics
    public function adminTopPerformersByMonth(Carbon $month, ?string $badgeCode = null)
    {
        $month = $month->copy()->startOfMonth();
        return EmployeeRecognition::with([
                'employee.employee',
                'employee.roles',
                'employee.media',
                'teamLeader.employee',
                'teamLeader.roles',
                'teamLeader.media',
            ])
            ->forMonth($month)
            ->when($badgeCode, function ($q) use ($badgeCode) {
                [$min, $max] = $this->getScoreRangeForBadgeCode($badgeCode);
                $q->whereBetween('total_score', [$min, $max]);
            })
            ->orderByDesc('total_score')
            ->get();
    }

    public function employeeTrend(User $employee, int $year)
    {
        return EmployeeRecognition::where('employee_id', $employee->id)
            ->forYear($year)
            ->orderBy('month')
            ->get();
    }

    public function compareTeamsByMonth(Carbon $month)
    {
        $month = $month->copy()->startOfMonth();
        return EmployeeRecognition::with([
                'teamLeader.employee',
                'teamLeader.roles',
                'teamLeader.media',
            ])
            ->forMonth($month)
            ->select('team_leader_id', DB::raw('AVG(total_score) as avg_score'))
            ->groupBy('team_leader_id')
            ->orderByDesc('avg_score')
            ->get();
    }

    // --------------------- Badge System ---------------------
    public function badgeCodeForScore(int $score): string
    {
        return match (true) {
            $score >= 90 => 'platinum',
            $score >= 80 => 'gold',
            $score >= 70 => 'silver',
            $score >= 60 => 'bronze',
            $score >= 50 => 'rising',
            default       => 'learner',
        };
    }

    public function badgeLabelForScore(int $score): string
    {
        return match ($this->badgeCodeForScore($score)) {
            'platinum' => 'Platinum Performer',
            'gold'     => 'Gold Achiever',
            'silver'   => 'Silver Contributor',
            'bronze'   => 'Bronze Supporter',
            'rising'   => 'Rising Star',
            'learner'  => 'Learner',
        };
    }

    public function badgeEmojiForScore(int $score): string
    {
        return match ($this->badgeCodeForScore($score)) {
            'platinum' => '🌟',
            'gold'     => '🥇',
            'silver'   => '🥈',
            'bronze'   => '🥉',
            'rising'   => '💪',
            'learner'  => '🌱',
        };
    }

    public function getScoreRangeForBadgeCode(string $badgeCode): array
    {
        return match ($badgeCode) {
            'platinum' => [90, 100],
            'gold'     => [80, 89],
            'silver'   => [70, 79],
            'bronze'   => [60, 69],
            'rising'   => [50, 59],
            'learner'  => [0, 49],
            default    => [0, 100],
        };
    }

    public function aggregateBadgeForAverage(float $avgScore): array
    {
        $code  = $this->badgeCodeForScore((int) round($avgScore));
        $label = $this->badgeLabelForScore((int) round($avgScore));
        $emoji = $this->badgeEmojiForScore((int) round($avgScore));
        return compact('code', 'label', 'emoji');
    }

    public function employeeBadgeTimeline(User $employee, ?int $year = null)
    {
        $q = EmployeeRecognition::where('employee_id', $employee->id)
            ->orderBy('month');
        if ($year) {
            $q->forYear($year);
        }
        return $q->get()->map(function ($row) {
            $score = (int) $row->total_score;
            return [
                'month' => $row->month,
                'total_score' => $score,
                'badge' => [
                    'code'  => $this->badgeCodeForScore($score),
                    'label' => $this->badgeLabelForScore($score),
                    'emoji' => $this->badgeEmojiForScore($score),
                ],
            ];
        });
    }

    public function orderTeamMembersByScore(User $teamLeader, Carbon $month)
    {
        $month = $month->copy()->startOfMonth();
        // Join recognitions for the month and order by total_score desc, nulls last
        return $teamLeader->tl_employees()->with(['employee', 'media'])
            ->wherePivot('is_active', true)
            ->leftJoin('employee_recognitions as eme', function ($join) use ($teamLeader, $month) {
                $join->on('users.id', '=', 'eme.employee_id')
                    ->where('eme.team_leader_id', '=', $teamLeader->id)
                    ->whereDate('eme.month', '=', $month->format('Y-m-d'));
            })
            ->orderByDesc('eme.total_score')
            ->orderBy('users.name')
            ->select('users.*')
            ->get();
    }
}
