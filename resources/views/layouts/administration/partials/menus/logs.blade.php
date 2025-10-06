<!-- Logs -->
@canany (['Logs Read'])
<li class="menu-header small text-uppercase">
    <span class="menu-header-text">{{ ___('Logs') }}</span>
</li>

<li class="menu-item {{ request()->is('logs*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-history"></i>
        <div data-i18n="Logs">{{ ___('Logs') }}</div>
    </a>
    <ul class="menu-sub">
        <li class="menu-item {{ request()->is('logs/login_logout_history*') ? 'active' : '' }}">
            <a href="{{ route('administration.logs.login_logout_history.index') }}" class="menu-link">{{ ___('Login Histories') }}</a>
        </li>
    </ul>
</li>
@endcanany
