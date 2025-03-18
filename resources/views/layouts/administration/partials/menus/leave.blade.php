<!-- Leave History Management -->
@canany(['Leave History Create', 'Leave History Read'])
<li class="menu-item {{ request()->is('leave*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-calendar-pause"></i>
        <div data-i18n="Leave History">{{ __('Leave') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Leave History Update', 'Leave History Delete'])
            <li class="menu-item {{ request()->is('leave/history/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.leave.history.index') }}" class="menu-link">{{ __('All Leaves') }}</a>
            </li>
        @endcanany
        @can('Leave History Read')
            <li class="menu-item {{ request()->is('leave/history/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.leave.history.my') }}" class="menu-link">{{ __('My Leaves') }}</a>
            </li>
        @endcan
        @can('Leave History Create')
            <li class="menu-item {{ request()->is('leave/history/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.leave.history.create') }}" class="menu-link">{{ __('Apply For Leave') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
