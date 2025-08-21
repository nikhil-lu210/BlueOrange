<?php

namespace App\Notifications\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TaskDueSoonNotification extends Notification
{
	use Queueable;

	protected $task;
	protected $daysLeft;

	public function __construct($task, int $daysLeft)
	{
		$this->task = $task;
		$this->daysLeft = $daysLeft;
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
			'icon' => 'alarm-clock',
			'title' => 'Task Due Soon',
			'message' => 'Task is due in '. $this->daysLeft .' days. Please update progress.',
			'task_id' => $this->task->id,
			'days_left' => $this->daysLeft,
			'kind' => 'due_soon',
		];
	}
}


