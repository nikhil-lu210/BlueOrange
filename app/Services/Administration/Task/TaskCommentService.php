<?php

namespace App\Services\Administration\Task;

use App\Models\Task\Task;
use App\Models\User;
use App\Models\Comment\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Task\NewCommentOnTaskMail;
use App\Notifications\Administration\Task\TaskCommentNotification;
use Exception;

class TaskCommentService
{
    /**
     * Store a new comment for a task
     *
     * @param Task $task
     * @param array $data
     * @return Comment
     * @throws Exception
     */
    public function storeComment(Task $task, array $data): Comment
    {
        $comment = null;

        DB::transaction(function () use ($task, $data, &$comment) {
            // Create the comment
            $comment = $task->comments()->create([
                'comment' => $data['comment']
            ]);

            // Store Task's Comment Files
            if (isset($data['files']) && !empty($data['files'])) {
                $this->storeCommentFiles($comment, $data['files'], $task);
            }

            // Send notifications to relevant users
            $this->notifyTaskComment($task, auth()->user());
        });

        if (!$comment) {
            throw new Exception('Failed to create comment');
        }

        return $comment;
    }

    /**
     * Store comment files
     *
     * @param Comment $comment
     * @param array $files
     * @param Task $task
     * @return void
     */
    private function storeCommentFiles(Comment $comment, array $files, Task $task): void
    {
        foreach ($files as $file) {
            $directory = 'tasks/' . $task->taskid . '/comments/' . auth()->user()->userid;
            store_file_media($file, $comment, $directory);
        }
    }

    /**
     * Send notifications to relevant users about the new comment
     *
     * @param Task $task
     * @param User $commenter
     * @return void
     */
    private function notifyTaskComment(Task $task, User $commenter): void
    {
        // Get notifiable user IDs
        $notifiableUserIds = $this->getNotifiableUserIds($task, $commenter);

        // Get notifiable users
        $notifiableUsers = $this->getNotifiableUsers($notifiableUserIds);

        // Send notifications and emails
        $this->sendNotificationsAndEmails($notifiableUsers, $task, $commenter);
    }

    /**
     * Get the list of user IDs that should be notified about the comment
     *
     * @param Task $task
     * @param User $commenter
     * @return array
     */
    private function getNotifiableUserIds(Task $task, User $commenter): array
    {
        // Retrieve the user IDs of the assigned users
        $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

        // Add the task creator's ID to the list if it's not already included
        if (!in_array($task->creator_id, $notifiableUserIds)) {
            $notifiableUserIds[] = $task->creator_id;
        }

        // Exclude the commenter's ID from the list of notifiable user IDs
        return array_diff($notifiableUserIds, [$commenter->id]);
    }

    /**
     * Get notifiable users based on user IDs
     *
     * @param array $userIds
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getNotifiableUsers(array $userIds)
    {
        return User::with(['employee'])
            ->select(['id', 'name', 'email'])
            ->whereIn('id', $userIds)
            ->get();
    }

    /**
     * Send notifications and emails to users
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param Task $task
     * @param User $commenter
     * @return void
     */
    private function sendNotificationsAndEmails($users, Task $task, User $commenter): void
    {
        foreach ($users as $user) {
            // Send Notification to System
            $user->notify(new TaskCommentNotification($task, $commenter));

            // Send Mail to the user's official email
            Mail::to($user->employee->official_email)
                ->queue(new NewCommentOnTaskMail($task, $user, $commenter));
        }
    }
}
