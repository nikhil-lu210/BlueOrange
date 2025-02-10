<div class="card mb-4">
    <div class="card-header header-elements">
        <h5 class="mb-0">{{ __('Attendance Issue Details') }}</h5>
    </div>
    
    <div class="card-body">
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-user-edit text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Creator:</span>
            </dt>
            <dd class="col-sm-8">
                {!! show_user_name_and_avatar($issue->user, role: null) !!}
            </dd>
        </dl>
        <dl class="row mt-2 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-plus text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Expected Clock-In At:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-bold">{{ show_date_time($issue->clock_in) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-minus text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Expected Clock-Out At:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-bold">{{ show_date_time($issue->clock_out) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Expected Type:</span>
            </dt>
            <dd class="col-sm-8">
                <small class="text-bold badge bg-{{ $issue->type === 'Regular' ? 'success' : 'warning' }}">
                    {{ $issue->type }}
                </small>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-bell-check text-heading"></i>
                <span class="fw-medium mx-2 text-heading">Issue Status:</span>
            </dt>
            <dd class="col-sm-8">
                {!! show_status($issue->status) !!}
            </dd>
        </dl>
        <hr>
        <small class="card-text text-uppercase text-bold text-dark">{{ __('Issue Reason') }}</small>
        <dl class="row mt-2 mb-0">
            <dd class="col-12">
                <span>
                    {!! $issue->reason !!}
                </span>
            </dd>
        </dl>
        @if ($issue->status !== 'Pending') 
            <hr>
            <small class="card-text text-uppercase text-bold text-dark">{{ __('Issue Status') }}</small>
            <dl class="row mt-3 mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-check text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">{{ $issue->status }} By:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($issue->updater, name: null) !!}
                </dd>
            </dl>
            <dl class="row mt-2 mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-clock-check text-heading"></i>
                    <span class="fw-medium mx-2 text-heading">{{ $issue->status }} At:</span>
                </dt>
                <dd class="col-sm-8">
                    <span class="text-bold">{{ show_date_time($issue->updated_at) }}</span>
                </dd>
            </dl>
            @if ($issue->note) 
                <dl class="row mb-1">
                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                        <i class="ti ti-note text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Note:</span>
                    </dt>
                    <dd class="col-sm-8">
                        <small class="text-dark">{!! $issue->note !!}</small>
                    </dd>
                </dl>
            @endif
        @endif
    </div>
</div>