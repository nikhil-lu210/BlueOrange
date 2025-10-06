<?php

namespace App\Notifications\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TaskOverdueNotification extends Notification
{
	use Queueable;

	protected $task;

	public function __construct($task)
	{
		$this->task = $task;
	}

	public function via(object $notifiable): array
	{
		return ['database'];
	}

	public function toArray(object $notifiable): array
	{
		$url = URL::route('administration.task.show', ['task' => $this->task, 'taskid' => $this->task->taskid]);
		return [
			'url' => $url,
			'icon' => 'alarm-minus',
			'title' => 'Task Overdue',
			'message' => 'Task is overdue. Please take immediate action.',
			'task_id' => $this->task->id,
			'kind' => 'overdue',
		];
	}
}


