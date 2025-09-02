<?php

namespace App\Http\Controllers\Administration\LearningHub;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\LearningHub\LearningHub;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Notifications\Administration\LearningHub\LearningHubCommentNotification;

class LearningHubCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, LearningHub $learning_topic)
    {
        try {
            DB::transaction(function () use ($request, $learning_topic) {
                // Create the comment using polymorphic relationship
                $learning_topic->comments()->create([
                    'comment' => $request->comment
                ]);

                // Send notifications to relevant users
                $this->notifyToLearningHubComment($learning_topic, auth()->user());
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
     * @param LearningHub $learning_topic The learning topic that received a comment
     * @param User $commenter The user who made the comment
     * @return void
     */
    private function notifyToLearningHubComment(LearningHub $learning_topic, User $commenter): void
    {
        $notifiableUserIds = [];

        // 1. Add creator if not the commenter
        if ($learning_topic->creator_id != $commenter->id) {
            $notifiableUserIds[] = $learning_topic->creator_id;
        }

        // 2. Add recipients or users interacting with creator (if recipients is null)
        if (is_array($learning_topic->recipients) && !empty($learning_topic->recipients)) {
            // Filter out the commenter
            $recipientIds = collect($learning_topic->recipients)
                ->filter(fn ($id) => $id != $commenter->id)
                ->toArray();

            $notifiableUserIds = array_merge($notifiableUserIds, $recipientIds);
        } else {
            // If no specific recipients, notify users who interact with the creator
            $creator = User::find($learning_topic->creator_id);
            $interactingUsers = $creator->user_interactions
                ->where('id', '!=', $commenter->id)
                ->where('id', '!=', $learning_topic->creator_id)
                ->pluck('id')
                ->toArray();

            $notifiableUserIds = array_merge($notifiableUserIds, $interactingUsers);
        }

        // 3. Final unique list
        $notifiableUserIds = array_unique($notifiableUserIds);

        // 4. Send notifications
        User::whereIn('id', $notifiableUserIds)->get()
            ->each(function ($user) use ($learning_topic, $commenter) {
                $user->notify(new LearningHubCommentNotification($learning_topic, $commenter));
            });
    }

    /**
     * Handle exceptions consistently
     */
    private function handleException(Exception $e)
    {
        return redirect()->back()
            ->withInput()
            ->with('error', 'An error occurred while submitting the comment: ' . $e->getMessage());
    }
}
