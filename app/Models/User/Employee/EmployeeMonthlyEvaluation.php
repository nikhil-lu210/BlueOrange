<?php

namespace App\Models\User\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class EmployeeMonthlyEvaluation extends Model
{
    protected $fillable = [
        'employee_id',
        'team_leader_id',
        'month',
        'behavior',
        'appreciation',
        'leadership',
        'loyalty',
        'dedication',
        'total_score',
        'rank',
        'locked_at',
    ];

    protected $casts = [
        'month' => 'date:Y-m-d',
        'locked_at' => 'datetime',
    ];

    // Relations
    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function teamLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'team_leader_id');
    }

    // Aliases to align with with() calls used in services
    public function teamLeaderRelation(): BelongsTo
    {
        return $this->teamLeader();
    }

    // Accessors
    public function totalScore(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $value ?? ((int)($attributes['behavior'] + $attributes['appreciation'] + $attributes['leadership'] + $attributes['loyalty'] + $attributes['dedication'])),
            set: function ($value, $attributes) {
                if ($value === null) {
                    $value = (int) (($attributes['behavior'] ?? 0)
                        + ($attributes['appreciation'] ?? 0)
                        + ($attributes['leadership'] ?? 0)
                        + ($attributes['loyalty'] ?? 0)
                        + ($attributes['dedication'] ?? 0));
                }
                return ['total_score' => $value];
            }
        );
    }

    public function isLocked(): bool
    {
        return !is_null($this->locked_at);
    }

    // Scopes
    public function scopeForMonth($query, $month): void
    {
        $monthDate = $month instanceof Carbon ? $month->copy()->startOfMonth() : Carbon::parse($month)->startOfMonth();
        $query->whereDate('month', $monthDate->format('Y-m-d'));
    }

    public function scopeForQuarter($query, int $year, int $quarter): void
    {
        $startMonth = ($quarter - 1) * 3 + 1;
        $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
        $end = (clone $start)->addMonths(3)->subDay()->endOfDay();
        $query->whereBetween('month', [$start, $end]);
    }

    public function scopeForYear($query, int $year): void
    {
        $start = Carbon::create($year, 1, 1)->startOfDay();
        $end = Carbon::create($year, 12, 31)->endOfDay();
        $query->whereBetween('month', [$start, $end]);
    }

    // Helpers
    public function getRankForScore(): ?string
    {
        $score = $this->total_score;
        if ($score >= 90) return 'first';
        if ($score >= 70) return 'second';
        if ($score >= 60) return 'third';
        return null;
    }
}
