<?php

namespace App\Console\Commands\Administration\Task;

use App\Mail\Administration\Task\TaskOverdueMail;
use App\Mail\Administration\Task\TaskDueSoonMail;
use App\Mail\Administration\Task\AssigneesCompletedTaskMail;
use App\Mail\Administration\Task\TaskOverdueTeamLeaderMail;
use App\Models\Task\Task;
use App\Models\User;
use App\Notifications\Administration\Task\TaskOverdueNotification;
use App\Notifications\Administration\Task\TaskDueSoonNotification;
use App\Notifications\Administration\Task\TaskAllAssigneesCompletedNotification;
use App\Notifications\Administration\Task\TaskOverdueTeamLeaderNotification;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendTaskNotifications extends Command
{
	protected $signature = 'send:task-notifications';

	protected $description = 'Send Task notifications and emails for completion, due soon, and overdue conditions';

	public function handle()
	{
		$today = Carbon::today();
		$dueSoonDays = [5, 3, 1];

		$tasks = Task::with(['creator', 'creator.employee', 'users', 'users.employee'])
			->whereNull('deleted_at')
			->whereIn('status', ['Running', 'Active'])
			->get();

		foreach ($tasks as $task) {
			$assignees = $task->users;
			if ($assignees->isEmpty()) {
				continue;
			}

			$assigneeProgress = $assignees->pluck('pivot.progress');
			$allCompleted = $assigneeProgress->every(fn ($p) => (int)$p === 100);

			// 1) All assignees completed => notify creator once
			if ($allCompleted) {
				$this->notifyOnce(
					collect([$task->creator])->filter(),
					new TaskAllAssigneesCompletedNotification($task),
					function ($user) use ($task) {
						if ($user && $user->employee && $user->employee->official_email) {
							Mail::to($user->employee->official_email)->queue(new AssigneesCompletedTaskMail($task, $user));
						}
					},
					['kind' => 'all_assignees_completed', 'task_id' => $task->id]
				);
			}

			// Prepare due/overdue windows
			$deadline = $task->deadline ? Carbon::parse($task->deadline)->startOfDay() : null;
			$hasIncompleteAssignees = $assigneeProgress->contains(fn ($p) => (int)$p < 100);

			if ($deadline && $hasIncompleteAssignees) {
				$daysDiff = $today->diffInDays($deadline, false); // negative if overdue

				// 2) Due Soon (5/3/1 days exactly)
				if (in_array($daysDiff, $dueSoonDays, true)) {
					$notifiables = $this->dueTargetUsers($task);
					$this->notifyOnce(
						$notifiables,
						new TaskDueSoonNotification($task, $daysDiff),
						function ($user) use ($task, $daysDiff) {
							if ($user && $user->employee && $user->employee->official_email) {
								Mail::to($user->employee->official_email)->queue(new TaskDueSoonMail($task, $user, $daysDiff));
							}
						},
						['kind' => 'due_soon', 'task_id' => $task->id, 'days_left' => $daysDiff]
					);
				}

				// 3) Overdue (deadline < today)
				if ($daysDiff < 0) {
					// Notify creator once
					$creator = collect([$task->creator])->filter();
					$this->notifyOnce(
						$creator,
						new TaskOverdueNotification($task),
						function ($user) use ($task) {
							if ($user && $user->employee && $user->employee->official_email) {
								Mail::to($user->employee->official_email)->queue(new TaskOverdueMail($task, $user));
							}
						},
						['kind' => 'overdue', 'task_id' => $task->id]
					);

					// Notify each incomplete assignee's active team leader
					$incompleteAssignees = $task->users->filter(fn ($u) => (int)$u->pivot->progress < 100);
					foreach ($incompleteAssignees as $assignee) {
						$teamLeader = $assignee->active_team_leader;
						if (!$teamLeader) {
							continue;
						}

						$this->notifyOnce(
							collect([$teamLeader]),
							new TaskOverdueTeamLeaderNotification($task, $assignee),
							function ($user) use ($task, $assignee) {
								if ($user && $user->employee && $user->employee->official_email) {
									Mail::to($user->employee->official_email)->queue(new TaskOverdueTeamLeaderMail($task, $user, $assignee));
								}
							},
							['kind' => 'overdue_tl', 'task_id' => $task->id, 'assignee_id' => $assignee->id]
						);
					}
				}
			}
		}

		$this->info('Task notifications processed.');
	}

	private function dueTargetUsers(Task $task): Collection
	{
		$incompleteAssignees = $task->users->filter(fn ($u) => (int)$u->pivot->progress < 100);
		$teamLeaders = $incompleteAssignees->map(function ($user) {
			return $user->active_team_leader ?: null;
		})->filter();
		$creator = collect([$task->creator])->filter();
		return $incompleteAssignees->merge($teamLeaders)->merge($creator)->unique('id');
	}

	private function notifyOnce(Collection $users, $notification, callable $mailCallback, array $keyData): void
	{
		$kind = $keyData['kind'] ?? null;
		$taskId = $keyData['task_id'] ?? null;
		$daysLeft = $keyData['days_left'] ?? null;

		foreach ($users as $user) {
			if (!$user) continue;
			$already = DB::table('notifications')
				->where('notifiable_id', $user->id)
				->where('notifiable_type', '=', User::class)
				->where('type', get_class($notification))
				->whereJsonContains('data->task_id', $taskId)
				->when($kind, fn ($q) => $q->whereJsonContains('data->kind', $kind))
				->when($daysLeft !== null, fn ($q) => $q->whereJsonContains('data->days_left', $daysLeft))
				->exists();

			if ($already) {
				continue;
			}

			$user->notify($notification);
			$mailCallback($user);
		}
	}
}


