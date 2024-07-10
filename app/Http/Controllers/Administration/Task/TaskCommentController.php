<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use App\Models\Task\TaskComment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TaskCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        // dd($request->all(), $task->id);
        $request->validate([
            'comment' => ['required', 'string', 'min:10'],
            'files.*' => ['nullable', 'max:5000']
        ]);
        
        try {
            DB::transaction(function () use ($request, $task) {
                $comment = TaskComment::create([
                    'task_id' => $task->id,
                    'comment' => $request->comment
                ]);

                // Store Task's Comment Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'public/tasks/comments' . $task->taskid;
                        store_file_media($file, $comment, $directory);
                    }
                }
            });
            
            toast('Task Comment Submitted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Task: ' . $e->getMessage());
        }
    }
}
