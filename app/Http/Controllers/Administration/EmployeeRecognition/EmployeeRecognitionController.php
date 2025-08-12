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
        $teamLeaderId = $request->input('team_leader_id');
        $employeeId = $request->input('employee_id');
        $canManageAll = Gate::allows('Recognition Everything');
        $selectedTeamLeader = $canManageAll && $teamLeaderId ? User::find($teamLeaderId) : $user;

        // Cannot filter future months for TLs; admins can view all months
        if ($month->greaterThan(now()->startOfMonth()) && !$canManageAll) {
            toast('You are not allowed to submit future months recognitions.', 'warning');
            return redirect()->route('administration.employee_recognition.index');
        }

        // Load team members ordered by current month's total score (highest first)
        $teamMembers = $this->service->orderTeamMembersByScore($selectedTeamLeader, $month);

        // Load existing recognitions for the month to prefill (for selected TL if admin)
        $recognitionsQuery = $selectedTeamLeader->given_recognitions()->whereDate('month', $month->format('Y-m-d'));
        if ($canManageAll && $employeeId) {
            $recognitionsQuery->where('employee_id', $employeeId);
        }
        $recognitions = $recognitionsQuery->get()->keyBy('employee_id');

        // Move window and badge logic out of Blade
        $isWindowOpen = $this->service->withinRecognitionWindow();
        $isLocked = !$isWindowOpen && !$canManageAll; // Read-only after window closes for TLs
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

        // Missing recognitions: employees without a record for the month (for dashboard/notification UI)
        $missingMembers = $teamMembers->filter(function ($member) use ($recognitions) {
            return !$recognitions->has($member->id);
        })->values();

        return view('administration.employee_recognition.index', compact(
            'user',
            'month',
            'teamMembers',
            'recognitions',
            'isWindowOpen',
            'badgeMap',
            'isLocked',
            'missingMembers',
            'canManageAll',
            'selectedTeamLeader',
            'teamLeaderId',
            'employeeId'
        ));
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
        $canManageAll = Gate::allows('Recognition Everything');

        // Enforce submission window for TLs
        if (!$this->service->withinRecognitionWindow() && !$canManageAll) {
            toast('Submission window is closed. Recognitions are read-only now.', 'warning');
            return redirect()->route('administration.employee_recognition.index', ['month' => $month->format('Y-m-d')]);
        }

        // Ensure TL only recognizes own team members (unless admin)
        $teamMemberIds = $user->tl_employees()->wherePivot('is_active', true)->pluck('users.id')->toArray();
        foreach (array_keys($validated['scores']) as $employeeId) {
            if (!$canManageAll && !in_array((int)$employeeId, $teamMemberIds, true)) {
                abort(403, 'You cannot recognize employees outside your team.');
            }
        }

        // Submission must include all active team members for TLs
        if (!$canManageAll) {
            $missing = array_diff($teamMemberIds, array_map('intval', array_keys($validated['scores'])));
            if (!empty($missing)) {
                toast('Submission requires all team members to be recognized for the month.', 'error');
                return redirect()->back();
            }
        }

        foreach ($validated['scores'] as $employeeId => $scores) {
            $employee = User::findOrFail($employeeId);
            $this->service->upsertEmployeeRecognition($user, $employee, $scores, $month);
        }

        // After submission, lock the month and notify (direct submission)
        $this->service->lockMonthForTeamLeader($user, $month);
        $this->service->notifyEmployeesOfRecognition($user, $month);

        toast('Monthly recognitions submitted and locked.', 'success');
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

        if ($year > now()->year) {
            toast('You cannot view future years.', 'warning');
            return redirect()->route('administration.employee_recognition.my');
        }

        $recognitions = $user->given_recognitions()->forYear($year)->orderBy('month')->get();
        return view('administration.employee_recognition.my', compact('user', 'year', 'recognitions'));
    }

    // Admin reports: top performers and team comparison for a month
    public function reports(Request $request)
    {
        if (!Gate::allows('User Read') && !Gate::allows('Recognition Everything')) {
            abort(403);
        }
        $month = $request->input('month') ? Carbon::parse($request->input('month'))->startOfMonth() : now()->startOfMonth();
        $badge = $request->input('badge');
        $teamLeaderId = $request->input('team_leader_id');
        $employeeId = $request->input('employee_id');

        $topPerformers = $this->service->adminTopPerformersByMonth($month, $badge);
        // Apply optional filters in-memory if provided
        if ($teamLeaderId) {
            $topPerformers = $topPerformers->where('team_leader_id', (int) $teamLeaderId);
        }
        if ($employeeId) {
            $topPerformers = $topPerformers->where('employee_id', (int) $employeeId);
        }

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

        return view('administration.employee_recognition.reports', compact('month', 'topPerformers', 'teamComparison', 'badge', 'topBadges', 'teamLeaderId', 'employeeId'));
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

    // Admin: browse recognitions across months with filters
    public function browse(Request $request)
    {
        if (!Gate::allows('Recognition Everything')) {
            abort(403);
        }
        $year = $request->integer('year');
        $month = $request->integer('month');
        $teamLeaderId = $request->integer('team_leader_id');
        $employeeId = $request->integer('employee_id');

        $rows = $this->service->adminBrowseRecognitions($year, $month, $teamLeaderId, $employeeId);
        return view('administration.employee_recognition.browse', compact('rows', 'year', 'month', 'teamLeaderId', 'employeeId'));
    }

    // Team leader: history of recognitions given to team, by month
    public function teamHistory(Request $request)
    {
        $user = auth()->user();
        $isAdmin = Gate::allows('Recognition Everything');
        $year = (int)($request->input('year') ?: now()->year);

        $q = \App\Models\User\Employee\EmployeeRecognition::with('employee')
            ->where('team_leader_id', $user->id)
            ->forYear($year)
            ->orderByDesc('month')
            ->orderByDesc('total_score');

        $rows = $q->get();
        return view('administration.employee_recognition.team_history', compact('user', 'year', 'rows', 'isAdmin'));
    }
}
