<?php

namespace App\Http\Controllers\Administration\Announcement;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Announcement\Announcement;
use App\Notifications\Administration\Announcement\AnnouncementCommentNotification;
use App\Http\Requests\Comment\CommentStoreRequest;

class AnnouncementCommentController extends Controller
{
    /**
     * Store Comment
     */
    public function store(CommentStoreRequest $request, Announcement $announcement)
    {
        try {
            DB::transaction(function () use ($request, $announcement) {
                // Create the comment
                $announcement->comments()->create([
                    'comment' => $request->comment
                ]);

                // Send notifications to relevant users
                $this->notifyAnnouncementComment($announcement, auth()->user());
            }, 3);

            toast('Comment Submitted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return $this->handleException($e);
        }
    }


    /**
     * Send notifications to relevant users about a new comment
     *
     * @param Announcement $announcement The announcement that received a comment
     * @param User $commenter The user who made the comment
     * @return void
     */
    private function notifyAnnouncementComment(Announcement $announcement, User $commenter): void
    {
        $notifiableUserIds = [];

        // 1. Add announcer if not the commenter
        if ($announcement->announcer_id != $commenter->id) {
            $notifiableUserIds[] = $announcement->announcer_id;
        }

        // 2. Add recipients or all active users (if recipients is null)
        if (is_array($announcement->recipients) && !empty($announcement->recipients)) {
            // Filter out the commenter
            $recipientIds = collect($announcement->recipients)
                ->filter(fn ($id) => $id != $commenter->id)
                ->toArray();

            $notifiableUserIds = array_merge($notifiableUserIds, $recipientIds);
        } else {
            $activeUsers = User::where('status', 'Active')
                ->where('id', '!=', $commenter->id)
                ->pluck('id')
                ->toArray();

            $notifiableUserIds = array_merge($notifiableUserIds, $activeUsers);
        }

        // 3. Final unique list
        $notifiableUserIds = array_unique($notifiableUserIds);

        // 4. Send notifications
        User::whereIn('id', $notifiableUserIds)->get()
            ->each(function ($user) use ($announcement, $commenter) {
                $user->notify(new AnnouncementCommentNotification($announcement, $commenter));
            });
    }
}
