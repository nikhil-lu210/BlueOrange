<!-- IT Ticket Management -->
@canany(['IT Ticket Everything', 'IT Ticket Create', 'IT Ticket Read'])
<li class="menu-item {{ request()->is('ticket/it_ticket*') ? 'active open' : '' }}">
    <a href="javascript:void(0);" class="menu-link menu-toggle">
        <i class="menu-icon tf-icons ti ti-ticket"></i>
        <div data-i18n="IT Ticket">{{ ___('IT Ticket') }}</div>
    </a>
    <ul class="menu-sub">
        @canany(['IT Ticket Everything'])
            <li class="menu-item {{ request()->is('ticket/it_ticket/all*') ? 'active' : '' }}">
                <a href="{{ route('administration.ticket.it_ticket.index') }}" class="menu-link">{{ ___('All Tickets') }}</a>
            </li>
        @endcanany
        @canany(['IT Ticket Create', 'IT Ticket Read'])
            <li class="menu-item {{ request()->is('ticket/it_ticket/my*') ? 'active' : '' }}">
                <a href="{{ route('administration.ticket.it_ticket.my') }}" class="menu-link">{{ ___('My Tickets') }}</a>
            </li>
        @endcanany
        @can('IT Ticket Create')
            <li class="menu-item {{ request()->is('ticket/it_ticket/create*') ? 'active' : '' }}">
                <a href="{{ route('administration.ticket.it_ticket.create') }}" class="menu-link">{{ ___('Arise New Ticket') }}</a>
            </li>
        @endcan
    </ul>
</li>
@endcanany
