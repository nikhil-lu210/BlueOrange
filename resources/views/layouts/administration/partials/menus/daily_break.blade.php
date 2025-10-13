<!-- Daily Break Management -->
@canany(['Daily Break Create', 'Daily Break Read'])
<li class="menu-item {{ request()->is('daily_break*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-clock-play"></i>
        <div data-i18n="Daily Break">{{ ___('Daily Break') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Daily Break Update', 'Daily Break Delete'])
            <li class="menu-item {{ request()->is('daily_break/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_break.index') }}" class="menu-link">{{ ___('All Daily Breaks') }}</a>
            </li>
        @endcanany
        @can('Daily Break Read')
            <li class="menu-item {{ request()->is('daily_break/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_break.my') }}" class="menu-link">{{ ___('My Daily Breaks') }}</a>
            </li>
        @endcan
        @can('Daily Break Create')
            <li class="menu-item {{ request()->is('daily_break/start_stop*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_break.create') }}" class="menu-link">{{ ___('Start/Stop Break') }}</a>
            </li>

            <li class="menu-item {{ request()->is('daily_break/barcode*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_break.barcode.scanner') }}" class="menu-link">{{ ___('Bar Code Break') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
