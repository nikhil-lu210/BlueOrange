<div class="col app-chat-contacts app-sidebar flex-grow-0 overflow-hidden border-end" id="app-chat-contacts">
    <div class="sidebar-header">
        <div class="d-flex align-items-center me-3 me-lg-0">
            <div class="flex-shrink-0 avatar me-3" data-bs-toggle="sidebar" data-overlay="app-overlay-ex" data-target="#app-chat-sidebar-left" title="{{ __('Create New Group') }}">
                <span class="avatar-initial rounded-circle bg-primary">
                    <i class="ti ti-plus"></i>
                </span>
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
        {{-- <!-- Chatting Groups --> --}}
        <div class="chat-contact-list-item-title">
            <h5 class="text-primary mb-0 px-4 pt-3 pb-2">Chatting Groups</h5>
        </div>
        <ul class="list-unstyled chat-contact-list mb-0" id="contact-list">
            @forelse ($chatGroups as $group)
                <li class="chat-contact-list-item {{ (isset($activeGroup) && $activeGroup === $group->id) ? 'active' : '' }}">
                    <a href="{{ route('administration.chatting.group.show', ['group' => $group, 'groupid' => $group->groupid]) }}" class="d-flex align-items-center">
                        <div class="flex-shrink-0 avatar">
                            @php
                                $colors = ['success', 'primary', 'info', 'danger', 'warning'];
                                $randomColor = $colors[array_rand($colors)];
                            @endphp
                            <span class="avatar-initial rounded-circle bg-label-{{ $randomColor }} border border-1">
                                {{ substr($group->name, 0, 1) }}
                            </span>
                        </div>
                        <div class="chat-contact-info flex-grow-1 ms-2">
                            <h6 class="chat-contact-name text-truncate m-0">{{ $group->name }}</h6>
                            <small class="chat-contact-status text-muted text-truncate mb-0"><b>Creator:</b> {{ $group->creator->alias_name }}</small>
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
