<!-- Task Management -->
@canany(['Task Everything', 'Task Create', 'Task Read'])
<li class="menu-item {{ request()->is('task*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-brand-stackshare"></i>
        <div data-i18n="Task">{{ __('Task') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['Task Everything'])
            <li class="menu-item {{ request()->is('task/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.task.index') }}" class="menu-link">{{ __('All Tasks') }}</a>
            </li>
        @endcanany

        @canany(['Task Everything'])
            <li class="menu-item {{ request()->is('task/kanban*') ? 'active' : '' }}">
                <a href="{{ route('administration.task.index.kanban') }}" class="menu-link">{{ __('All Tasks Board') }}</a>
            </li>
        @endcanany
        @canany(['Task Everything'])
            <li class="menu-item {{ request()->is('task/sprint*') ? 'active' : '' }}">
                <a href="{{ route('administration.task.index.sprint') }}" class="menu-link">{{ __('All Tasks Board Sprint') }}</a>
            </li>
        @endcanany
        @can('Task Read')
            <li class="menu-item {{ request()->is('task/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.task.my') }}" class="menu-link">{{ __('My Tasks') }}</a>
            </li>
        @endcan
        @can('Task Create')
            <li class="menu-item {{ request()->is('task/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.task.create') }}" class="menu-link">{{ __('New Task') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
