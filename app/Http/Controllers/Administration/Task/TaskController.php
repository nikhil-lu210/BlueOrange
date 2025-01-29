<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\User;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Task\NewTaskMail;
use App\Mail\Administration\Task\UpdateTaskMail;
use App\Mail\Administration\Task\AddUsersTaskMail;
use App\Mail\Administration\Task\FileUploadForTaskMail;
use App\Http\Requests\Administration\Task\TaskStoreRequest;
use App\Http\Requests\Administration\Task\TaskUpdateRequest;
use App\Mail\Administration\Task\StatusUpdateTaskMail;
use App\Models\Chatting\Chatting;
use App\Notifications\Administration\Task\TaskCreateNotification;
use App\Notifications\Administration\Task\TaskUpdateNotification;
use App\Notifications\Administration\Task\TaskAddUsersNotification;
use App\Notifications\Administration\Task\TaskFileUploadNotification;
use App\Notifications\Administration\Task\TaskStatusUpdateNotification;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::select(['id', 'name'])
                        ->with([
                            'users' => function ($user) {
                                $user->permission('Task Create')
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

        $assignees = User::permission('Task Read')
                        ->select(['id', 'name'])
                        ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->get();

        $query = Task::with([
            'creator' => function($query) {
                $query->select(['id', 'first_name', 'last_name']);
            },
            'users'
        ])->orderByDesc('created_at');

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

        $tasks = $query->get();
                    
        return view('administration.task.index', compact(['tasks', 'roles', 'assignees']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my(Request $request)
    {
        $creators = User::permission('Task Create')->select(['id', 'name'])->get();

        $query = Task::with(['creator:id,first_name,last_name'])
                        ->where(function ($taskQuery) {
                            $taskQuery->whereHas('users', function ($userQuery) {
                                $userQuery->where('user_id', auth()->id());
                            })->orWhere('creator_id', auth()->id());
                        })
                        ->orderByDesc('created_at');

        if ($request->has('creator_id') && !is_null($request->creator_id)) {
            $query->where('creator_id', $request->creator_id);
        }

        if ($request->has('status') && !is_null($request->status)) {
            $query->where('status', $request->status);
        }

        $tasks = $query->get();

        // Count total tasks
        $totalTasks = $tasks->count();

        // Count tasks by status
        $statusCounts = $tasks->groupBy('status')->map->count();

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
    public function create()
    {
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();
        
        return view('administration.task.create', compact(['roles']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createChatTask(Chatting $message)
    {
        // dd($message->toArray());
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();
        
        return view('administration.task.create_chat_task', compact(['roles', 'message']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            $task = null;
            $taskID = null;
            DB::transaction(function () use ($request, &$task, &$taskID) {
                $task = Task::create([
                    'chatting_id' => $request->chatting_id ?? NULL,
                    'title' => $request->title,
                    'description' => $request->description,
                    'deadline' => $request->deadline ?? null,
                    'priority' => $request->priority
                ]);
                $taskID = $task->taskid;

                // Assign users to the task if necessary
                if ($request->has('users')) {
                    $task->users()->attach($request->users);
                }

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'tasks/' . $task->taskid;
                        store_file_media($file, $task, $directory);
                    }
                }

                $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

                $notifiableUsers = [];
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $notifiableUserIds)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskCreateNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email & Dispatch the email to the queue
                    Mail::to($notifiableUser->email)->queue(new NewTaskMail($task, $notifiableUser));
                }
            });

            toast('Task assigned successfully.', 'success');
            return redirect()->route('administration.task.show', ['task' => $task, 'taskid' => $taskID]);
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
        

        $task = Task::with([
                'creator', 
                'users', 
                'files', 
                'comments.files', 
                'histories' => function ($history) {
                    $history->whereStatus('Completed')->orderBy('ends_at', 'desc')->get();
                }
            ])
            ->whereId($task->id)
            ->whereTaskid($taskid)
            ->firstOrFail();

        $isWorking = $task->histories()
                        ->whereUserId(auth()->id())
                        ->whereNull('ends_at')
                        ->whereStatus('Working')
                        ->exists();

        $lastActiveTaskHistory = $task->histories()
                        ->whereUserId(auth()->id())
                        ->where('status', 'Working')
                        ->latest('started_at')
                        ->first();

        $roles = Role::with([
            'users' => function($query) use ($task) {
                $query->whereDoesntHave('tasks', function($taskQuery) use ($task) {
                    $taskQuery->where('task_id', $task->id);
                })
                ->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                ->whereStatus('Active');
            }
        ])->get();
        

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
        
        $roles = Role::with([
            'users' => function ($query) {
                $query->whereIn('id', auth()->user()->user_interactions->pluck('id'))
                        ->whereStatus('Active')
                        ->orderBy('name', 'asc');
            }
        ])->get();

        return view('administration.task.edit', compact(['roles', 'task']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        try {
            DB::transaction(function () use ($request, $task) {
                $task->update([
                    'title' => $request->title,
                    'description' => $request->description,
                    'deadline' => $request->deadline ?? null,
                    'priority' => $request->priority
                ]);

                $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

                $notifiableUsers = [];
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $notifiableUserIds)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskUpdateNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email
                    Mail::to($notifiableUser->email)->queue(new UpdateTaskMail($task, $notifiableUser));
                }
            });
            
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
        // dd($task);
        try {
            $task->delete();
            
            toast('Task Has Been Delete Successfully.', 'success');
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
                function ($attribute, $value, $fail) use ($task) {
                    if ($task->users()->where('user_id', $value)->exists()) {
                        $fail('The user is already assigned to this task.');
                    }
                },
            ],
        ]);

        try {
            DB::transaction(function() use ($request, $task) {
                if ($request->has('users')) {
                    $task->users()->attach($request->users);
                }

                $notifiableUsers = [];
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $request->users)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskAddUsersNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email
                    Mail::to($notifiableUser->email)->queue(new AddUsersTaskMail($task, $notifiableUser));
                }
            });

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
                function ($attribute, $value, $fail) use ($task) {
                    if (!$task->users()->where('user_id', $value)->exists()) {
                        $fail('The selected user is not assigned to this task.');
                    }
                },
            ],
        ]);
        // dd($request->all(), $task);

        try {
            if ($request->has('user')) {
                $task->users()->detach($request->user);
            }

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
            DB::transaction(function() use ($request, $task) {
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'tasks/' . $task->taskid;
                        store_file_media($file, $task, $directory);
                    }
                }

                $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

                $notifiableUsers = [];
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $notifiableUserIds)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskFileUploadNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email
                    Mail::to($notifiableUser->email)->queue(new FileUploadForTaskMail($task, $notifiableUser));
                }
            });

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
            DB::transaction(function() use ($request, $task) {
                $task->update([
                    'status' => $request->status
                ]);

                $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

                $notifiableUsers = [];
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $notifiableUserIds)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskStatusUpdateNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email
                    Mail::to($notifiableUser->email)->queue(new StatusUpdateTaskMail($task, $notifiableUser));
                }
            });

            toast('Task Status Updated to '. $request->status, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
