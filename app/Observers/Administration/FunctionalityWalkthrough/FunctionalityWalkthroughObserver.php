<?php

namespace App\Observers\Administration\FunctionalityWalkthrough;

use App\Models\User;
use App\Models\FunctionalityWalkthrough\FunctionalityWalkthrough;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\FunctionalityWalkthrough\NewWalkthroughMail;
use App\Notifications\Administration\FunctionalityWalkthrough\WalkthroughCreateNotification;

class FunctionalityWalkthroughObserver
{
    /**
     * Handle the FunctionalityWalkthrough "created" event.
     */
    public function created(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        $this->sendNotifications($functionalityWalkthrough);
    }

    /**
     * Handle the FunctionalityWalkthrough "updated" event.
     */
    public function updated(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        //
    }

    /**
     * Handle the FunctionalityWalkthrough "deleted" event.
     */
    public function deleted(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        //
    }

    /**
     * Handle the FunctionalityWalkthrough "restored" event.
     */
    public function restored(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        //
    }

    /**
     * Handle the FunctionalityWalkthrough "force deleted" event.
     */
    public function forceDeleted(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        //
    }

    /**
     * Send notifications and emails for walkthrough creation
     */
    private function sendNotifications(FunctionalityWalkthrough $functionalityWalkthrough): void
    {
        try {
            $notifiableUsers = $this->getNotifiableUsers($functionalityWalkthrough);

            foreach ($notifiableUsers as $notifiableUser) {
                try {
                    // Send Notification to System
                    $notifiableUser->notify((new WalkthroughCreateNotification($functionalityWalkthrough, $functionalityWalkthrough->creator))->afterCommit());

                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    if ($notifiableUser->employee && $notifiableUser->employee->official_email) {
                        Mail::to($notifiableUser->employee->official_email)->queue((new NewWalkthroughMail($functionalityWalkthrough, $notifiableUser))->afterCommit());
                    }
                } catch (\Exception $e) {
                    \Log::error('FunctionalityWalkthrough Observer: Error sending notification/email to user ' . $notifiableUser->id . ': ' . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('FunctionalityWalkthrough Observer: Error in sendNotifications: ' . $e->getMessage());
        }
    }

    /**
     * Get users who should be notified
     */
    private function getNotifiableUsers(FunctionalityWalkthrough $functionalityWalkthrough)
    {
        // Check if assigned_roles is null or empty array
        if (is_null($functionalityWalkthrough->assigned_roles) || empty($functionalityWalkthrough->assigned_roles)) {
            // If no roles specified, notify only users who interact with the creator
            $creator = User::find($functionalityWalkthrough->creator_id);
            $interactingUsers = $creator->user_interactions
                ->where('id', '!=', $functionalityWalkthrough->creator_id)
                ->pluck('id')
                ->toArray();

            return User::with(['employee'])
                ->select(['id', 'name', 'email'])
                ->whereIn('id', $interactingUsers)
                ->get();
        } else {
            // Process specific assigned roles
            $roleIds = collect($functionalityWalkthrough->assigned_roles)
                ->map(function($item) {
                    if (is_object($item) && isset($item->id)) {
                        return $item->id;
                    }
                    return $item;
                })
                ->flatten()
                ->filter(fn($value) => $value !== 'selectAllValues' && is_numeric($value))
                ->map(fn($id) => (int) $id)
                ->filter(fn($id) => $id > 0)
                ->unique()
                ->values()
                ->all();

            return User::with(['employee'])
                ->whereHas('roles', function($query) use ($roleIds) {
                    $query->whereIn('roles.id', $roleIds);
                })
                ->where('id', '!=', $functionalityWalkthrough->creator_id)
                ->select(['id', 'name', 'email'])
                ->get();
        }
    }
}
