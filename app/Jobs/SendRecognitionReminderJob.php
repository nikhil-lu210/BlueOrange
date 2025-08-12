<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User\Employee\EmployeeRecognition;
use App\Notifications\Administration\Recognition\TeamLeaderRecognitionReminder;
use App\Services\Administration\EmployeeRecognition\EmployeeRecognitionService;
use App\Mail\Administration\EmployeeRecognition\TeamLeaderRecognitionReminderMail;

class SendRecognitionReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public ?string $monthDate = null)
    {
    }

    public function handle(EmployeeRecognitionService $service): void
    {
        $currentMonth = $this->monthDate ? \Carbon\Carbon::parse($this->monthDate)->startOfMonth() : now()->startOfMonth();
        if (!$service->withinRecognitionWindow()) {
            return; // only remind during the window
        }

        // Identify team leaders
        $teamLeaders = User::whereHas('tl_employees', function ($q) {
            $q->where('is_active', true);
        })->get();

        foreach ($teamLeaders as $tl) {
            $teamMemberIds = $tl->tl_employees()->wherePivot('is_active', true)->pluck('users.id');
            $recognizedIds = EmployeeRecognition::where('team_leader_id', $tl->id)
                ->whereDate('month', $currentMonth->format('Y-m-d'))
                ->pluck('employee_id');
            $missingIds = $teamMemberIds->diff($recognizedIds);

            if ($missingIds->isNotEmpty()) {
                $names = User::whereIn('id', $missingIds)->pluck('name')->toArray();
                $monthLabel = $currentMonth->format('F Y');
                // Database notification (in-app)
                $tl->notify(new TeamLeaderRecognitionReminder($names, $monthLabel));
                // Email using your markdown layout
                $mailData = [
                    'missing' => $names,
                    'month_label' => $monthLabel,
                ];
                Mail::to($tl->employee->official_email)->queue(new TeamLeaderRecognitionReminderMail($mailData, $tl));
            }
        }
    }
}
