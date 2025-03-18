<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('administration.dashboard.index') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
                <img src="{{ asset(config('app.logo')) }}" width="90%">
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
        @include('layouts.administration.partials.menus.dashboard')

        @include('layouts.administration.partials.menus.chatting')

        @include('layouts.administration.partials.menus.vault')

        @include('layouts.administration.partials.menus.attendance')

        @include('layouts.administration.partials.menus.daily_break')

        @include('layouts.administration.partials.menus.leave')

        @include('layouts.administration.partials.menus.announcement')

        @include('layouts.administration.partials.menus.task')

        @include('layouts.administration.partials.menus.daily_work_update')

        @include('layouts.administration.partials.menus.it_ticket')

        @include('layouts.administration.partials.menus.booking')

        <!-- Accounts -->
        <li class="menu-header small text-uppercase">
            <span class="menu-header-text">{{ __('Accounts') }}</span>
        </li>

        @include('layouts.administration.partials.menus.salary')

        @include('layouts.administration.partials.menus.income_expense')

        @include('layouts.administration.partials.menus.logs')

        @include('layouts.administration.partials.menus.settings')
    </ul>
</aside>
