<div class="col app-chat-sidebar-right app-sidebar overflow-hidden" id="app-chat-sidebar-right">
    <div class="sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <div class="avatar avatar-xl">
            <span class="avatar-initial rounded-circle bg-dark border border-1">
                {{ substr($group->name, 0, 1) }}
            </span>
        </div>
        <h6 class="mt-2 mb-0">{{ $group->name }}</h6>
        <small class="text-muted">{{ show_date($group->created_at) }}</small>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar d-block" data-bs-toggle="sidebar" data-overlay
            data-target="#app-chat-sidebar-right"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <div class="my-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted text-uppercase">User List</small>
                <div class="d-inline-block">
                    @can ('Group Chatting Delete')
                        <a href="{{ route('administration.chatting.group.destroy', ['group' => $group, 'groupid' => $group->groupid]) }}" class="btn btn-danger btn-sm btn-icon waves-effect waves-light confirm-danger" title="Delete Group?">
                            <i class="ti ti-trash"></i>
                        </a>
                    @endcan

                    @can ('Group Chatting Create')
                        <a href="javascript:void(0);" class="btn btn-primary btn-sm btn-icon waves-effect waves-light" title="Add New Users" data-bs-toggle="modal" data-bs-target="#addGroupChattingUsersModal">
                            <i class="ti ti-plus"></i>
                        </a>
                    @endcan
                </div>
            </div>
            <div class="row">
                <!-- User List Style -->
                <div class="col-12 mb-4 mb-xl-0">
                    <div class="demo-inline-spacing mt-3">
                        <ul class="list-group">
                            @foreach ($group->group_users as $user)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="padding: 5px 10px;">
                                    {{ show_content($user->name, 17) }}
                                    @if ($group->creator->id != $user->id) 
                                        <a href="{{ route('administration.chatting.group.remove.user', ['group' => $group, 'user' => $user]) }}" class="text-bold text-danger confirm-danger" title="Remove {{ $user->name }}?">
                                            <i class="ti ti-x"></i>
                                        </a>
                                    @else
                                        <small class="badge bg-label-primary text-bold">Admin</small>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <!--/ User List Style -->
            </div>
        </div>
    </div>
</div>


{{-- Page Modal --}}
@include('administration.chatting.group.modals.add_group_chatting_users')