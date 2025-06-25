<div class="card card-action mb-4">
    <div class="card-header align-items-center pb-1 pt-3">
        <h5 class="card-action-title mb-0">Test Details</h5>

        @canany(['Quiz Everything', 'Quiz Create', 'Quiz Update'])
            <div class="card-action-element">
                <div class="dropdown">
                    <button type="button" class="btn btn-icon btn-outline-primary btn-sm waves-effect waves-light dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        @if ($test->status == 'Pending')
                            <li>
                                <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#addTaskUsersModal">
                                    <i class="ti ti-pencil me-1 fs-5" style="margin-top: -2px;"></i>
                                    Edit Info
                                </button>
                            </li>
                            <li>
                                <hr class="dropdown-divider" />
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('administration.quiz.test.destroy', ['test' => $test]) }}" class="dropdown-item text-danger confirm-danger">
                                <i class="ti ti-trash me-1 fs-5" style="margin-top: -2px;"></i>
                                Delete Test
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endcanany
    </div>
    <div class="card-body">
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-hash"></i>
                <span class="fw-medium mx-2 text-heading">Test ID:</span>
            </dt>
            <dd class="col-sm-8">
                <b class="text-dark">{{ $test->testid }}</b>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-user"></i>
                <span class="fw-medium mx-2 text-heading">Candidate Name:</span>
            </dt>
            <dd class="col-sm-8">
                <b class="text-dark">{{ $test->candidate_name }}</b>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-mail"></i>
                <span class="fw-medium mx-2 text-heading">Candidate Email:</span>
            </dt>
            <dd class="col-sm-8">
                <a href="mailto:{{ $test->candidate_email }}" class="text-primary text-bold">{{ $test->candidate_email }}</a>
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-check"></i>
                <span class="fw-medium mx-2 text-heading">Created At:</span>
            </dt>
            <dd class="col-sm-8">
                {{ show_date_time($test->created_at) }}
            </dd>
        </dl>
        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-calendar-check"></i>
                <span class="fw-medium mx-2 text-heading">Status:</span>
            </dt>
            <dd class="col-sm-8">
                {!! show_status($test->status) !!}
                @if ($test->auto_submitted == true)
                    <sup class="badge bg-danger">Auto Submitted</sup>
                @endif
            </dd>
        </dl>
        @if ($test->started_at)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-calendar-check"></i>
                    <span class="fw-medium mx-2 text-heading">Started At:</span>
                </dt>
                <dd class="col-sm-8">
                    {{ show_date_time($test->started_at) }}
                </dd>
            </dl>
        @endif
        @if ($test->ended_at)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-calendar-check"></i>
                    <span class="fw-medium mx-2 text-heading">Ended At:</span>
                </dt>
                <dd class="col-sm-8">
                    {{ show_date_time($test->ended_at) }}
                </dd>
            </dl>
        @endif
        @if ($test->started_at && $test->ended_at)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-hourglass-high"></i>
                    <span class="fw-medium mx-2 text-heading">Total Time:</span>
                </dt>
                <dd class="col-sm-8">
                    {{ total_time_difference($test->started_at, $test->ended_at) }}
                </dd>
            </dl>
        @endif
        @if ($test->total_score)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-bookmark-edit"></i>
                    <span class="fw-medium mx-2 text-heading">Total Score:</span>
                </dt>
                <dd class="col-sm-8">
                    <span class="badge bg-dark text-bold">{{ $test->total_score }}</span>
                </dd>
            </dl>
        @endif
        <hr>
        @isset ($test->creator)
            <dl class="row mt-3 mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-cog"></i>
                    <span class="fw-medium mx-2 text-heading">Creator:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($test->creator, name: null) !!}
                </dd>
            </dl>
        @endisset
    </div>
</div>
