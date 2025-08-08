<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Chatting\Chatting;
use App\Http\Requests\Administration\Task\TaskStoreRequest;
use App\Http\Requests\Administration\Task\TaskUpdateRequest;
use App\Repositories\Administration\Task\TaskRepository;
use App\Services\Administration\Task\TaskService;

class TaskController extends Controller
{
    protected $taskService;
    protected $taskRepository;

    public function __construct(TaskService $taskService, TaskRepository $taskRepository)
    {
        $this->taskService = $taskService;
        $this->taskRepository = $taskRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = $this->taskRepository->getRolesWithTaskCreators();
        $assignees = $this->taskRepository->getAssignableUsers();
        $tasks = $this->taskRepository->getTasksQuery($request)->get();

        return view('administration.task.index', compact(['tasks', 'roles', 'assignees']));
    }

    public function kanban(Request $request)
    {
        return view('administration.task.kanban');
    }

    public function sprint(Request $request)
    {
        $roles = $this->taskRepository->getRolesWithTaskCreators();
        $assignees = $this->taskRepository->getAssignableUsers();
        $tasks = $this->taskRepository->getTasksQuery($request)->get();

        return view('administration.task.sprint', compact(['tasks', 'roles', 'assignees']));
    }

    public function fetch()
    {
        $tasks =  $this->taskRepository->getTasksQuery()->get();
        return response()->json($tasks);
    }

    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        $creators = User::with(['employee'])->permission('Task Create')->select(['id', 'name'])->get();

        // Get all tasks for the current user
        $query = $this->taskRepository->getMyTasksQuery($request);
        $allTasks = $query->get();

        // Count tasks by status
        $statusCounts = $allTasks->groupBy('status')->map->count();

        // Get paginated tasks
        $tasks = $query->paginate(20);
        $totalTasks = $tasks->total();

        // Calculate percentages
        $statusPercentages = [
            'active' => $totalTasks > 0 ? round(($statusCounts->get('Active', 0) / $totalTasks) * 100, 2) : 0,
            'running' => $totalTasks > 0 ? round(($statusCounts->get('Running', 0) / $totalTasks) * 100, 2) : 0,
            'completed' => $totalTasks > 0 ? round(($statusCounts->get('Completed', 0) / $totalTasks) * 100, 2) : 0,
            'canceled' => $totalTasks > 0 ? round(($statusCounts->get('Cancelled', 0) / $totalTasks) * 100, 2) : 0,
        ];

        return view('administration.task.my', compact(['creators', 'tasks', 'statusPercentages']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $roles = $this->taskRepository->getRolesWithUsers();
        $tasks = $this->taskRepository->getMyTasksQuery(new Request())->get();

        return view('administration.task.create', compact(['roles', 'tasks']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createChatTask(Chatting $message)
    {
        $roles = $this->taskRepository->getRolesWithUsers();

        return view('administration.task.create_chat_task', compact(['roles', 'message']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            // Create task using service
            $task = $this->taskService->createTask($request->validated());

            toast('Task assigned successfully.', 'success');
            return redirect()->route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, $taskid)
    {
        abort_if(
            !(
                $task->users->contains(auth()->user()->id) ||
                $task->creator_id == auth()->user()->id ||
                auth()->user()->hasRole('Developer') ||
                auth()->user()->hasRole('Super Admin')
            ),
            403,
            'You are not authorized to view this task as you are not the assigner, assignee, Developer, or Superadmin.'
        );

        // Get detailed task with all relationships
        $task = $this->taskRepository->getDetailedTask($task, $taskid);

        // Check if user is currently working on this task
        $isWorking = $task->histories()
                        ->whereUserId(auth()->id())
                        ->whereNull('ends_at')
                        ->whereStatus('Working')
                        ->exists();

        // Get last active task history
        $lastActiveTaskHistory = $task->histories()
                        ->whereUserId(auth()->id())
                        ->where('status', 'Working')
                        ->orderByDesc('started_at')
                        ->first();

        // Get roles with users not assigned to this task
        $roles = $this->taskRepository->getRolesWithUnassignedUsers($task);

        return view('administration.task.show', compact(['task', 'isWorking', 'lastActiveTaskHistory', 'roles']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        abort_if(
            !(
                $task->creator_id == auth()->user()->id ||
                auth()->user()->hasRole('Developer')
            ),
            403,
            'You are not authorized to view this task as you are not the assigner, assignee, Developer, or Superadmin.'
        );

        $roles = $this->taskRepository->getRolesWithUsers();

        return view('administration.task.edit', compact(['roles', 'task']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        try {
            // Update task using service
            $task = $this->taskService->updateTask($task, $request->validated());

            toast('Task Updated successfully.', 'success');
            return redirect()->route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]);
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();

            toast('Task Has Been Deleted Successfully.', 'success');
            return redirect()->route('administration.task.index');
        } catch (Exception $e) {
            return redirect()->back()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Add Users for Task
     */
    public function addUsers(Request $request, Task $task)
    {
        $request->validate([
            'users' => ['required', 'array'],
            'users.*' => [
                'integer',
                'exists:users,id',
                function (/** @noinspection PhpUnusedParameterInspection */ $_, $value, $fail) use ($task) {
                    if ($task->users()->where('user_id', $value)->exists()) {
                        $fail('The user is already assigned to this task.');
                    }
                },
            ],
        ]);

        try {
            // Add users to task using service
            $this->taskService->addUsersToTask($task, $request->users);

            toast('Assignees Added Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Remove user for task
     */
    public function removeUser(Request $request, Task $task)
    {
        $request->validate([
            'user' => [
                'required',
                'integer',
                'exists:users,id',
                function (/** @noinspection PhpUnusedParameterInspection */ $_, $value, $fail) use ($task) {
                    if (!$task->users()->where('user_id', $value)->exists()) {
                        $fail('The selected user is not assigned to this task.');
                    }
                },
            ],
        ]);

        try {
            // Remove user from task using service
            $this->taskService->removeUserFromTask($task, $request->user);

            toast('Assignee Removed Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Upload Files for task
     */
    public function uploadFiles(Request $request, Task $task)
    {
        $request->validate([
            'files.*' => ['required', 'max:5000']
        ]);

        try {
            // Upload files to task using service
            $this->taskService->uploadTaskFiles($task, $request->file('files'));

            toast('Task Files Added Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }


    /**
     * Update Task Status
     */
    public function updateTaskStatus(Request $request, Task $task) {
        abort_if($task->creator_id != auth()->user()->id, 403, 'You are not authorized to update the task status. Only the task creator can update the status');

        $request->validate([
            'status' => ['required', 'in:Active,Running,Completed,Cancelled']
        ]);

        try {
            // Update task status using service
            $this->taskService->updateTaskStatus($task, $request->status);

            toast('Task Status Updated to '. $request->status, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
