<?php

namespace App\Services\Administration\Task;

use App\Models\Task\Task;
use App\Models\User;
use App\Repositories\Administration\Task\TaskRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Task\NewTaskMail;
use App\Mail\Administration\Task\UpdateTaskMail;
use App\Mail\Administration\Task\AddUsersTaskMail;
use App\Mail\Administration\Task\FileUploadForTaskMail;
use App\Mail\Administration\Task\StatusUpdateTaskMail;
use App\Notifications\Administration\Task\TaskCreateNotification;
use App\Notifications\Administration\Task\TaskUpdateNotification;
use App\Notifications\Administration\Task\TaskAddUsersNotification;
use App\Notifications\Administration\Task\TaskFileUploadNotification;
use App\Notifications\Administration\Task\TaskStatusUpdateNotification;
use Exception;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Create a new task
     * 
     * @param array $data
     * @return Task
     * @throws Exception
     */
    public function createTask(array $data): Task
    {
        $task = null;
        $taskID = null;

        DB::transaction(function () use ($data, &$task, &$taskID) {
            $task = Task::create([
                'chatting_id' => $data['chatting_id'] ?? null,
                'parent_task_id' => $data['parent_task_id'] ?? null,
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'] ?? null,
                'priority' => $data['priority']
            ]);
            $taskID = $task->taskid;

            // Assign users to the task if necessary
            if (isset($data['users']) && is_array($data['users'])) {
                $task->users()->attach($data['users']);
            }

            // Store Task Files
            if (isset($data['files']) && is_array($data['files'])) {
                $this->uploadFiles($task, $data['files']);
            }

            // Send notifications
            $this->notifyTaskCreation($task);
        });

        if (!$task) {
            throw new Exception('Failed to create task');
        }

        return $task;
    }

    /**
     * Update an existing task
     * 
     * @param Task $task
     * @param array $data
     * @return Task
     * @throws Exception
     */
    public function updateTask(Task $task, array $data): Task
    {
        DB::transaction(function () use ($task, $data) {
            $task->update([
                'title' => $data['title'],
                'description' => $data['description'],
                'deadline' => $data['deadline'] ?? null,
                'priority' => $data['priority']
            ]);

            // Send notifications
            $this->notifyTaskUpdate($task);
        });

        return $task;
    }

    /**
     * Add users to a task
     * 
     * @param Task $task
     * @param array $userIds
     * @return Task
     * @throws Exception
     */
    public function addUsersToTask(Task $task, array $userIds): Task
    {
        DB::transaction(function () use ($task, $userIds) {
            $task->users()->attach($userIds);
            
            // Send notifications
            $this->notifyTaskUserAddition($task, $userIds);
        });

        return $task;
    }

    /**
     * Remove a user from a task
     * 
     * @param Task $task
     * @param int $userId
     * @return Task
     */
    public function removeUserFromTask(Task $task, int $userId): Task
    {
        $task->users()->detach($userId);
        return $task;
    }

    /**
     * Upload files to a task
     * 
     * @param Task $task
     * @param array $files
     * @return Task
     * @throws Exception
     */
    public function uploadTaskFiles(Task $task, array $files): Task
    {
        DB::transaction(function () use ($task, $files) {
            $this->uploadFiles($task, $files);
            
            // Send notifications
            $this->notifyTaskFileUpload($task);
        });

        return $task;
    }

    /**
     * Update task status
     * 
     * @param Task $task
     * @param string $status
     * @return Task
     * @throws Exception
     */
    public function updateTaskStatus(Task $task, string $status): Task
    {
        DB::transaction(function () use ($task, $status) {
            $task->update([
                'status' => $status
            ]);
            
            // Send notifications
            $this->notifyTaskStatusUpdate($task);
        });

        return $task;
    }

    /**
     * Upload files helper method
     * 
     * @param Task $task
     * @param array $files
     * @return void
     */
    private function uploadFiles(Task $task, array $files): void
    {
        foreach ($files as $file) {
            $directory = 'tasks/' . $task->taskid;
            store_file_media($file, $task, $directory);
        }
    }

    /**
     * Notify users about task creation
     * 
     * @param Task $task
     * @return void
     */
    private function notifyTaskCreation(Task $task): void
    {
        $notifiableUserIds = $task->users()->pluck('users.id')->toArray();
        $notifiableUsers = $this->taskRepository->getUsersWithEmployeeByIds($notifiableUserIds);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new TaskCreateNotification($task, auth()->user()));

            // Send Mail to the notifiableUser's email & Dispatch the email to the queue
            Mail::to($notifiableUser->employee->official_email)->queue(new NewTaskMail($task, $notifiableUser));
        }
    }

    /**
     * Notify users about task update
     * 
     * @param Task $task
     * @return void
     */
    private function notifyTaskUpdate(Task $task): void
    {
        $notifiableUserIds = $task->users()->pluck('users.id')->toArray();
        $notifiableUsers = $this->taskRepository->getUsersWithEmployeeByIds($notifiableUserIds);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new TaskUpdateNotification($task, auth()->user()));

            // Send Mail to the notifiableUser's email
            Mail::to($notifiableUser->employee->official_email)->queue(new UpdateTaskMail($task, $notifiableUser));
        }
    }

    /**
     * Notify users about task user addition
     * 
     * @param Task $task
     * @param array $userIds
     * @return void
     */
    private function notifyTaskUserAddition(Task $task, array $userIds): void
    {
        $notifiableUsers = $this->taskRepository->getUsersWithEmployeeByIds($userIds);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new TaskAddUsersNotification($task, auth()->user()));

            // Send Mail to the notifiableUser's email
            Mail::to($notifiableUser->employee->official_email)->queue(new AddUsersTaskMail($task, $notifiableUser));
        }
    }

    /**
     * Notify users about task file upload
     * 
     * @param Task $task
     * @return void
     */
    private function notifyTaskFileUpload(Task $task): void
    {
        $notifiableUserIds = $task->users()->pluck('users.id')->toArray();
        $notifiableUsers = $this->taskRepository->getUsersWithEmployeeByIds($notifiableUserIds);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new TaskFileUploadNotification($task, auth()->user()));

            // Send Mail to the notifiableUser's email
            Mail::to($notifiableUser->employee->official_email)->queue(new FileUploadForTaskMail($task, $notifiableUser));
        }
    }

    /**
     * Notify users about task status update
     * 
     * @param Task $task
     * @return void
     */
    private function notifyTaskStatusUpdate(Task $task): void
    {
        $notifiableUserIds = $task->users()->pluck('users.id')->toArray();
        $notifiableUsers = $this->taskRepository->getUsersWithEmployeeByIds($notifiableUserIds);

        foreach ($notifiableUsers as $notifiableUser) {
            // Send Notification to System
            $notifiableUser->notify(new TaskStatusUpdateNotification($task, auth()->user()));

            // Send Mail to the notifiableUser's email
            Mail::to($notifiableUser->employee->official_email)->queue(new StatusUpdateTaskMail($task, $notifiableUser));
        }
    }
}
