<?php

namespace App\Observers\Administration\Suggestion;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Suggestion\NewSuggestionMail;
use App\Models\Suggestion\Suggestion;
use App\Models\User;
use App\Notifications\Administration\Sugggestion\SuggestionCreateNotification;

class SuggestionObserver
{
    /**
     * Handle the Suggestion "created" event.
     */
    public function created(Suggestion $suggestion): void
    {
        $this->sendNotifications($suggestion, auth()->user());
    }

    /**
     * Handle the Suggestion "updated" event.
     */
    public function updated(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "deleted" event.
     */
    public function deleted(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "restored" event.
     */
    public function restored(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Handle the Suggestion "force deleted" event.
     */
    public function forceDeleted(Suggestion $suggestion): void
    {
        //
    }

    /**
     * Send notifications and emails for walkthrough creation
     */
    private function sendNotifications(Suggestion $suggestion, User $user): void
    {
        try {
            $notifiableUsers = $this->getNotifiableUsers($suggestion);

            foreach ($notifiableUsers as $notifiableUser) {
                try {
                    // Send Notification to System
                    $notifiableUser->notify((new SuggestionCreateNotification($suggestion, $user))->afterCommit());

                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    if ($notifiableUser->employee && $notifiableUser->employee->official_email) {
                        Mail::to($notifiableUser->employee->official_email)->queue((new NewSuggestionMail($suggestion, $notifiableUser))->afterCommit());
                    }
                } catch (\Exception $e) {
                    \Log::error('Suggestion Observer: Error sending notification/email to user ' . $notifiableUser->id . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('Suggestion Observer: Error in sendNotifications: ' . $e->getMessage());
        }
    }

    private function getNotifiableUsers(){
        $notifiableUsers = User::whereStatus('Active')->get()->filter(function ($user) {
            return $user->hasAnyPermission(['Suggestion Everything', 'Suggestion Update']);
        });

        return $notifiableUsers;
    }
}
