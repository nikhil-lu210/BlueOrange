<!-- Daily Work Update -->
@canany(['Daily Work Update Create', 'Daily Work Update Read'])
<li class="menu-item {{ request()->is('daily_work_update*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-device-imac-check"></i>
        <div data-i18n="Daily Work Update">{{ __('Daily Work Update') }}</div>
    </a>
    <ul class="menu-sub">
        @if(auth()->user()->hasAllPermissions(['Daily Work Update Create', 'Daily Work Update Update', 'Daily Work Update Delete']))
            <li class="menu-item {{ request()->is('daily_work_update/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_work_update.index') }}" class="menu-link">{{ __('All Work Updates') }}</a>
            </li>
        @endif
        @can('Daily Work Update Read')
            <li class="menu-item {{ request()->is('daily_work_update/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_work_update.my') }}" class="menu-link">{{ __('My Work Updates') }}</a>
            </li>
        @endcan
        @can('Daily Work Update Create')
            <li class="menu-item {{ request()->is('daily_work_update/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.daily_work_update.create') }}" class="menu-link">{{ __('New Work Update') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
