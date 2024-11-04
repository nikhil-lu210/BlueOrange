<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Leave History Details</small>
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash"></i>
                <span class="fw-medium mx-2 text-heading">Requested Type:</span>
            </dt>
            <dd class="col-sm-8">
                @if ($leaveHistory->type === 'Earned') 
                    <span class="badge bg-success">{{ __('Earned Leave') }}</span>
                @elseif ($leaveHistory->type === 'Sick') 
                    <span class="badge bg-warning">{{ __('Sick Leave') }}</span>
                @else 
                    <span class="badge bg-danger">{{ __('Casual Leave') }}</span>
                @endif
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-pause"></i>
                <span class="fw-medium mx-2 text-heading">For Date:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{{ show_date($leaveHistory->date) }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-pause"></i>
                <span class="fw-medium mx-2 text-heading">Total Time:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark text-bold">{{ $leaveHistory->total_leave->forHumans() }}</span>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-chart-candle"></i>
                <span class="fw-medium mx-2 text-heading">Status:</span>
            </dt>
            <dd class="col-sm-8">
                @if ($leaveHistory->status === 'Approved') 
                    <span class="badge bg-success">{{ __('Approved') }}</span>
                @elseif ($leaveHistory->status === 'Rejected') 
                    <span class="badge bg-danger">{{ __('Rejected') }}</span>
                @else 
                    <span class="badge bg-primary">{{ __('Pending') }}</span>
                @endif

                @isset($leaveHistory->is_paid_leave)
                    @switch($leaveHistory->is_paid_leave)
                        @case(true)
                            <span class="badge bg-success">{{ __('Paid') }}</span>
                            @break
                        @default
                            <span class="badge bg-danger">{{ __('Unpaid') }}<span>
                    @endswitch
                @endisset
            </dd>
        </dl>
        @isset ($leaveHistory->reviewed_by) 
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-cog"></i>
                    <span class="fw-medium mx-2 text-heading">Reviewed By:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($leaveHistory->reviewer, name: null) !!}
                </dd>
            </dl>
        @endisset
        @isset ($leaveHistory->reviewed_at)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-clock-check"></i>
                    <span class="fw-medium mx-2 text-heading">Reviewed At:</span>
                </dt>
                <dd class="col-sm-8">
                    <span class="text-dark">{{ show_date_time($leaveHistory->reviewed_at) }}</span>
                </dd>
            </dl>
        @endisset
        @if (!is_null($leaveHistory->reviewer_note) && $leaveHistory->status === 'Rejected') 
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-edit"></i>
                    <span class="fw-medium mx-2 text-heading">Reviewer Note:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! $leaveHistory->reviewer_note !!}
                </dd>
            </dl>
        @endif
    </div>
</div>