<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\User;
use App\Models\Task\Task;
use Illuminate\Http\Request;
use App\Models\Task\TaskComment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\Administration\Task\NewCommentOnTaskMail;
use App\Notifications\Administration\Task\TaskCommentNotification;

class TaskCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        // dd($request->all(), $task->id, auth()->user()->userid);
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
                        $directory = 'tasks/' . $task->taskid .'/comments/' . auth()->user()->userid;
                        store_file_media($file, $comment, $directory);
                    }
                }

                // Retrieve the user IDs of the assigned users
                $notifiableUserIds = $task->users()->pluck('users.id')->toArray();

                // Add the task creator's ID to the list if it's not already included
                if (!in_array($task->creator_id, $notifiableUserIds)) {
                    $notifiableUserIds[] = $task->creator_id;
                }

                // Exclude the commenter's ID from the list of notifiable user IDs
                $notifiableUserIds = array_diff($notifiableUserIds, [auth()->user()->id]);

                $notifiableUsers = [];
                // Retrieve the notifiable users based on the combined list of user IDs
                $notifiableUsers = User::select(['id', 'name', 'email'])->whereIn('id', $notifiableUserIds)->get();
                
                foreach ($notifiableUsers as $notifiableUser) {
                    // Send Notification to System
                    $notifiableUser->notify(new TaskCommentNotification($task, auth()->user()));

                    // Send Mail to the notifiableUser's email
                    Mail::to($notifiableUser->email)->send(new NewCommentOnTaskMail($task, $notifiableUser, auth()->user()));
                }
            });
            
            toast('Task Comment Submitted Successfully.', 'success');
            return redirect()->back();
        } catch (Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An error occurred while creating the Task: ' . $e->getMessage());
        }
    }
}
