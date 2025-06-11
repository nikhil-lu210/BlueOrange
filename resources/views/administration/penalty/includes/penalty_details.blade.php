<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Penalty Details</small>
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-user"></i>
                <span class="fw-medium mx-2 text-heading">Employee:</span>
            </dt>
            <dd class="col-sm-8">
                {!! show_user_name_and_avatar($penalty->user, name: null) !!}
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-pause"></i>
                <span class="fw-medium mx-2 text-heading">Date:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{{ show_date($penalty->attendance->clock_in) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-pause"></i>
                <span class="fw-medium mx-2 text-heading">Total Time:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{{ $penalty->total_time_formatted }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-chart-candle"></i>
                <span class="fw-medium mx-2 text-heading">Type:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-bold text-danger">{{ $penalty->type }}<span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-check"></i>
                <span class="fw-medium mx-2 text-heading">Submitted At:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark">{{ show_date_time($penalty->created_at) }}</span>
            </dd>
        </dl>
        @isset ($penalty->creator)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-cog"></i>
                    <span class="fw-medium mx-2 text-heading">Creator:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($penalty->creator, name: null) !!}
                </dd>
            </dl>
        @endisset
    </div>
</div>
