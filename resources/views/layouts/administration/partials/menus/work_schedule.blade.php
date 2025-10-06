<!-- Work Schedule Management -->
@canany(['User Everything', 'User Create', 'User Read'])
<li class="menu-item {{ request()->is('work_schedule*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-calendar-time"></i>
        <div data-i18n="Work Schedule">{{ ___('Work Schedule') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['User Everything', 'User Read'])
            <li class="menu-item {{ request()->is('work_schedule/report*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.report') }}" class="menu-link">{{ ___('Graph Report') }}</a>
            </li>
        @endcanany
        @canany(['User Everything', 'User Read'])
            <li class="menu-item {{ request()->is('work_schedule') && !request()->is('work_schedule/*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.index') }}" class="menu-link">{{ ___('All Schedules') }}</a>
            </li>
        @endcanany
        @can('User Create')
            <li class="menu-item {{ request()->is('work_schedule/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.work_schedule.create') }}" class="menu-link">{{ ___('Assign Schedule') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
