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

                    @if ($message->sender_id === auth()->user()->id)
                        <li class="chat-message chat-message-right">
                            <div class="d-flex overflow-hidden">
                                <div class="chat-message-wrapper flex-grow-1" data-bs-toggle="tooltip" title="{{ show_time($message->created_at) }}">
                                    @if (!is_null($message->message))
                                        <div class="chat-message-text">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
                                        @if ($message->readByUsers->count() > 0)
                                            <small>Seen by: {{ $message->readByUsers->pluck('employee.alias_name')->join(', ') }}</small>
                                        @endif
                                    @endif
                                    @if (!is_null($message->file))
                                        <a href="#" class="chat-message-text card h-100" target="_blank">
                                            <div class="card-body text-center">
                                              <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file ti-lg"></i></div>
                                              <p class="mb-0">Lorem consectetur.pdf</p>
                                            </div>
                                        </a>

                                        {{-- If File type is image --}}
                                        <div class="chat-message-image mt-1" style="width: 170px;" target="_blank">
                                            <img src="https://fakeimg.pl/300" class="img-responsive img-thumbnail image-link">
                                        </div>
                                    @endif
                                </div>
                                <div class="user-avatar flex-shrink-0 ms-3">
                                    <div class="avatar avatar-sm">
                                        @if ($key === 0 || $messages[$key - 1]->sender_id !== $message->sender_id)
                                            <img src="{{ $imageURL }}" alt="Avatar" class="rounded-circle" title="{{ $message->sender->name }}"/>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </li>
                    @else
                        <li class="chat-message">
                            <div class="d-flex overflow-hidden">
                                <div class="user-avatar flex-shrink-0 me-3">
                                    <div class="avatar avatar-sm">
                                        @if ($key === 0 || $messages[$key - 1]->sender_id !== $message->sender_id)
                                            <img src="{{ $imageURL }}" alt="Avatar" class="rounded-circle" title="{{ $message->sender->name }}"/>
                                        @endif
                                    </div>
                                </div>
                                <div class="chat-message-wrapper flex-grow-1" data-bs-toggle="tooltip" title="{{ show_time($message->created_at) }}">
                                    @if (!is_null($message->message))
                                        @if ($key === 0 || $messages[$key - 1]->sender_id !== $message->sender_id)
                                            <div class="text-muted mt-0">
                                                <small>{{ $message->sender->name }}</small>
                                            </div>
                                        @endif
                                        <div class="chat-message-text">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
                                        @if ($message->readByUsers->count() > 0)
                                            <small>Seen by: {{ $message->readByUsers->pluck('employee.alias_name')->join(', ') }}</small>
                                        @endif
                                    @endif
                                    @if (!is_null($message->file))
                                        <a href="#" class="chat-message-text card h-100" target="_blank">
                                            <div class="card-body text-center">
                                              <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file ti-lg"></i></div>
                                              <p class="mb-0">Lorem consectetur.pdf</p>
                                            </div>
                                        </a>

                                        {{-- If File type is image --}}
                                        <div class="chat-message-image mt-1" style="width: 170px;" target="_blank">
                                            <img src="https://fakeimg.pl/300" class="img-responsive img-thumbnail image-link">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>

        <!-- Chat message form -->
        <div class="chat-history-footer shadow-sm">
            <form wire:submit.prevent="sendMessage" class="form-send-message d-flex justify-content-between align-items-center" enctype="multipart/form-data">
                @csrf
                <textarea
                    wire:model="newMessage"
                    class="form-control message-input border-0 me-3 shadow-none"
                    placeholder="Type your message here (Shift+Enter for new line)"
                    rows="1"
                    x-data="{}"
                    x-on:keydown.enter="
                        if ($event.shiftKey) {
                            // Allow Shift+Enter to create a new line (default behavior)
                        } else {
                            // Submit form when Enter is pressed without Shift
                            $event.preventDefault();
                            $wire.sendMessage();
                        }
                    "
                ></textarea>
                <div class="message-actions d-flex align-items-center">
                    {{-- <label for="attach-doc" class="form-label mb-0" title="Upload File">
                        <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                        <input type="file" id="attach-doc" name="file" wire:model="file" hidden />
                    </label> --}}
                    <button type="submit" class="btn btn-primary d-flex send-msg-btn">
                        <i class="ti ti-send me-md-1 me-0"></i>
                        <span class="align-middle d-md-inline-block d-none">Send</span>
                    </button>
                </div>
            </form>
        </div>

        {{-- Include Chat Scripts as blade file --}}
        @include('livewire.administration.chatting.partials.chat-scripts')
    </div>
</div>
