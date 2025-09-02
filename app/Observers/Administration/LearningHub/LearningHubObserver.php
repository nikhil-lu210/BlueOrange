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
        try {
            $notifiableUsers = $this->getNotifiableUsers($learningHub);

            foreach ($notifiableUsers as $notifiableUser) {
                try {
                    // Send Notification to System
                    $notifiableUser->notify((new LearningTopicCreateNotification($learningHub, $learningHub->creator))->afterCommit());

                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    if ($notifiableUser->employee && $notifiableUser->employee->official_email) {
                        Mail::to($notifiableUser->employee->official_email)->queue((new NewLearningTopicMail($learningHub, $notifiableUser))->afterCommit());
                    }
                } catch (\Exception $e) {
                    \Log::error('LearningHub Observer: Error sending notification/email to user ' . $notifiableUser->id . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('LearningHub Observer: Error in sendNotifications: ' . $e->getMessage());
        }
    }

    /**
     * Get users who should be notified
     */
    private function getNotifiableUsers(LearningHub $learningHub)
    {
        // Check if recipients is null or empty array
        if (is_null($learningHub->recipients) || empty($learningHub->recipients)) {
            // If no recipients specified, notify all users except the creator
            return User::with(['employee'])
                ->select(['id', 'name', 'email'])
                ->where('id', '!=', $learningHub->creator_id)
                ->get();
        } else {
            // Handle both array of IDs and array of User objects
            $recipientIds = collect($learningHub->recipients)
                ->map(function($item) {
                    // If it's a User object, get the ID
                    if (is_object($item) && isset($item->id)) {
                        return $item->id;
                    }
                    // If it's already an ID, use it directly
                    return $item;
                })
                ->flatten()
                ->filter(fn($value) => $value !== 'selectAllValues' && is_numeric($value))
                ->map(fn($id) => (int) $id)
                ->filter(fn($id) => $id > 0)
                ->unique()
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
