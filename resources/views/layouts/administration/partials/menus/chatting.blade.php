@hasanyrole(['Developer', 'Super Admin'])
    <li class="menu-item {{ request()->is('chatting/one-to-one*') ? 'active' : '' }}">
        <a href="{{ route('administration.chatting.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-message"></i>
            <div data-i18n="Chattings">{{ __('Chattings') }}</div>
            @if (get_total_unread_private_messages_count() > 0)
                <div class="badge bg-danger rounded-pill ms-auto">{{ get_total_unread_private_messages_count() }}</div>
            @endif
        </a>
    </li>
    
    <li class="menu-item {{ request()->is('chatting/group*') ? 'active' : '' }}">
        <a href="{{ route('administration.chatting.group.index') }}" class="menu-link">
            <i class="menu-icon tf-icons ti ti-messages"></i>
            <div data-i18n="Group Chattings">{{ __('Group Chattings') }}</div>
        </a>
    </li>
@endhasanyrole
