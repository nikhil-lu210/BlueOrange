<div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
    <div class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <div class="avatar avatar-xl">
            <span class="avatar-initial rounded-circle bg-dark border border-1">
                {{ substr($group->name, 0, 1) }}
            </span>
        </div>
        <h6 class="mt-2 mb-0">{{ $group->name }}</h6>
        <small class="text-muted">{{ show_date($group->created_at) }}</small>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar d-block" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-right"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <div class="my-4">
            <small class="text-muted text-uppercase">Personal Information</small>
            <ul class="list-unstyled d-grid gap-2 mt-3">
                <li class="d-flex align-items-center">
                    <i class="ti ti-mail ti-sm"></i>
                    <span class="align-middle ms-2">Lorem, ipsum dolor.</span>
                </li>
                <li class="d-flex align-items-center">
                    <i class="ti ti-phone-call ti-sm"></i>
                    <span class="align-middle ms-2">+1(123) 456 - 7890</span>
                </li>
                <li class="d-flex align-items-center">
                    <i class="ti ti-clock ti-sm"></i>
                    <span class="align-middle ms-2">Lorem, ipsum.</span>
                </li>
            </ul>
        </div>
    </div>
</div>