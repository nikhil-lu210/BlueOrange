<!-- Recognition Management -->
@canany(['Recognition Everything', 'Recognition Create', 'Recognition Read'])
<li class="menu-item {{ request()->is('recognition*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-award"></i>
        <div data-i18n="Recognition">{{ ___('Recognition') }}</div>
    </a>
    <ul class="menu-sub">
        @can('Recognition Read')
            <li class="menu-item {{ request()->is('recognition/analytics*') ? 'active' : '' }}">
                <a href="{{ route('administration.recognition.analytics') }}" class="menu-link">{{ ___('Analytics') }}</a>
            </li>
        @endcan
        @can('Recognition Read')
            <li class="menu-item {{ request()->is('recognition/leaderboard*') ? 'active' : '' }}">
                <a href="{{ route('administration.recognition.leaderboard') }}" class="menu-link">{{ ___('Leaderboard') }}</a>
            </li>
        @endcan
        @canany(['Recognition Everything'])
            <li class="menu-item {{ request()->is('recognition/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.recognition.index') }}" class="menu-link">{{ ___('All Recognitions') }}</a>
            </li>
        @endcanany
        @can('Recognition Read')
            <li class="menu-item {{ request()->is('recognition/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.recognition.my') }}" class="menu-link">{{ ___('My Recognitions') }}</a>
            </li>
        @endcan
        @can('Recognition Create')
            <li class="menu-item {{ request()->is('recognition/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.recognition.create') }}" class="menu-link">{{ ___('New Recognition') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
