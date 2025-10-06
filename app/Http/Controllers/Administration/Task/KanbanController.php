<?php

namespace App\Http\Controllers\Administration\Task;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task\Task;
use Illuminate\Support\Carbon;

class KanbanController extends Controller
{
    /**
     * Get kanban board data formatted for jKanban
     */
    public function getKanbanData(Request $request)
    {
        try {
            // Fetch tasks with relationships
            $tasks = Task::with([
                'creator:id,userid,name',
                'users:id,name',
                'comments',
                'files',
                'parent_task:id,title',
                'sub_tasks'
            ])->get();

            // Group tasks by status
            $groupedTasks = $tasks->groupBy('status');

            // Define status mapping
            $statusMapping = [
                'Active' => [
                    'id' => 'board-activated',
                    'title' => 'Activated',
                    'color' => 'danger'
                ],
                'Running' => [
                    'id' => 'board-running', 
                    'title' => 'Running',
                    'color' => 'info'
                ],
                'Completed' => [
                    'id' => 'board-completed',
                    'title' => 'Completed', 
                    'color' => 'success'
                ],
                'Cancelled' => [
                    'id' => 'board-cancelled',
                    'title' => 'Cancelled',
                    'color' => 'secondary'
                ]
            ];

            $kanbanData = [];

            foreach ($statusMapping as $status => $boardConfig) {
                $boardTasks = $groupedTasks->get($status, collect());
                
                $kanbanData[] = [
                    'id' => $boardConfig['id'],
                    'title' => $boardConfig['title'],
                    'item' => $boardTasks->map(function ($task, $index) use ($boardConfig) {
                        return $this->formatTaskForKanban($task, $index + 1, $boardConfig);
                    })->values()->toArray()
                ];
            }

            return response()->json($kanbanData);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch kanban data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format individual task for kanban board
     */
    private function formatTaskForKanban($task, $index, $boardConfig)
    {
        // Calculate progress based on assigned users or default
        $progress = $this->calculateTaskProgress($task);
        
        // Get priority badge info
        $badgeInfo = $this->getPriorityBadgeInfo($task->priority);
        
        // Format assigned users data
        $assignedData = $this->formatAssignedUsers($task->users);
        
        // Format deadline
        $deadline = $task->deadline ? show_date($task->deadline, 'd-m-Y') : null;
        
        // Base task data
        $taskData = [
            'id' => strtolower($boardConfig['title']) . '-' . $task->id,
            'title' => $task->title,
            'description' => show_content(strip_tags($task->description), 100), // Remove HTML tags
            'comments' => (string) $task->all_comments->count(),
            'badge-text' => $badgeInfo['text'],
            'badge' => $badgeInfo['color'],
            'deadline' => $deadline,
            'attachments' => (string) $task->files->count(),
            'progress' => (string) $progress,
            'created-by' => $task->creator->alias_name ?? 'Unknown',
            'assigned' => $assignedData['avatars'],
            'members' => $assignedData['names'],
            'task-history-url' => route('administration.task.history.show', $task),
            'task-show-url' => route('administration.task.show', [$task, $task->taskid]),
        ];

        if($task->sub_tasks->count() > 0) {
            $taskData['sub_tasks'] = $task->sub_tasks->count();
        }

        // Add parent task ID if exists
        if ($task->parent_task_id && $task->parent_task) {
            // Get parent task's board config by its own status
            $parentBoardConfig = $this->getBoardConfigByStatus($task->parent_task->status);

            // Use parent board title for parent_id
            $taskData['parent_id'] = strtolower($parentBoardConfig['title']) . '-' . $task->parent_task_id;
            $taskData['parent_title'] = $task->parent_task->title;
        }

        return $taskData;
    }

    /**
     * Calculate task progress based on assigned users
     */
    private function calculateTaskProgress($task)
    {
        // Load pivot data if not already loaded
        $users = $task->users;

        // Sum progress from pivot
        $totalProgress = $users->sum(function ($user) {
            return $user->pivot->progress ?? 0;
        });

        $userCount = $users->count();

        $averageProgress = $userCount > 0 ? round($totalProgress / $userCount) : 0;

        // Override logic based on task status
        if ($task->status === 'Completed') {
            return 100;
        } elseif ($task->status === 'Cancelled') {
            return 0;
        }

        return $averageProgress;
    }


    /**
     * Get priority badge information
     */
    private function getPriorityBadgeInfo($priority)
    {
        $priorityMap = [
            'High' => ['text' => 'High', 'color' => 'danger'],
            'Medium' => ['text' => 'Medium', 'color' => 'warning'], 
            'Low' => ['text' => 'Low', 'color' => 'success'],
            'Critical' => ['text' => 'Critical', 'color' => 'danger'],
        ];

        return $priorityMap[$priority] ?? ['text' => 'Normal', 'color' => 'primary'];
    }

    /**
     * Format assigned users data for kanban
     */
    private function formatAssignedUsers($users)
    {
        $avatars = [];
        $names = [];

        foreach ($users as $user) {
            // Assuming you have avatar field in users table or a default avatar logic

            if ($user->hasMedia('avatar')):
                $avatar = $user->getFirstMediaUrl('avatar', 'thumb');
            else:
                $avatar = profile_name_pic($user);
            endif;

            $avatars[] = $avatar;
            $names[] = $user->name;
        }

        return [
            'avatars' => $avatars,
            'names' => $names
        ];
    }

    /**
     * Create a new task via AJAX
     */
    public function createTask(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'board_id' => 'required|string',
            'description' => 'nullable|string',
        ]);

        try {
            // Extract status from board_id
            $status = $this->getStatusFromBoardId($request->board_id);
            
            $task = Task::create([
                'taskid' => 'TSK' . date('YmdHis'),
                'title' => $request->title,
                'description' => $request->description ?? '',
                'status' => $status,
                'priority' => 'Medium', // Default priority
                'creator_id' => auth()->id(),
                'deadline' => now()->addDays(7), // Default 7 days from now
            ]);

            // Return formatted task data
            $boardConfig = $this->getBoardConfigByStatus($status);
            $formattedTask = $this->formatTaskForKanban($task->load(['creator', 'users', 'comments', 'files']), 1, $boardConfig);

            return response()->json([
                'success' => true,
                'task' => $formattedTask
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update task status (for drag and drop)
     */
    public function updateTaskStatus(Request $request)
    {
        $request->validate([
            'task_id' => 'required|string',
            'new_status' => 'required|string',
        ]);

        try {
            // Extract actual task ID from kanban task ID
            $taskId = $this->extractTaskIdFromKanbanId($request->task_id);
            $status = $this->getStatusFromBoardId($request->new_status);

            $task = Task::findOrFail($taskId);
            $task->status = $status;
            $task->save();

            return response()->json([
                'success' => true,
                'message' => 'Task status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update task status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a task
     */
    public function deleteTask(Request $request)
    {
        $request->validate([
            'task_id' => 'required|string',
        ]);

        try {
            $taskId = $this->extractTaskIdFromKanbanId($request->task_id);
            $task = Task::findOrFail($taskId);
            $task->delete();

            return response()->json([
                'success' => true,
                'message' => 'Task deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete task: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper method to get status from board ID
     */
    private function getStatusFromBoardId($boardId)
    {
      $statusMap = [
        'board-activated' => 'Active',
        'board-running' => 'Running',
        'board-completed' => 'Completed',
        'board-cancelled' => 'Cancelled',
      ];
      return $statusMap[$boardId] ?? 'Active';
    }

    /**
     * Helper method to get board config by status
     */
    private function getBoardConfigByStatus($status)
    {
        $configMap = [
            'Active' => ['id' => 'board-activated', 'title' => 'Activated', 'color' => 'danger'],
            'Running' => ['id' => 'board-running', 'title' => 'Running', 'color' => 'info'],
            'Completed' => ['id' => 'board-completed', 'title' => 'Completed', 'color' => 'success'],
            'Cancelled' => ['id' => 'board-cancelled', 'title' => 'Cancelled', 'color' => 'secondary'],
        ];
        return $configMap[$status] ?? $configMap['Active'];
    }

    /**
     * Helper method to extract actual task ID from kanban task ID
     */
    private function extractTaskIdFromKanbanId($kanbanTaskId)
    {
        // Extract ID from format like "activated-5" or "running-10"
        return (int) substr($kanbanTaskId, strrpos($kanbanTaskId, '-') + 1);
    }
}


