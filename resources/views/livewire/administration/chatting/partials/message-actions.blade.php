@isset ($message->task)
    <small class="{{ $isCurrentUser ? 'float-left' : 'float-right' }} pt-1" title="Show Related Task">
        <a href="{{ route('administration.task.show', ['task' => $message->task, 'taskid' => $message->task->taskid]) }}" target="_blank" class="text-bold text-dark">Show Task</a>
    </small>
@else
    <small class="{{ $isCurrentUser ? 'float-left' : 'float-right' }} pt-1">
        @if ($isCurrentUser)
            <a href="javascript:void(0);" class="text-bold" wire:click="$set('replyToMessageId', {{ $message->id }})" title="Reply" style="margin-right: 10px;">
                <i class="ti ti-arrow-back-up fs-4"></i>
            </a>
            @can ('Task Create')
                <a href="{{ route('administration.task.create.chat.task', ['message' => $message]) }}" target="_blank" class="text-bold" title="Create New Task">
                    <i class="ti ti-brand-stackshare"></i>
                </a>
            @endcan
        @else
            @can ('Task Create')
                <a href="{{ route('administration.task.create.chat.task', ['message' => $message]) }}" target="_blank" class="text-bold" title="Create New Task" style="margin-right: 10px;">
                    <i class="ti ti-brand-stackshare"></i>
                </a>
            @endcan
            <a href="javascript:void(0);" class="text-bold" wire:click="$set('replyToMessageId', {{ $message->id }})" title="Reply">
                <i class="ti ti-arrow-back-up fs-4"></i>
            </a>
        @endif
    </small>
@endisset
