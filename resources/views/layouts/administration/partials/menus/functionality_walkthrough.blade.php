<!-- Functionality Walkthrough Management -->
@canany(['Functionality Walkthrough Everything', 'Functionality Walkthrough Create', 'Functionality Walkthrough Read'])
<li class="menu-item {{ request()->is('functionality_walkthrough*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-route"></i>
        <div data-i18n="Functionality Walkthrough">{{ __('Functionality Walkthrough') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Functionality Walkthrough Everything'])
            <li class="menu-item {{ request()->is('functionality_walkthrough/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.functionality_walkthrough.index') }}" class="menu-link">{{ __('All Walkthroughs') }}</a>
            </li>
        @endcanany
        @can('Functionality Walkthrough Read')
            <li class="menu-item {{ request()->is('functionality_walkthrough/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.functionality_walkthrough.my') }}" class="menu-link">{{ __('My Walkthroughs') }}</a>
            </li>
        @endcan
        @can('Functionality Walkthrough Create')
            <li class="menu-item {{ request()->is('functionality_walkthrough/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.functionality_walkthrough.create') }}" class="menu-link">{{ __('New Walkthrough') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
