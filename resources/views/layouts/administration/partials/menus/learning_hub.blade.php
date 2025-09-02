<!-- Learning Hub Management -->
@canany(['Learning Hub Everything', 'Learning Hub Create', 'Learning Hub Read'])
<li class="menu-item {{ request()->is('learning_hub*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-book"></i>
        <div data-i18n="Learning Hub">{{ __('Learning Hub') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Learning Hub Everything'])
            <li class="menu-item {{ request()->is('learning_hub/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.learning_hub.index') }}" class="menu-link">{{ __('All Topics') }}</a>
            </li>
        @endcanany
        @can('Learning Hub Read')
            <li class="menu-item {{ request()->is('learning_hub/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.learning_hub.my') }}" class="menu-link">{{ __('My Topics') }}</a>
            </li>
        @endcan
        @can('Learning Hub Create')
            <li class="menu-item {{ request()->is('learning_hub/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.learning_hub.create') }}" class="menu-link">{{ __('New Topic') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
