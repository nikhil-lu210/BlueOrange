<div class="col app-chat-history bg-body">
    <div class="chat-history-wrapper">
        {{-- Chat Header --}}
        <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex overflow-hidden align-items-center">
                    <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                    <div class="flex-shrink-0 avatar">
                        @if ($receiver->hasMedia('avatar'))
                            <img src="{{ $receiver->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" width="40">
                        @else
                            <span class="avatar-initial rounded-circle bg-dark border border-1" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
                                {{ substr($receiver->alias_name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-2">
                        <h6 class="m-0">{{ $receiver->alias_name }}</h6>
                        <small class="user-status text-muted">{{ $receiver->role->name }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn p-0 me-3 chat-history-refresh" type="button" title="Refresh Messages">
                        <i class="ti ti-refresh"></i>
                    </button>
                    <div class="dropdown d-flex align-self-center">
                        <button class="btn p-0" type="button" id="chat-header-actions" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ti ti-dots-vertical"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                            <a class="dropdown-item confirm-danger" href="javascript:void(0);">Block Contact</a>
                            <a class="dropdown-item confirm-danger" href="javascript:void(0);">Clear Chat</a>
                            <a class="dropdown-item confirm-warning" href="javascript:void(0);">Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chat Messages --}}
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
                            $imageURL = "https://fakeimg.pl/300/dddddd/?text=" . $message->sender->alias_name;
                        }

                        // Check if this message is being replied to
                        $isBeingRepliedTo = $replyToMessageId == $message->id;

                        // Check if message is from current user
                        $isCurrentUser = $message->sender_id === auth()->user()->id;
                    @endphp

                    {{-- Date Divider if date changes --}}
                    @if ($currentDate !== $messageDate)
                        @php
                            $currentDate = $messageDate;
                        @endphp
                        <div class="divider divider-dotted">
                            <div class="divider-text">{{ $message->created_at->format('F j, Y') }}</div>
                        </div>
                    @endif

                    {{-- Message Item --}}
                    <li class="chat-message {{ $isCurrentUser ? 'chat-message-right' : '' }}">
                        <div class="d-flex overflow-hidden">
                            @if (!$isCurrentUser)
                                <div class="user-avatar flex-shrink-0 me-3">
                                    @include('livewire.administration.chatting.partials.avatar')
                                </div>
                            @endif

                            <div class="chat-message-wrapper flex-grow-1">
                                {{-- Message Content --}}
                                @include('livewire.administration.chatting.partials.message-content')

                                {{-- Message Actions --}}
                                @include('livewire.administration.chatting.partials.message-actions')
                            </div>

                            @if ($isCurrentUser)
                                <div class="user-avatar flex-shrink-0 ms-3">
                                    @include('livewire.administration.chatting.partials.avatar')
                                </div>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- Chat Message Form --}}
        @include('livewire.administration.chatting.partials.chat-message-form')

        {{-- Include Chat Scripts as blade file --}}
        @include('livewire.administration.chatting.partials.chat-scripts')
    </div>
</div>
