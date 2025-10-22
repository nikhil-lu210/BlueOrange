<!-- Suggestion Management -->
@canany(['Suggestion Everything', 'Suggestion Create', 'Suggestion Read'])
<li class="menu-item {{ request()->is('suggestion*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-exclamation-circle"></i>
        <div data-i18n="Suggestion">{{ ___('Suggestion') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Suggestion Everything'])
            <li class="menu-item {{ request()->is('suggestion/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.suggestion.index') }}" class="menu-link">{{ ___('All Suggestions') }}</a>
            </li>
        @endcanany
        @can('Suggestion Read')
            <li class="menu-item {{ request()->is('suggestion/my/suggestion*') ? 'active' : '' }}">
                <a href="{{ route('administration.suggestion.my') }}" class="menu-link">{{ ___('My Suggestions') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
