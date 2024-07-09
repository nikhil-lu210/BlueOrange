<?php

namespace App\Http\Controllers\Administration\Task;

use App\Http\Controllers\Controller;
use App\Models\Task\Task;
use Illuminate\Http\Request;

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
        return view('administration.task.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        dd($task);
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
