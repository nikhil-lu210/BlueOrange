<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Models\FileMedia\FileMedia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Administration\Task\TaskStoreRequest;
use App\Http\Requests\Administration\Task\TaskUpdateRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::with([
                        'creator' => function($creator) {
                            $creator->select(['id', 'first_name', 'last_name']);
                        }
                    ])
                    ->orderByDesc('created_at')
                    ->get();
                    
        return view('administration.task.index', compact(['tasks']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        $tasks = Task::with([
                        'creator:id,first_name,last_name'
                    ])
                    ->whereHas('users', function($query) {
                        $query->where('user_id', auth()->id());
                    })
                    ->orderByDesc('created_at')
                    ->get();
                    
        return view('administration.task.my', compact(['tasks']));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::with(['users'])->get();
        return view('administration.task.create', compact(['roles']));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $task = Task::create([
                    'title' => $request->title,
                    'description' => $request->description,
                    'deadline' => $request->deadline ?? null,
                    'priority' => $request->priority
                ]);

                // Assign users to the task if necessary
                if ($request->has('users')) {
                    $task->users()->attach($request->users);
                }

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'public/tasks/' . $task->taskid;
                        store_file_media($file, $task, $directory);
                    }
                }
            });

            toast('Task assigned successfully.', 'success');
            return redirect()->route('administration.task.index');
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, $taskid)
    {
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
        
        $roles = Role::with(['users' => function($user) use ($task) {
            $user->whereDoesntHave('tasks', function($taskQuery) use ($task) {
                $taskQuery->where('task_id', $task->id);
            });
        }])->get();

        return view('administration.task.show', compact(['task', 'isWorking', 'lastActiveTaskHistory', 'roles']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $roles = Role::with(['users'])->get();
        return view('administration.task.edit', compact(['roles', 'task']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, Task $task)
    {
        try {
            $task->update([
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => $request->deadline ?? null,
                'priority' => $request->priority
            ]);
            
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
        dd($task);
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
            if ($request->has('users')) {
                $task->users()->attach($request->users);
            }

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
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $directory = 'public/tasks/' . $task->taskid;
                    store_file_media($file, $task, $directory);
                }
                
                toast('Task Files Added Successfully.', 'success');
                return redirect()->back();
            } else {
                toast('No File Selected. Please Select Files.', 'danger');
                return redirect()->back();
            }
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }
}
