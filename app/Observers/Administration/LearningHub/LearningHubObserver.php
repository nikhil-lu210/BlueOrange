<?php

namespace App\Observers\Administration\LearningHub;

use App\Models\User;
use App\Models\LearningHub\LearningHub;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\LearningHub\NewLearningTopicMail;
use App\Notifications\Administration\LearningHub\LearningTopicCreateNotification;

class LearningHubObserver
{
    /**
     * Handle the LearningHub "created" event.
     */
    public function created(LearningHub $learningHub): void
    {
        $this->sendNotifications($learningHub);
    }

    /**
     * Handle the LearningHub "updated" event.
     */
    public function updated(LearningHub $learningHub): void
    {
        //
    }

    /**
     * Handle the LearningHub "deleted" event.
     */
    public function deleted(LearningHub $learningHub): void
    {
        //
    }

    /**
     * Handle the LearningHub "restored" event.
     */
    public function restored(LearningHub $learningHub): void
    {
        //
    }

    /**
     * Handle the LearningHub "force deleted" event.
     */
    public function forceDeleted(LearningHub $learningHub): void
    {
        //
    }

    /**
     * Send notifications and emails for learning topic creation
     */
    private function sendNotifications(LearningHub $learningHub): void
    {
        $notifiableUsers = $this->getNotifiableUsers($learningHub);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new LearningTopicCreateNotification($learningHub, auth()->user()));

            // Send Mail to the notifiableUser's email & Dispatch the email to the queue
            if ($notifiableUser->employee && $notifiableUser->employee->official_email) {
                Mail::to($notifiableUser->employee->official_email)->queue(new NewLearningTopicMail($learningHub, $notifiableUser));
            }
        }
    }

    /**
     * Get users who should be notified
     */
    private function getNotifiableUsers(LearningHub $learningHub)
    {
        if (is_null($learningHub->recipients)) {
            // If recipients is null, notify all users except the creator
            return User::with(['employee'])
                ->select(['id', 'name', 'email'])
                ->where('id', '!=', $learningHub->creator_id)
                ->get();
        } else {
            // Ensure recipients is an array and flatten it, filtering out non-numeric values
            $recipientIds = collect($learningHub->recipients)
                ->flatten()
                ->filter(fn($value) => $value !== 'selectAllValues' && is_numeric($value))
                ->values()
                ->all();

            if (empty($recipientIds)) {
                return collect();
            }

            // Notify only the specified recipients
            return User::with(['employee'])
                ->select(['id', 'name', 'email'])
                ->whereIn('id', $recipientIds)
                ->get();
        }
    }
}
