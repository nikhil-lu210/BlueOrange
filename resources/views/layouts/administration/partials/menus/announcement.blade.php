<!-- Announcement Management -->
@canany(['Announcement Everything', 'Announcement Create', 'Announcement Read'])
<li class="menu-item {{ request()->is('announcement*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-speakerphone"></i>
        <div data-i18n="Announcement">{{ ___('Announcement') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Announcement Everything'])
            <li class="menu-item {{ request()->is('announcement/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.announcement.index') }}" class="menu-link">{{ ___('All Announcements') }}</a>
            </li>
        @endcanany
        @can('Announcement Read')
            <li class="menu-item {{ request()->is('announcement/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.announcement.my') }}" class="menu-link">{{ ___('My Announcements') }}</a>
            </li>
        @endcan
        @can('Announcement Create')
            <li class="menu-item {{ request()->is('announcement/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.announcement.create') }}" class="menu-link">{{ ___('New Announcement') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
