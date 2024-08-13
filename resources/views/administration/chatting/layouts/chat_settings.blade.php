<div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left">
    <div class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <div class="avatar avatar-xl avatar-online">
            @if (auth()->user()->hasMedia('avatar'))
                <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
            @else
                <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
            @endif
        </div>
        <h5 class="mt-2 mb-0">{{ auth()->user()->name }}</h5>
        <span>{{ auth()->user()->roles[0]->name }}</span>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <div class="my-4">
            <small class="text-muted text-uppercase">Status</small>
            <div class="d-grid gap-2 mt-3">
                <div class="form-check form-check-success">
                    <input name="chat-user-status" class="form-check-input" type="radio" value="active" id="user-active" checked />
                    <label class="form-check-label" for="user-active">Active</label>
                </div>
                <div class="form-check form-check-warning">
                    <input name="chat-user-status" class="form-check-input" type="radio" value="away" id="user-away" />
                    <label class="form-check-label" for="user-away">Away</label>
                </div>
                <div class="form-check form-check-secondary">
                    <input name="chat-user-status" class="form-check-input" type="radio" value="offline" id="user-offline" />
                    <label class="form-check-label" for="user-offline">Offline</label>
                </div>
            </div>
        </div>
        <div class="my-4">
            <small class="text-muted text-uppercase">Settings</small>
            <ul class="list-unstyled d-grid gap-2 me-3 mt-3">
                <li class="d-flex justify-content-between align-items-center">
                    <div>
                        <i class="ti ti-bell me-1 ti-sm"></i>
                        <span class="align-middle">Notification</span>
                    </div>
                    <label class="switch switch-primary me-4 switch-sm">
                        <input type="checkbox" class="switch-input" checked/>
                        <span class="switch-toggle-slider">
                            <span class="switch-on"></span>
                            <span class="switch-off"></span>
                        </span>
                    </label>
                </li>
            </ul>
        </div>
        <div class="d-flex mt-4">
            <button class="btn btn-primary btn-block" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left">
                Save Chat Settings
            </button>
        </div>
    </div>
</div>