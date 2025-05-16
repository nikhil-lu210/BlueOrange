<small class="{{ $isCurrentUser ? 'float-left' : 'float-right' }} pt-1">
    @if ($isCurrentUser)
        <a href="javascript:void(0);" class="text-bold action-btn-right-margin" wire:click="setReplyMessage({{ $message->id }})" title="Reply">
            <i class="ti ti-arrow-back-up fs-4"></i>
        </a>
    @else
        <a href="javascript:void(0);" class="text-bold" wire:click="setReplyMessage({{ $message->id }})" title="Reply">
            <i class="ti ti-arrow-back-up fs-4"></i>
        </a>
    @endif
</small>
