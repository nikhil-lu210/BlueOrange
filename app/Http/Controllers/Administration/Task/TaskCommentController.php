<?php

namespace App\Http\Controllers\Administration\Task;

use Exception;
use App\Models\Task\Task;
use App\Http\Controllers\Controller;
use App\Http\Requests\Comment\CommentStoreRequest;
use App\Services\Administration\Task\TaskCommentService;

class TaskCommentController extends Controller
{
    protected $taskCommentService;

    public function __construct(TaskCommentService $taskCommentService)
    {
        $this->taskCommentService = $taskCommentService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentStoreRequest $request, Task $task)
    {
        try {
            // Prepare data for service
            $data = [
                'comment' => $request->comment,
                'files' => $request->hasFile('files') ? $request->file('files') : null,
                'parent_comment_id' => $request->parent_comment_id ?? null
            ];

            // Create comment using service
            $this->taskCommentService->storeComment($task, $data);

            $message = $request->parent_comment_id ? 'Task Comment Reply Submitted Successfully.' : 'Task Comment Submitted Successfully.';
            toast($message, 'success');
            return redirect()->back();
        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the comment: ' . $e->getMessage());
        }
    }
}
