<li class="menu-item {{ request()->is('dashboard*') ? 'active' : '' }}">
    <a href="{{ route('administration.dashboard.index') }}" class="menu-link">
        <i class="menu-icon tf-icons ti ti-smart-home"></i>
        <div data-i18n="Dashboard">{{ ___('Dashboard') }}</div>
    </a>
</li>
