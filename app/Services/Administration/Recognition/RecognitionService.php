<?php

namespace App\Services\Administration\Recognition;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\Recognition\Recognition;
use Illuminate\Support\Facades\Notification;
use App\Mail\Administration\Recognition\RecognitionCongratulationMail;
use App\Mail\Administration\Recognition\RecognitionReminderMail;
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
        Mail::to($employee->employee->official_email)->send(new RecognitionCongratulationMail($recognition));
    }
}
