<?php

declare(strict_types=1);

namespace App\Repositories\Administration\EmployeeRecognition;

use App\Models\User;
use App\Models\User\Employee\EmployeeRecognition;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class EmployeeRecognitionRepository
{
    /**
     * Check if a team leader can evaluate an employee
     */
    public function canTeamLeaderEvaluate(User $teamLeader, User $employee): bool
    {
        return $teamLeader->tl_employees()
            ->where('users.id', $employee->id)
            ->wherePivot('is_active', true)
            ->exists();
    }

    /**
     * Check if month is locked for a team leader
     */
    public function isMonthLocked(User $teamLeader, Carbon $month): bool
    {
        $month = $month->copy()->startOfMonth();
        return EmployeeRecognition::where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->whereNotNull('locked_at')
            ->exists();
    }

    /**
     * Lock all recognitions for a month and team leader
     */
    public function lockMonthForTeamLeader(User $teamLeader, Carbon $month): void
    {
        $month = $month->copy()->startOfMonth();
        EmployeeRecognition::where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->whereNull('locked_at')
            ->update(['locked_at' => now()]);
    }

    /**
     * Create or update a recognition record
     */
    public function upsert(User $teamLeader, User $employee, array $scores, Carbon $month): EmployeeRecognition
    {
        $month = $month->copy()->startOfMonth();

        // Find existing recognition for the same team leader, employee, and month
        $existing = EmployeeRecognition::where('employee_id', $employee->id)
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month)
            ->first();

        $totalScore = array_sum($scores);
        $payload = [
            'employee_id'    => $employee->id,
            'team_leader_id' => $teamLeader->id,
            'month'          => $month->format('Y-m-d'),
            'behavior'       => (int)($scores['behavior'] ?? 0),
            'appreciation'   => (int)($scores['appreciation'] ?? 0),
            'leadership'     => (int)($scores['leadership'] ?? 0),
            'loyalty'        => (int)($scores['loyalty'] ?? 0),
            'dedication'     => (int)($scores['dedication'] ?? 0),
            'total_score'    => $totalScore,
        ];

        if ($existing) {
            $existing->update($payload);
            return $existing->refresh();
        }

        return EmployeeRecognition::create($payload);
    }

    /**
     * Get team members ordered by score for a specific month
     */
    public function getTeamMembersOrderedByScore(User $teamLeader, Carbon $month): Collection
    {
        $month = $month->copy()->startOfMonth();
        
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

    /**
     * Get recognitions for a specific month and team leader
     */
    public function getMonthlyRecognitions(User $teamLeader, Carbon $month, ?string $badgeCode = null): Collection
    {
        $month = $month->copy()->startOfMonth();
        $query = EmployeeRecognition::with('employee')
            ->where('team_leader_id', $teamLeader->id)
            ->forMonth($month);
            
        if ($badgeCode) {
            $badgeRepo = app(BadgeRepository::class);
            [$min, $max] = $badgeRepo->getScoreRangeForCode($badgeCode);
            $query->whereBetween('total_score', [$min, $max]);
        }
        
        return $query->orderByDesc('total_score')->get();
    }

    /**
     * Get top performers for a specific month
     */
    public function getTopPerformersByMonth(Carbon $month, ?string $badgeCode = null, ?int $teamLeaderId = null, ?int $employeeId = null): Collection
    {
        $month = $month->copy()->startOfMonth();
        $query = EmployeeRecognition::with([
                'employee.employee',
                'employee.roles',
                'employee.media',
                'teamLeader.employee',
                'teamLeader.roles',
                'teamLeader.media',
            ])
            ->forMonth($month);
            
        if ($badgeCode) {
            $badgeRepo = app(BadgeRepository::class);
            [$min, $max] = $badgeRepo->getScoreRangeForCode($badgeCode);
            $query->whereBetween('total_score', [$min, $max]);
        }
        
        if ($teamLeaderId) {
            $query->where('team_leader_id', (int)$teamLeaderId);
        }
        
        if ($employeeId) {
            $query->where('employee_id', (int)$employeeId);
        }
        
        return $query->orderByDesc('total_score')->get();
    }

    /**
     * Get employee trend for a specific year
     */
    public function getEmployeeTrendByYear(User $employee, int $year): Collection
    {
        return EmployeeRecognition::where('employee_id', $employee->id)
            ->forYear($year)
            ->orderBy('month')
            ->get();
    }

    /**
     * Get team comparison for a specific month
     */
    public function getTeamComparisonByMonth(Carbon $month): Collection
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

    /**
     * Browse recognitions with optional filters
     */
    public function browse(?int $year = null, ?int $month = null, ?int $teamLeaderId = null, ?int $employeeId = null): Collection
    {
        $query = EmployeeRecognition::with(['employee', 'teamLeader']);
        
        if ($year) {
            $query->forYear($year);
        }
        
        if ($month) {
            $monthDate = Carbon::createFromDate(now()->year, $month, 1)->startOfMonth();
            $query->forMonth($monthDate);
        }
        
        if ($teamLeaderId) {
            $query->where('team_leader_id', (int)$teamLeaderId);
        }
        
        if ($employeeId) {
            $query->where('employee_id', (int)$employeeId);
        }
        
        return $query->orderByDesc('month')
            ->orderByDesc('total_score')
            ->get();
    }
}