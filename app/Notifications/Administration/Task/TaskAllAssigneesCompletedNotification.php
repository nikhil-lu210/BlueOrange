<?php

namespace App\Notifications\Administration\Task;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

class TaskAllAssigneesCompletedNotification extends Notification
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
			'icon' => 'check-circle-2',
			'title' => 'All Assignees Completed',
			'message' => 'Your assignees marked this task as 100% complete. Please review and mark status as Completed if confirmed.',
			'task_id' => $this->task->id,
			'kind' => 'all_assignees_completed',
		];
	}
}


