@extends('administration.chatting.index')

@section('chat_body')

<div class="col app-chat-history bg-body">
    <div class="chat-history-wrapper">
        <div class="chat-history-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex overflow-hidden align-items-center">
                    <i class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-contacts"></i>
                    <div class="flex-shrink-0 avatar">
                        @if ($user->hasMedia('avatar'))
                            <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" width="40">
                        @else
                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right" width="40">
                        @endif
                    </div>
                    <div class="chat-contact-info flex-grow-1 ms-2">
                        <h6 class="m-0">{{ $user->name }}</h6>
                        <small class="user-status text-muted">{{ $user->roles[0]->name }}</small>
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
        <div class="chat-history-body bg-body">
            <ul class="list-unstyled chat-history">
                <li class="chat-message chat-message-right">
                    <div class="d-flex overflow-hidden">
                        <div class="chat-message-wrapper flex-grow-1">
                            <div class="chat-message-text">
                                <p class="mb-0">How can we help? We're here for you! 😄</p>
                            </div>
                            <div class="text-end text-muted mt-1">
                                <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                <small>10:00 AM</small>
                            </div>
                        </div>
                        <div class="user-avatar flex-shrink-0 ms-3">
                            <div class="avatar avatar-sm">
                                <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />
                            </div>
                        </div>
                    </div>
                </li>
                <li class="chat-message">
                    <div class="d-flex overflow-hidden">
                        <div class="user-avatar flex-shrink-0 me-3">
                            <div class="avatar avatar-sm">
                                <img src="../../assets/img/avatars/2.png" alt="Avatar" class="rounded-circle" />
                            </div>
                        </div>
                        <div class="chat-message-wrapper flex-grow-1">
                            <div class="chat-message-text">
                                <p class="mb-0">Hey John, I am looking for the best admin template.</p>
                                <p class="mb-0">Could you please help me to find it out? 🤔</p>
                            </div>
                            <div class="chat-message-text mt-2">
                                <p class="mb-0">It should be Bootstrap 5 compatible.</p>
                            </div>
                            <div class="text-muted mt-1">
                                <small>10:02 AM</small>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Chat message form -->
        <div class="chat-history-footer shadow-sm">
            <form id="sendMessage" class="form-send-message d-flex justify-content-between align-items-center">
                <input class="form-control message-input border-0 me-3 shadow-none" placeholder="Type your message here" />
                <div class="message-actions d-flex align-items-center">
                    <label for="attach-doc" class="form-label mb-0" title="Upload File">
                        <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                        <input type="file" id="attach-doc" hidden />
                    </label>
                    <button class="btn btn-primary d-flex send-msg-btn">
                        <i class="ti ti-send me-md-1 me-0"></i>
                        <span class="align-middle d-md-inline-block d-none">Send</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>




<!-- Sidebar Right -->
@include('administration.chatting.layouts.chat_contact_details')
<!-- /Sidebar Right -->
@endsection