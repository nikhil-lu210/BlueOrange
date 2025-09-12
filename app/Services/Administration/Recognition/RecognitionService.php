<?php

namespace App\Services\Administration\Recognition;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\Recognition\Recognition;
use Illuminate\Support\Facades\Notification;
use App\Mail\Administration\Recognition\RecognitionCongratulationMail;
use App\Mail\Administration\Recognition\RecognitionReminderMail;
use App\Notifications\Administration\Recognition\RecognitionCreatedNotification;
use App\Notifications\Administration\Recognition\RecognitionReminderNotification;

class RecognitionService
{
    /**
     * Check if the team leader needs a recognition reminder.
     */
    public function needsReminder(User $teamLeader, ?int $days = null): bool
    {
        $lastRecognition = $teamLeader->created_recognitions()
            ->latest('created_at')
            ->first();

        // If not passed, fallback to config value
        $days = $days ?? config('recognition.reminder_days', 15);

        if (!$lastRecognition) {
            return true;
        }

        return $lastRecognition->created_at->diffInDays(now()) >= $days;
    }

    /**
     * Send reminder notification and email to team leader.
     */
    public function sendReminder(User $teamLeader)
    {
        Notification::send($teamLeader, new RecognitionReminderNotification());
        Mail::to($teamLeader->employee->official_email)->send(new RecognitionReminderMail($teamLeader));
    }

    /**
     * Send congratulation email to recognized employee.
     */
    public function sendCongratulation(User $employee, Recognition $recognition)
    {
        $employee->notify(new RecognitionCreatedNotification($recognition));
        Mail::to($employee->employee->official_email)->send(new RecognitionCongratulationMail($recognition));
    }

    // Notify all users 
    public function allUserNotify($employee, Recognition $recognition){
        $users = User::where('status', 'Active')->where('id','!=',$employee->id)->get();
        foreach ($users as $key => $user) {
            $user->notify(new RecognitionCreatedNotification($recognition));
        }
    }

    /**
     * Get recognition statistics for a user.
     */
    public function getRecognitionStats(User $user): array
    {
        $receivedRecognitions = $user->received_recognitions();
        $givenRecognitions = $user->created_recognitions();

        return [
            'total_received' => $receivedRecognitions->count(),
            'total_given' => $givenRecognitions->count(),
            'average_score_received' => $receivedRecognitions->avg('total_mark') ?? 0,
            'average_score_given' => $givenRecognitions->avg('total_mark') ?? 0,
            'highest_score_received' => $receivedRecognitions->max('total_mark') ?? 0,
            'total_score_received' => $receivedRecognitions->sum('total_mark'),
            'this_month_received' => $receivedRecognitions->thisMonth()->count(),
            'this_year_received' => $receivedRecognitions->thisYear()->count(),
            'category_breakdown' => $receivedRecognitions->selectRaw('category, COUNT(*) as count, AVG(total_mark) as avg_score')
                ->groupBy('category')
                ->get()
                ->keyBy('category'),
        ];
    }

    /**
     * Get top performers by recognition score.
     */
    public function getTopPerformers(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return User::withCount(['received_recognitions as recognition_count'])
            ->withSum('received_recognitions as total_score', 'total_mark')
            ->withAvg('received_recognitions as avg_score', 'total_mark')
            ->whereHas('received_recognitions')
            ->orderBy('total_score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recognition trends over time.
     */
    public function getRecognitionTrends(int $months = 6): array
    {
        $trends = [];
        $startDate = now()->subMonths($months);

        for ($i = 0; $i < $months; $i++) {
            $month = $startDate->copy()->addMonths($i);
            $trends[] = [
                'month' => $month->format('M Y'),
                'count' => Recognition::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
                'avg_score' => Recognition::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->avg('total_mark') ?? 0,
            ];
        }

        return $trends;
    }

    /**
     * Get department recognition statistics.
     */
    public function getDepartmentRecognitionStats(): array
    {
        return User::with(['received_recognitions'])
            ->whereHas('received_recognitions')
            ->get()
            ->groupBy('employee.department_id')
            ->map(function ($users, $departmentId) {
                $recognitions = $users->flatMap->received_recognitions;
                return [
                    'department_id' => $departmentId,
                    'department_name' => $users->first()->employee->department->name ?? 'Unknown',
                    'total_recognitions' => $recognitions->count(),
                    'total_score' => $recognitions->sum('total_mark'),
                    'average_score' => $recognitions->avg('total_mark') ?? 0,
                    'employee_count' => $users->count(),
                ];
            })
            ->values()
            ->toArray();
    }

    /**
     * Get monthly recognition report.
     */
    public function getMonthlyRecognitionReport(string $month = null): array
    {
        $targetMonth = $month ? \Carbon\Carbon::parse($month) : now();
        
        $recognitions = Recognition::with(['user', 'recognizer'])
            ->whereYear('created_at', $targetMonth->year)
            ->whereMonth('created_at', $targetMonth->month)
            ->get();

        return [
            'month' => $targetMonth->format('F Y'),
            'total_recognitions' => $recognitions->count(),
            'total_score' => $recognitions->sum('total_mark'),
            'average_score' => $recognitions->avg('total_mark') ?? 0,
            'category_breakdown' => $recognitions->groupBy('category')->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'total_score' => $group->sum('total_mark'),
                    'avg_score' => $group->avg('total_mark'),
                ];
            }),
            'top_recognizers' => $recognitions->groupBy('recognizer_id')->map(function ($group, $recognizerId) {
                $recognizer = $group->first()->recognizer;
                return [
                    'recognizer' => $recognizer,
                    'count' => $group->count(),
                    'total_score_given' => $group->sum('total_mark'),
                ];
            })->sortByDesc('count')->take(5)->values(),
            'top_recipients' => $recognitions->groupBy('user_id')->map(function ($group, $userId) {
                $user = $group->first()->user;
                return [
                    'user' => $user,
                    'count' => $group->count(),
                    'total_score_received' => $group->sum('total_mark'),
                ];
            })->sortByDesc('total_score_received')->take(5)->values(),
        ];
    }

    /**
     * Get recognition leaderboard by category.
     */
    public function getCategoryLeaderboard(string $category, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return User::withSum(['received_recognitions as category_score' => function ($query) use ($category) {
                $query->where('category', $category);
            }], 'total_mark')
            ->withCount(['received_recognitions as category_count' => function ($query) use ($category) {
                $query->where('category', $category);
            }])
            ->whereHas('received_recognitions', function ($query) use ($category) {
                $query->where('category', $category);
            })
            ->orderBy('category_score', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recognition analytics for dashboard.
     */
    public function getDashboardAnalytics(User $user): array
    {
        return [
            'my_stats' => $this->getRecognitionStats($user),
            'recent_recognitions' => Recognition::with(['user', 'recognizer'])
                ->where('user_id', $user->id)
                ->latest()
                ->limit(5)
                ->get(),
            'top_performers' => $this->getTopPerformers(5),
            'monthly_trends' => $this->getRecognitionTrends(6),
        ];
    }
}
