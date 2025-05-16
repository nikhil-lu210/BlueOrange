<div class="col app-chat-history bg-body">
    <div class="chat-history-wrapper">
        <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex overflow-hidden align-items-center">
                    <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                    <div class="flex-shrink-0 avatar">
                        <span class="avatar-initial rounded-circle bg-dark border border-1" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
                            {{ substr($chattingGroup->name, 0, 2) }}
                        </span>
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-2">
                        <h6 class="m-0">{{ $chattingGroup->name }}</h6>
                        <small class="user-status text-muted"><b class="text-dark">Creator:</b> {{ $chattingGroup->creator->name }}</small>
                    </div>
                </div>

                @canany(['Group Chatting Create', 'Group Chatting Delete'])
                    @if ($chattingGroup->creator_id == auth()->user()->id)
                        <div class="d-flex align-items-center">
                            <div class="dropdown d-flex align-self-center">
                                <button class="btn p-0" type="button" id="chat-header-actions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                                    @can ('Group Chatting Create')
                                        <a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#addGroupChattingUsersModal">
                                            <i class="ti ti-plus"></i>
                                            Add Users
                                        </a>
                                    @endcan


                                    @can ('Group Chatting Delete')
                                        <a class="dropdown-item text-danger confirm-danger" href="{{ route('administration.chatting.group.destroy', ['group' => $chattingGroup, 'groupid' => $chattingGroup->groupid]) }}">
                                            <i class="ti ti-trash"></i>
                                            Delete Group
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endif
                @endcanany
            </div>
        </div>
        <div class="chat-history-body bg-body" wire:keep-alive>
            <ul class="list-unstyled chat-history" wire:poll.10s="loadMessages">
                @php
                    $currentDate = null;
                @endphp
                @foreach ($messages as $key => $message)
                    @php
                        // Format the message date
                        $messageDate = $message->created_at->format('Y-m-d');

                        // Update imageURL
                        if ($message->sender->hasMedia('avatar')) {
                            $imageURL = $message->sender->getFirstMediaUrl('avatar', 'thumb');
                        } else {
                            $imageURL = "https://fakeimg.pl/300/dddddd/?text=" . $message->sender->first_name;
                        }
                    @endphp

                    @if ($currentDate !== $messageDate)
                        @php
                            $currentDate = $messageDate;
                        @endphp
                        <div class="divider divider-dotted">
                            <div class="divider-text">{{ $message->created_at->format('F j, Y') }}</div>
                        </div>
                    @endif

                    {{-- Message Item --}}
                    @php
                        // Check if message is from current user
                        $isCurrentUser = $message->sender_id === auth()->user()->id;
                        // Check if this message is being replied to
                        $isBeingRepliedTo = $replyToMessageId == $message->id;
                    @endphp
                    <li class="chat-message {{ $isCurrentUser ? 'chat-message-right' : '' }}">
                        <div class="d-flex overflow-hidden">
                            @if (!$isCurrentUser)
                                <div class="user-avatar flex-shrink-0 me-3">
                                    @include('livewire.administration.chatting.group.partials.avatar')
                                </div>
                            @endif

                            <div class="chat-message-wrapper flex-grow-1">
                                {{-- Show sender name for non-current user messages --}}
                                @if (!$isCurrentUser && ($key === 0 || $messages[$key - 1]->sender_id !== $message->sender_id))
                                    <div class="text-muted mt-0">
                                        <small>{{ $message->sender->name }}</small>
                                    </div>
                                @endif

                                {{-- Message Content --}}
                                @include('livewire.administration.chatting.group.partials.message-content')

                                {{-- Message Actions --}}
                                @include('livewire.administration.chatting.group.partials.message-actions')
                            </div>

                            @if ($isCurrentUser)
                                <div class="user-avatar flex-shrink-0 ms-3">
                                    @include('livewire.administration.chatting.group.partials.avatar')
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Chat message form -->
        @include('livewire.administration.chatting.group.partials.chat-message-form')

        {{-- Include Chat Scripts as blade file --}}
        @include('livewire.administration.chatting.partials.chat-scripts')
    </div>
</div>
