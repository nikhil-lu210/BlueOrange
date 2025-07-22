<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\User;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use App\Models\Task\TaskHistory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Task\TaskStopMail;
use App\Mail\Administration\Task\TaskStartMail;
use App\Services\Administration\Task\TaskCommentService;
use App\Notifications\Administration\Task\TaskStopNotification;
use App\Notifications\Administration\Task\TaskStartNotification;

class TaskHistoryController extends Controller
{

    /**
     * Update
     */
    public function hasUnderstood(Task $task, $status)
    {
        try {
            $task->users()->updateExistingPivot(auth()->id(), ['has_understood' => $status === 'false' ? false : true]);

            $taskCommentService = new TaskCommentService();

            $comment = $status === 'false'
                                    ? "<p>I am having difficulty understanding this task.</p><p>Could you please provide further clarification or additional details?</p>"
                                    : "<p>I have reviewed and understood the task requirements.</p><p>Thank you for the clear instructions.</p>";

            $data = [
                'comment' => $comment
            ];

            // Create comment using service
            $taskCommentService->storeComment($task, $data);

            toast('Task Marked as '. ($status ? 'Understood' : 'Not Understood'), 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()->withInput()->withErrors('An error occurred: ' . $e->getMessage());
        }
    }



    /**
     * Store a newly created resource in storage.
     */
    public function start(Request $request, Task $task)
    {
        // dd($request->all(), $task->id);
        try {
            DB::transaction(function () use ($task) {
                // Create a new TaskHistory
                $history = TaskHistory::create([
                    'task_id' => $task->id,
                ]);

                // Check if this is the first TaskHistory for the task
                $historyCount = $task->histories()->count();
                if ($historyCount >= 1 && $task->status === 'Active') {
                    // Update task status to Running
                    $task->update(['status' => 'Running']);
                }

                $this->notifyTaskUsers($task, new TaskStartNotification($task, auth()->user()), function ($user) use ($task, $history) {
                    Mail::to($user->employee->official_email)->queue(new TaskStartMail($task, $history, $user, auth()->user()));
                });
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


                $this->notifyTaskUsers($task, new TaskStopNotification($task, auth()->user()), function ($user) use ($task, $taskHistory) {
                    Mail::to($user->employee->official_email)->queue(new TaskStopMail($task, $taskHistory, $user, auth()->user()));
                });
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


    /**
     * Notify assigned users and creator (excluding current user).
     */
    private function notifyTaskUsers(Task $task, $notification, callable $mailCallback): void
    {
        $assignedUserIds = $task->users()->pluck('users.id')->toArray();

        if (!in_array($task->creator_id, $assignedUserIds)) {
            $assignedUserIds[] = $task->creator_id;
        }

        $notifiableUserIds = array_diff($assignedUserIds, [auth()->id()]);

        if (empty($notifiableUserIds)) return;

        $notifiableUsers = User::with('employee')
            ->select('id', 'name', 'email')
            ->whereIn('id', $notifiableUserIds)
            ->get();

        foreach ($notifiableUsers as $user) {
            $user->notify($notification);
            $mailCallback($user);
        }
    }
}
