<div class="col app-chat-history bg-body">
    <div class="chat-history-wrapper">
        <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex overflow-hidden align-items-center">
                    <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                    <div class="flex-shrink-0 avatar">
                        @if ($receiver->hasMedia('avatar'))
                            <img src="{{ $receiver->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" width="40">
                        @else
                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" width="40">
                        @endif
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-2">
                        <h6 class="m-0">{{ $receiver->name }}</h6>
                        <small class="user-status text-muted">{{ $receiver->roles[0]->name }}</small>
                    </div>
                </div>
                <div class="d-flex align-items-center">
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
                                @if (!is_null($message->seen_at))
                                    <i class="ti ti-checks ti-xs me-1 text-success" data-bs-toggle="tooltip" title="Seen At: {{ show_time($message->seen_at) }}"></i>
                                @else 
                                    <i class="ti ti-check ti-xs me-1"></i>
                                @endif
                                <div class="chat-message-wrapper flex-grow-1" data-bs-toggle="tooltip" title="{{ show_time($message->created_at) }}">
                                    @if (!is_null($message->message)) 
                                        <div class="chat-message-text">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
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
                                            <img src="{{ $imageURL }}" alt="Avatar" class="rounded-circle" />
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
                                            <img src="{{ $imageURL }}" alt="Avatar" class="rounded-circle" />
                                        @endif
                                    </div>
                                </div>
                                <div class="chat-message-wrapper flex-grow-1" data-bs-toggle="tooltip" title="{{ show_time($message->created_at) }}">
                                    @if (!is_null($message->message)) 
                                        <div class="chat-message-text">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
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
                                @if (!is_null($message->seen_at))
                                    <i class="ti ti-checks ti-xs me-1 text-success" data-bs-toggle="tooltip" title="Seen At: {{ show_time($message->seen_at) }}"></i>
                                @else 
                                    <i class="ti ti-check ti-xs me-1"></i>
                                @endif
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
                <input wire:model="newMessage" class="form-control message-input border-0 me-3 shadow-none" placeholder="Type your message here" />
                <div class="message-actions d-flex align-items-center">
                    <label for="attach-doc" class="form-label mb-0" title="Upload File">
                        <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                        <input type="file" id="attach-doc" name="file" wire:model="file" hidden />
                    </label>
                    <button type="submit" class="btn btn-primary d-flex send-msg-btn">
                        <i class="ti ti-send me-md-1 me-0"></i>
                        <span class="align-middle d-md-inline-block d-none">Send</span>
                    </button>
                </div>
            </form>
        </div> 
    </div>
</div>