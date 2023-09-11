<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('administration.dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset('Logo/logo_black_01.png') }}" width="90%">
            </span>
            <span class="app-brand-text demo menu-text fw-bold">{{ config('app.name') }}</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
            <i class="ti menu-toggle-icon d-none d-xl-block ti-sm align-middle"></i>
            <i class="ti ti-x d-block d-xl-none ti-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->is('administration/dashboard*') ? 'active' : '' }}">
            <a href="{{ route('administration.dashboard.index') }}" class="menu-link">
                <i class="menu-icon tf-icons ti ti-smart-home"></i>
                <div data-i18n="Dashboard">Dashboard</div>
            </a>
        </li>

        <!-- Apps & Pages -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Apps & Pages</span>
        </li>
        
        <!-- Menu start -->
        <li class="menu-item {{ request()->is('administration/menus*') ? 'active open' : '' }}">
            <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons ti ti-book"></i>
                <div data-i18n="Menu">Menu</div>
            </a>
            <ul class="menu-sub">
                <li class="menu-item {{ request()->is('administration/menus/menu1*') ? 'active' : '' }}">
                    <a href="#" class="menu-link">
                        <div data-i18n="Menu1">Menu1</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</aside>
