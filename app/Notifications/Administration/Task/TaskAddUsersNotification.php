<?php

namespace App\Notifications\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class TaskAddUsersNotification extends Notification
{
    use Queueable;

    protected $task, $authUser;

    /**
     * Create a new notification instance.
     */
    public function __construct($task, $authUser)
    {
        $this->task = $task;
        $this->authUser = $authUser;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $url = URL::route('administration.task.show', ['task' => $this->task, 'taskid' => $this->task->taskid]);
        return [
            'url'   => $url,
            'icon'   => 'brand-stackshare',
            'title'   => 'Task Assigned',
            'message'     => 'A Task ('. $this->task->title .') Has Been assigned to You by '. $this->authUser->alias_name,
        ];
    }
}
