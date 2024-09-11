<div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end" id="app-chat-contacts">
    <div class="sidebar-header">
        <div class="d-flex align-items-center me-3 me-lg-0">
            <div class="flex-shrink-0 avatar avatar-online me-3" data-bs-toggle="sidebar" data-overlay="app-overlay-ex" data-target="#app-chat-sidebar-left">
                @if (auth()->user()->hasMedia('avatar'))
                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="user-avatar rounded-circle cursor-pointer" width="40">
                @else
                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="user-avatar rounded-circle cursor-pointer" width="40">
                @endif
            </div>
            <div class="flex-grow-1 input-group input-group-merge rounded-pill">
                <span class="input-group-text" id="basic-addon-search31"><i class="ti ti-search"></i></span>
                <input type="text" class="form-control chat-search-input" id="chat-search-input" placeholder="Search..." aria-label="Search..." aria-describedby="basic-addon-search31" />
            </div>
        </div>
        <i class="ti ti-x cursor-pointer d-lg-none d-block position-absolute mt-2 me-1 top-0 end-0" data-overlay data-bs-toggle="sidebar" data-target="#app-chat-contacts"></i>
    </div>
    <hr class="container-m-nx m-0" />
    <div class="sidebar-body" style="overflow-y: scroll;">
        {{-- <!-- Chats --> --}}
        <div class="chat-contact-list-item-title">
            <h5 class="text-primary mb-0 px-4 pt-3 pb-2">Chats</h5>
        </div>
        <ul class="list-unstyled chat-contact-list" id="chat-list">
            @forelse ($chatUsers as $user)
                <li class="chat-contact-list-item position-relative {{ (isset($activeUser) && $activeUser === $user->id) ? 'active' : '' }}">
                    <a href="{{ route('administration.chatting.show', ['user' => $user, 'userid' => $user->userid]) }}" class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar avatar-online">
                            @if ($user->hasMedia('avatar'))
                                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle cursor-pointer" width="40">
                            @else
                                <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle cursor-pointer" width="40">
                            @endif
                        </div>
                        <div class="chat-contact-info flex-grow-1 ms-2">
                            <h6 class="chat-contact-name text-truncate m-0">{{ $user->name }}</h6>
                            @if (get_receiver_last_message($user->id))
                                <small class="text-muted mb-auto float-start">{{ show_content(get_receiver_last_message($user->id)->message, 15) }}</small>
                                <small class="text-muted mb-auto float-end">{{ date_time_ago(get_receiver_last_message($user->id)->created_at) }}</small>
                            @endif
                        </div>
                        @if (get_receiver_unread_messages_count($user->id) > 0)
                            <small class="mb-auto badge bg-danger rounded-pill total-unread-message position-absolute" style="padding: 5px 7px; font-size: 10px; right: 0; top: 8px;">{{ get_receiver_unread_messages_count($user->id) }}</small>
                        @endif
                    </a>
                </li>
            @empty
                <li class="chat-contact-list-item chat-list-item-0 d-none">
                    <h6 class="text-muted mb-0">No Chats Found</h6>
                </li>
            @endforelse
        </ul>

        {{-- <!-- Contacts --> --}}
        <ul class="list-unstyled chat-contact-list mb-0" id="contact-list">
            <li class="chat-contact-list-item chat-contact-list-item-title">
                <h5 class="text-primary mb-0">Contacts</h5>
            </li>
            @forelse ($contacts as $contact) 
                <li class="chat-contact-list-item {{ (isset($activeUser) && $activeUser === $contact->id) ? 'active' : '' }}">
                    <a href="{{ route('administration.chatting.show', ['user' => $contact, 'userid' => $contact->userid]) }}" class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar avatar-offline">
                            @if ($contact->hasMedia('avatar'))
                                <img src="{{ $contact->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle cursor-pointer" width="40">
                            @else
                                <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle cursor-pointer" width="40">
                            @endif
                        </div>
                        <div class="chat-contact-info flex-grow-1 ms-2">
                            <h6 class="chat-contact-name text-truncate m-0">{{ $contact->name }}</h6>
                            <p class="chat-contact-status text-muted text-truncate mb-0">{{ $contact->roles[0]->name }}</p>
                        </div>
                    </a>
                </li>
            @empty 
                <li class="chat-contact-list-item contact-list-item-0 d-none">
                    <h6 class="text-muted mb-0">No Contacts Found</h6>
                </li>
            @endforelse
        </ul>
    </div>
</div>



{{-- Search Chat User --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var searchInput = document.getElementById('chat-search-input');
        
        searchInput.addEventListener('keyup', function() {
            var searchTerm = searchInput.value.toLowerCase();
            var searchTerms = searchTerm.split(' ');
            var contactItems = document.querySelectorAll('.chat-contact-list-item');
            
            contactItems.forEach(function(item) {
                var itemText = item.textContent.toLowerCase();
                var isMatch = searchTerms.every(function(term) {
                    return itemText.indexOf(term) > -1;
                });
                
                if (isMatch) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
</script>