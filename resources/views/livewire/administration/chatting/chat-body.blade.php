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
                            <span class="avatar-initial rounded-circle bg-dark border border-1" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right">
                                {{ substr($receiver->name, 0, 1) }}
                            </span>
                        @endif
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-2">
                        <h6 class="m-0">{{ get_employee_name($receiver) }}</h6>
                        <small class="user-status text-muted">{{ $receiver->role->name }}</small>
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
            <ul class="list-unstyled chat-history">
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
                                <div class="chat-message-wrapper flex-grow-1">
                                    @if (!is_null($message->message))
                                        <small class="text-muted d-block text-right">{{ show_time($message->created_at) }}</small>
                                        <div class="chat-message-text position-relative">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
                                        @isset ($message->task)
                                            <small class="float-left pt-1" title="Show Related Task">
                                                <a href="{{ route('administration.task.show', ['task' => $message->task, 'taskid' => $message->task->taskid]) }}" target="_blank" class="text-bold text-dark">Show Task</a>
                                            </small>
                                        @else
                                            @can ('Task Create')
                                                <small class="float-left pt-1">
                                                    <a href="javascript:void(0);" class="text-bold" wire:click="$set('replyToMessageId', {{ $message->id }})" title="Reply" style="margin-right: 10px;">
                                                        <i class="ti ti-arrow-back-up fs-4"></i>
                                                    </a>
                                                    <a href="{{ route('administration.task.create.chat.task', ['message' => $message]) }}" target="_blank" class="text-bold" title="Create New Task">
                                                        <i class="ti ti-brand-stackshare"></i>
                                                    </a>
                                                </small>
                                            @endcan
                                        @endisset
                                    @endif
                                    @if (!is_null($message->file))
                                        @php
                                            $fileExtension = pathinfo($message->file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $fileName = pathinfo($message->file, PATHINFO_BASENAME);
                                        @endphp

                                        @if ($isImage)
                                            <div class="chat-message-image mt-1" style="width: 170px;">
                                                <a href="{{ asset('storage/' . $message->file) }}" class="image-link" target="_blank">
                                                    <img src="{{ asset('storage/' . $message->file) }}" class="img-responsive img-thumbnail">
                                                </a>
                                                <small class="d-block text-muted mt-1">{{ $fileName }}</small>
                                            </div>
                                        @else
                                            <a href="{{ asset('storage/' . $message->file) }}" class="chat-message-text card h-100" target="_blank">
                                                <div class="card-body text-center">
                                                    <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file-download ti-lg"></i></div>
                                                    <p class="mb-0">{{ $fileName }}</p>
                                                </div>
                                            </a>
                                        @endif
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
                                <div class="chat-message-wrapper flex-grow-1">
                                    @if (!is_null($message->message))
                                        <small class="text-muted d-block text-left">{{ show_time($message->created_at) }}</small>
                                        <div class="chat-message-text position-relative">
                                            <p class="mb-0">{!! $message->message !!}</p>
                                        </div>
                                        @isset ($message->task)
                                            <small class="float-right pt-1" title="Show Related Task">
                                                <a href="{{ route('administration.task.show', ['task' => $message->task, 'taskid' => $message->task->taskid]) }}" target="_blank" class="text-bold text-dark">Show Task</a>
                                            </small>
                                        @else
                                            @can ('Task Create')
                                                <small class="float-right pt-1">
                                                    <a href="{{ route('administration.task.create.chat.task', ['message' => $message]) }}" target="_blank" class="text-bold" title="Create New Task" style="margin-right: 10px;">
                                                        <i class="ti ti-brand-stackshare"></i>
                                                    </a>
                                                    <a href="javascript:void(0);" class="text-bold" wire:click="$set('replyToMessageId', {{ $message->id }})" title="Reply">
                                                        <i class="ti ti-arrow-back-up fs-4"></i>
                                                    </a>
                                                </small>
                                            @endcan
                                        @endisset
                                    @endif
                                    @if (!is_null($message->file))
                                        @php
                                            $fileExtension = pathinfo($message->file, PATHINFO_EXTENSION);
                                            $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $fileName = pathinfo($message->file, PATHINFO_BASENAME);
                                        @endphp

                                        @if ($isImage)
                                            <div class="chat-message-image mt-1" style="width: 170px;">
                                                <a href="{{ asset('storage/' . $message->file) }}" class="image-link" target="_blank">
                                                    <img src="{{ asset('storage/' . $message->file) }}" class="img-responsive img-thumbnail">
                                                </a>
                                                <small class="d-block text-muted mt-1">{{ $fileName }}</small>
                                            </div>
                                        @else
                                            <a href="{{ asset('storage/' . $message->file) }}" class="chat-message-text card h-100" target="_blank">
                                                <div class="card-body text-center">
                                                    <div class="badge rounded p-2 bg-label-dark mb-2"><i class="ti ti-file-download ti-lg"></i></div>
                                                    <p class="mb-0">{{ $fileName }}</p>
                                                </div>
                                            </a>
                                        @endif
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
            @if($replyToMessage)
            <div class="reply-to-message bg-light p-2 mb-2 border-start border-3 border-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Replying to</small>
                        <p class="mb-0 text-truncate" style="max-width: 250px;">
                            @if(is_object($replyToMessage) && $replyToMessage->message)
                                {{ $replyToMessage->message }}
                            @else
                                Message
                            @endif
                        </p>
                    </div>
                    <button type="button" class="btn btn-sm text-danger" wire:click="$set('replyToMessageId', null)">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="sendMessage" class="form-send-message d-flex justify-content-between align-items-center" enctype="multipart/form-data">
                @csrf
                <textarea
                    wire:model="newMessage"
                    class="form-control message-input border-0 me-3 shadow-none"
                    placeholder="Type your message here"
                    rows="1"
                    x-data="{}"
                    x-on:keydown.enter.prevent="
                        if (!$event.shiftKey) {
                            $event.preventDefault();
                            $wire.sendMessage();
                        }
                    "
                ></textarea>
                <div class="message-actions d-flex align-items-center">
                    <label for="attach-doc" class="form-label mb-0 me-2" title="Upload File">
                        <i class="ti ti-paperclip ti-sm cursor-pointer"></i>
                        <input type="file" id="attach-doc" wire:model="file" hidden accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip" />
                    </label>
                    <button type="submit" class="btn btn-primary d-flex send-msg-btn">
                        <i class="ti ti-send me-md-1 me-0"></i>
                        <span class="align-middle d-md-inline-block d-none">Send</span>
                    </button>
                </div>
            </form>

            @if($file)
            <div class="selected-file mt-2 p-2 bg-light rounded">
                <div class="d-flex justify-content-between align-items-center">
                    <span><i class="ti ti-paperclip me-1"></i> {{ $file->getClientOriginalName() }}</span>
                    <button type="button" class="btn btn-sm text-danger" wire:click="$set('file', null)">
                        <i class="ti ti-x"></i>
                    </button>
                </div>
            </div>
            @endif
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto-resize textarea
                const textarea = document.querySelector('.message-input');
                if (textarea) {
                    textarea.addEventListener('input', function() {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                    });
                }

                // Scroll to bottom of chat
                const scrollToBottom = function() {
                    const chatHistory = document.querySelector('.chat-history-body');
                    if (chatHistory) {
                        chatHistory.scrollTop = chatHistory.scrollHeight;
                    }
                };

                scrollToBottom();

                // Re-initialize event listeners after Livewire updates
                document.addEventListener('livewire:load', function() {
                    Livewire.hook('message.processed', (message, component) => {
                        scrollToBottom();
                    });
                });
            });
        </script>
    </div>
</div>
