<div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
    <div class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <div class="avatar avatar-xl avatar-online">
            @if ($user->hasMedia('avatar'))
                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle" width="40">
            @else
                <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="rounded-circle" width="40">
            @endif
        </div>
        <h6 class="mt-2 mb-0">{{ $user->name }}</h6>
        <span>{{ $user->roles[0]->name }}</span>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar d-block" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <div class="my-4">
            <small class="text-muted text-uppercase">Personal Information</small>
            <ul class="list-unstyled d-grid gap-2 mt-3">
                <li class="d-flex align-items-center">
                    <i class="ti ti-mail ti-sm"></i>
                    <span class="align-middle ms-2">{{ $user->email }}</span>
                </li>
                <li class="d-flex align-items-center">
                    <i class="ti ti-phone-call ti-sm"></i>
                    <span class="align-middle ms-2">+1(123) 456 - 7890</span>
                </li>
                <li class="d-flex align-items-center">
                    <i class="ti ti-clock ti-sm"></i>
                    <span class="align-middle ms-2">
                        {{ show_time(optional($user->current_shift)->start_time) }}
                        <small>to</small>
                        {{ show_time(optional($user->current_shift)->end_time) }}
                    </span>
                </li>
            </ul>
        </div>
    </div>
</div>