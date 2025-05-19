<div class="card card-border-shadow-primary mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Information</small>
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-user"></i>
                <span class="fw-medium mx-2 text-heading">Ticket Creator:</span>
            </dt>
            <dd class="col-sm-8">
                {!! show_user_name_and_avatar($itTicket->creator, role: false) !!}
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash"></i>
                <span class="fw-medium mx-2 text-heading">Title:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{!! $itTicket->title !!}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar"></i>
                <span class="fw-medium mx-2 text-heading">Creation Date:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{{ show_date($itTicket->created_at) }}</span>
                at
                <span class="text-dark text-bold">{{ show_time($itTicket->created_at) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-chart-candle"></i>
                <span class="fw-medium mx-2 text-heading">Status:</span>
            </dt>
            <dd class="col-sm-8">
                @if ($itTicket->status === 'Pending')
                    <span class="badge bg-dark">{{ __('Pending') }}</span>
                @elseif ($itTicket->status === 'Running')
                    <span class="badge bg-primary">{{ __('Running') }}</span>
                @elseif ($itTicket->status === 'Solved')
                    <span class="badge bg-success">{{ __('Solved') }}</span>
                @else
                    <span class="badge bg-danger">{{ __('Canceled') }}</span>
                @endif
            </dd>
        </dl>
        @if ($itTicket->solved_at)
            <hr>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-cog"></i>
                    <span class="fw-medium mx-2 text-heading">{{ $itTicket->status }} By:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($itTicket->solver, name: null) !!}
                </dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-clock-check"></i>
                    <span class="fw-medium mx-2 text-heading">{{ $itTicket->status }} At:</span>
                </dt>
                <dd class="col-sm-8">
                    <span class="text-dark">{{ show_date_time($itTicket->solved_at) }}</span>
                </dd>
            </dl>
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-calendar"></i>
                    <span class="fw-medium mx-2 text-heading">{{ $itTicket->status }} At:</span>
                </dt>
                <dd class="col-sm-8">
                    <span class="text-dark text-bold">{{ show_date($itTicket->solved_at) }}</span>
                    at
                    <span class="text-dark text-bold">{{ show_time($itTicket->solved_at) }}</span>
                </dd>
            </dl>
            <hr>
        @endif
        <dl class="row mb-1 mt-3">
            <dd class="col-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-bold text-center">Seen By</th>
                            <th class="text-bold text-center">Seen At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($itTicket->seen_by as $seenByAt)
                            <tr>
                                <td class="text-center">
                                    {{ show_user_data($seenByAt['user_id'], 'name') }}
                                </td>
                                <td class="text-center">
                                    {{ show_date_time($seenByAt['seen_at']) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </dd>
        </dl>
    </div>
</div>