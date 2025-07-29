<?php

namespace App\Repositories\Administration\Task;

use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class TaskRepository
{
    /**
     * Get roles with users who have Task Create permission
     *
     * @return Collection
     */
    public function getRolesWithTaskCreators(): Collection
    {
        return Role::select(['id', 'name'])
            ->with([
                'users' => function ($user) {
                    $user->with(['employee'])->permission('Task Create')
                        ->select(['id', 'name'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active');
                }
            ])
            ->whereHas('users', function ($user) {
                $user->permission('Task Create');
            })
            ->distinct()
            ->get();
    }

    /**
     * Get users who can be assigned to tasks
     *
     * @return Collection
     */
    public function getAssignableUsers(): Collection
    {
        return User::with(['employee'])->permission('Task Read')
            ->select(['id', 'name'])
            ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
            ->whereStatus('Active')
            ->get();
    }

    /**
     * Get tasks query with filters
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getTasksQuery(Request $request)
    {
        $query = Task::with([
            'creator:id,userid',
            'creator.employee',
            'users',
            'users.media',
            'users.employee'
        ])->orderByDesc('created_at');

        $this->applyFilters($query, $request);

        return $query;
    }

    /**
     * Get my tasks query with filters
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getMyTasksQuery(Request $request)
    {
        $query = Task::with([
            'creator:id,userid',
            'creator.employee',
            'users',
            'users.media',
            'users.employee'
        ])
        ->where(function ($taskQuery) {
            $taskQuery->whereHas('users', function ($userQuery) {
                $userQuery->where('user_id', auth()->id());
            })->orWhere('creator_id', auth()->id());
        })
        ->orderByDesc('created_at');

        $this->applyFilters($query, $request);

        return $query;
    }

    /**
     * Get detailed task by ID and taskid
     *
     * @param Task $task
     * @param string $taskid
     * @return Task
     */
    public function getDetailedTask(Task $task, string $taskid)
    {
        return Task::with([
            'creator.employee',
            'creator.media',
            'parent_task',
            'sub_tasks' => function ($subTask) {
                $subTask->orderByDesc('created_at');
            },
            'users.employee',
            'users.media',
            'files',
            'comments' => function ($comment) {
                $comment->with([
                    'files',
                    'commenter.roles',
                    'commenter.employee',
                    'commenter.media'
                ])->orderByDesc('created_at')->get();
            },
            'histories' => function ($history) {
                $history->whereStatus('Completed')->orderByDesc('ends_at')->get();
            }
        ])
        ->whereId($task->id)
        ->whereTaskid($taskid)
        ->firstOrFail();
    }

    /**
     * Get roles with users for task assignment
     *
     * @return Collection
     */
    public function getRolesWithUsers(): Collection
    {
        return Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                    ->whereStatus('Active')
                    ->orderBy('name', 'asc');
            }
        ])->get();
    }

    /**
     * Get roles with users not assigned to a specific task
     *
     * @param Task $task
     * @return Collection
     */
    public function getRolesWithUnassignedUsers(Task $task): Collection
    {
        return Role::with([
            'users' => function($query) use ($task) {
                $query->whereDoesntHave('tasks', function($taskQuery) use ($task) {
                    $taskQuery->where('task_id', $task->id);
                })
                ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                ->whereStatus('Active');
            }
        ])->get();
    }

    /**
     * Apply common filters to task queries
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Request $request
     * @return void
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->has('creator_id') && !is_null($request->creator_id)) {
            $query->where('creator_id', $request->creator_id);
        }

        if ($request->has('user_id') && !is_null($request->user_id)) {
            $query->whereHas('users', function ($q) use ($request) {
                $q->where('users.id', $request->user_id);
            });
        }

        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        }
    }

    /**
     * Get users by IDs with employee relationship
     *
     * @param array $userIds
     * @return Collection
     */
    public function getUsersWithEmployeeByIds(array $userIds): Collection
    {
        return User::with(['employee'])
            ->select(['id', 'name', 'email'])
            ->whereIn('id', $userIds)
            ->get();
    }
}
