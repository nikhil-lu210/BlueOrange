<?php

namespace App\Notifications\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\URL;
use Illuminate\Notifications\Notification;

class TaskUpdateNotification extends Notification
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
            'title'   => 'Task Has Been Updated',
            'message'     => 'Task ('. $this->task->title .') has been updated by '. $this->authUser->alias_name,
        ];
    }
}
