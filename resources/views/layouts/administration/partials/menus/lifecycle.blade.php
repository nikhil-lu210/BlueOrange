<!-- Task Management -->
@canany(['LifeCycle Everything', 'LifeCycle Create', 'LifeCycle Read'])
<li class="menu-item {{ request()->is('LifeCycle*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-recycle"></i>
        <div data-i18n="LifeCycle">{{ __('Employee LifeCycle Management') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['LifeCycle Everything'])
            <li class="menu-item {{ request()->is('lifecycle/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.index') }}" class="menu-link">{{ __('All LifeCycle') }}</a>
            </li>
        @endcanany

        @can('LifeCycle Read')
            <li class="menu-item {{ request()->is('lifecycle/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.my') }}" class="menu-link">{{ __('My LifeCycle') }}</a>
            </li>
        @endcan
        @can('LifeCycle Create')
            <li class="menu-item {{ request()->is('lifecycle/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.lifecycle.create') }}" class="menu-link">{{ __('New LifeCycle') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany