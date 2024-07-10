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
        // dd($tasks);
        return view('administration.task.index', compact(['tasks']));
    }
    
    /**
     * Display a listing of the resource.
     */
    public function my()
    {
        return view('administration.task.my');
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
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Task: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, $taskid)
    {
        $task = Task::whereId($task->id)->whereTaskid($taskid)->firstOrFail();
        // dd(get_file_media_url($task->files[0]), $task->files[0]->original_name);
        return view('administration.task.show', compact(['task']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        dd($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        dd($request->all(), $task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        dd($task);
    }
}
