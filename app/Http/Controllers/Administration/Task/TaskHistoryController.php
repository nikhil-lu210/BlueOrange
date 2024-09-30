<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use App\Models\Task\TaskHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TaskHistoryController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function start(Request $request, Task $task)
    {
        // dd($request->all(), $task->id);        
        try {
            DB::transaction(function () use ($task) {
                // Create a new TaskHistory
                TaskHistory::create([
                    'task_id' => $task->id,
                ]);
    
                // Check if this is the first TaskHistory for the task
                $historyCount = $task->histories()->count();
                if ($historyCount >= 1 && $task->status === 'Active') {
                    // Update task status to Running
                    $task->update(['status' => 'Running']);
                }
            });
            
            toast('Task Started Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while starting the Task: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function stop(Request $request, Task $task, TaskHistory $taskHistory)
    {
        // dd($request->all(), $task->id, $taskHistory->id);
        try {      
            DB::transaction(function () use ($request, $task, $taskHistory) {
                $currentTime = now();
                $endsAtTime = $currentTime->timestamp; // Use timestamp
                $startedAtTime = $taskHistory->started_at->timestamp;
    
                // Calculate total time in seconds
                $totalSeconds = $endsAtTime - $startedAtTime;
    
                // Convert total time to HH:MM:SS format
                $hours = floor($totalSeconds / 3600);
                $minutes = floor(($totalSeconds % 3600) / 60);
                $seconds = $totalSeconds % 60;
    
                $formattedTotalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                // dd($formattedTotalTime);
                
                $taskHistory->update([
                    'ends_at' => now(),
                    'total_worked' => $formattedTotalTime,
                    'note' => $request->note,
                    'progress' => $request->progress,
                    'status' => 'Completed',
                ]);

                // Update progress in the pivot table
                $task->users()->updateExistingPivot(auth()->id(), ['progress' => $request->progress]);

                // Store Task Files
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $directory = 'tasks/' . $task->taskid .'/task_history/' . auth()->user()->userid;
                        store_file_media($file, $taskHistory, $directory);
                    }
                }
            });

            toast('Task stopped successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->with('error', 'An error occurred while stopping the Task: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $histories = TaskHistory::with(['task', 'user', 'files'])->whereTaskId($task->id)->orderBy('created_at', 'desc')->get();
        // dd($histories);
        return view('administration.task.history', compact(['task', 'histories']));
    }
}
