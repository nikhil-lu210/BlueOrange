<?php

namespace App\Http\Controllers\Administration\EmployeeRecognition;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\Administration\EmployeeRecognition\EmployeeRecognitionService;
use Illuminate\Support\Facades\Gate;

class EmployeeRecognitionController extends Controller
{
    public function __construct(protected EmployeeRecognitionService $service)
    {
    }

    // Team leader panel: list team members and enter scores for a month
    public function index(Request $request)
    {
        $user = auth()->user();
        $month = $request->input('month') ? Carbon::parse($request->input('month'))->startOfMonth() : now()->startOfMonth();

        // Load team members ordered by current month's total score (highest first)
        $teamMembers = $this->service->orderTeamMembersByScore($user, $month);

        // Load existing recognitions for the month to prefill
        $recognitions = $user->given_recognitions()->whereDate('month', $month->format('Y-m-d'))->get()->keyBy('employee_id');

        // Move window and badge logic out of Blade
        $isWindowOpen = $this->service->withinRecognitionWindow();
        $badgeMap = $recognitions->mapWithKeys(function ($e) {
            $score = (int) $e->total_score;
            $code = $this->service->badgeCodeForScore($score);
            $label = $this->service->badgeLabelForScore($score);
            $emoji = $this->service->badgeEmojiForScore($score);
            $classMap = [
                'platinum' => 'bg-success',
                'gold' => 'bg-warning',
                'silver' => 'bg-primary',
                'bronze' => 'bg-danger',
                'rising' => 'bg-dark',
                'learner' => 'bg-label-dark',
            ];
            $class = $classMap[$code] ?? 'bg-secondary';
            return [$e->employee_id => compact('code', 'label', 'emoji', 'class')];
        });

        return view('administration.employee_recognition.index', compact('user', 'month', 'teamMembers', 'recognitions', 'isWindowOpen', 'badgeMap'));
    }

    // Store or update recognitions for multiple employees in the selected month
    public function store(Request $request)
    {
        $user = auth()->user();
        $validated = $request->validate([
            'month' => 'required|date',
            'scores' => 'required|array',
            'scores.*.behavior' => 'required|integer|min:0|max:20',
            'scores.*.appreciation' => 'required|integer|min:0|max:20',
            'scores.*.leadership' => 'required|integer|min:0|max:20',
            'scores.*.loyalty' => 'required|integer|min:0|max:20',
            'scores.*.dedication' => 'required|integer|min:0|max:20',
        ]);

        $month = Carbon::parse($validated['month'])->startOfMonth();

        foreach ($validated['scores'] as $employeeId => $scores) {
            $employee = User::findOrFail($employeeId);
            $this->service->upsertEmployeeRecognition($user, $employee, $scores, $month);
        }

        // After submission, lock the month for this TL
        $this->service->lockMonthForTeamLeader($user, $month);

        toast('Monthly recognitions saved and locked.', 'success');
        return redirect()->route('administration.employee_recognition.index', ['month' => $month->format('Y-m-d')]);
    }

    // Leaderboards and analytics for TL
    public function leaderboard(Request $request)
    {
        $user = auth()->user();
        $month = $request->input('month') ? Carbon::parse($request->input('month'))->startOfMonth() : now()->startOfMonth();
        $badge = $request->input('badge');
        $leaderboard = $this->service->monthlyLeaderboard($user, $month, $badge);

        // Provide badge options and per-row badge info to the view
        $badgeOptions = [
            'platinum' => '🌟 Platinum Performer',
            'gold'     => '🥇 Gold Achiever',
            'silver'   => '🥈 Silver Contributor',
            'bronze'   => '🥉 Bronze Supporter',
            'rising'   => '💪 Rising Star',
            'learner'  => '🌱 Learner',
        ];
        $rowBadges = $leaderboard->mapWithKeys(function ($row) {
            $score = (int) $row->total_score;
            $code = $this->service->badgeCodeForScore($score);
            $label = $this->service->badgeLabelForScore($score);
            $emoji = $this->service->badgeEmojiForScore($score);
            $classMap = [
                'platinum' => 'bg-dark',
                'gold' => 'bg-warning',
                'silver' => 'bg-secondary',
                'bronze' => 'bg-brown',
                'rising' => 'bg-info',
                'learner' => 'bg-light text-dark',
            ];
            $class = $classMap[$code] ?? 'bg-secondary';
            return [$row->id => compact('code', 'label', 'emoji', 'class')];
        });

        return view('administration.employee_recognition.leaderboard', compact('user', 'month', 'leaderboard', 'badge', 'badgeOptions', 'rowBadges'));
    }

    // Employee self-view: see own monthly scores and trends
    public function myScores(Request $request)
    {
        $user = auth()->user();
        $year = (int)($request->input('year') ?: now()->year);
        $recognitions = $user->given_recognitions()->forYear($year)->orderBy('month')->get();
        return view('administration.employee_recognition.my', compact('user', 'year', 'recognitions'));
    }

    // Admin reports: top performers and team comparison for a month
    public function reports(Request $request)
    {
        if (!Gate::allows('User Read') && !Gate::allows('Employee Recognition Everything')) {
            abort(403);
        }
        $month = $request->input('month') ? Carbon::parse($request->input('month'))->startOfMonth() : now()->startOfMonth();
        $badge = $request->input('badge');
        $topPerformers = $this->service->adminTopPerformersByMonth($month, $badge);
        $teamComparison = $this->service->compareTeamsByMonth($month);
        
        $topBadges = $topPerformers->mapWithKeys(function ($row) {
            $score = (int) $row->total_score;
            $code = $this->service->badgeCodeForScore($score);
            $label = $this->service->badgeLabelForScore($score);
            $emoji = $this->service->badgeEmojiForScore($score);
            $classMap = [
                'platinum' => 'bg-dark',
                'gold' => 'bg-warning',
                'silver' => 'bg-secondary',
                'bronze' => 'bg-brown',
                'rising' => 'bg-info',
                'learner' => 'bg-light text-dark',
            ];
            $class = $classMap[$code] ?? 'bg-secondary';
            return [$row->id => compact('code', 'label', 'emoji', 'class')];
        });

        return view('administration.employee_recognition.reports', compact('month', 'topPerformers', 'teamComparison', 'badge', 'topBadges'));
    }

    // Admin: employee trend view
    public function employeeTrend(Request $request, User $user)
    {
        if (!Gate::allows('User Read') && auth()->id() !== $user->id) {
            abort(403);
        }
        $year = (int)($request->input('year') ?: now()->year);
        $trend = $this->service->employeeTrend($user, $year);
        return view('administration.employee_recognition.employee_trend', compact('user', 'year', 'trend'));
    }
}
