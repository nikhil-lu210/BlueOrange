@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Task'))

@section('css_links')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/jkanban/jkanban.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
    
    {{-- page css --}}
    <link rel="stylesheet" href="{{asset('assets/vendor/css/pages/app-kanban.css')}}" />
@endsection

@section('custom_css')
    <style>
      .kanban-item { background:#fff; border:1px solid rgba(67,89,113,0.1); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05); transition:all .2s ease; margin-bottom:12px; padding:16px; }
      .kanban-item:hover { box-shadow:0 4px 12px rgba(0,0,0,0.1); transform: translateY(-1px); }
      .kanban-board[data-id*="activated"] { background:rgba(255,62,29,0.05); border-left:3px solid #ff3e1d; }
      .kanban-board[data-id*="running"] { background:rgba(13,110,253,0.05); border-left:3px solid #0d6efd; }
      .kanban-board[data-id*="completed"] { background:rgba(25,135,84,0.05); border-left:3px solid #198754; }
      .kanban-board[data-id*="cancelled"] { background:rgba(108,117,125,0.05); border-left:3px solid #6c757d; }
      .kanban-board[data-id*="activated"] .kanban-item { border-left:2px solid rgba(255,62,29,0.3); }
      .kanban-board[data-id*="running"] .kanban-item { border-left:2px solid rgba(13,110,253,0.3); }
      .kanban-board[data-id*="completed"] .kanban-item { border-left:2px solid rgba(25,135,84,0.3); }
      .kanban-board[data-id*="cancelled"] .kanban-item { border-left:2px solid rgba(108,117,125,0.3); opacity:.8; }
      .task-creator small, .parent-task-info small { font-size: .7rem; }
      .parent-task-info { background: rgba(13,110,253,.1); border-radius:4px; padding:4px 8px; }
      .task-progress .progress { border-radius:2px; background-color: rgba(67,89,113,.1); }
      .task-progress .progress-bar { transition: width .3s ease; }
      .enhanced-footer { border-top:1px solid rgba(67,89,113,.05); margin-top:12px; padding-top:8px; }
      .enhanced-footer small { font-size:.7rem; font-weight:500; }
      .kanban-text { font-size:.875rem; line-height:1.3; color:#566a7f; }
      .kanban-description p { color:#8592a3; }
      .kanban-board { border-radius:8px; padding:16px 12px; }
      .kanban-title-board { font-weight:600; color:#566a7f; margin-bottom:16px; font-size:1rem; }
      .kanban-board[data-id*="activated"] .kanban-title-board { color:#ff3e1d; }
      .kanban-board[data-id*="running"] .kanban-title-board { color:#0d6efd; }
      .kanban-board[data-id*="completed"] .kanban-title-board { color:#198754; }
      .kanban-board[data-id*="cancelled"] .kanban-title-board { color:#6c757d; }
      .new-item-form { background:#f8f9fa; border-radius:8px; padding:12px; margin-bottom:12px; }
      .new-item-form .form-control { border:1px solid #dee2e6; font-size:.875rem; }
      .spinner-border-sm { width:.875rem; height:.875rem; }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Tasks Board') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    <li class="breadcrumb-item active">{{ __('All Tasks Board') }}</li>
@endsection


@section('content')


<div class="row">
    <div class="app-kanban">
        <!-- Kanban Wrapper -->
        <div class="kanban-wrapper"></div>

        <!-- Edit Task & Activities -->
        <div class="offcanvas offcanvas-end kanban-update-item-sidebar">
            <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title">Edit Task</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
            <ul class="nav nav-tabs tabs-line">
                <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-update">
                    <i class="ti ti-edit me-2"></i>
                    <span class="align-middle">Edit</span>
                </button>
                </li>
                <li class="nav-item">
                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-activity">
                    <i class="ti ti-trending-up me-2"></i>
                    <span class="align-middle">Activity</span>
                </button>
                </li>
            </ul>
            <div class="tab-content px-0 pb-0">
                <!-- Update item/tasks -->
                <div class="tab-pane fade show active" id="tab-update" role="tabpanel">
                <form>
                    <div class="mb-3">
                    <label class="form-label" for="title">Title</label>
                    <input type="text" id="title" class="form-control" placeholder="Enter Title" />
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea id="description" class="form-control" rows="4" placeholder="Add description..."></textarea>
                    </div>

                    <div class="mb-3">
                    <label class="form-label" for="due-date">Due Date</label>
                    <input type="text" id="due-date" class="form-control" placeholder="Enter Due Date" />
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="label"> Label</label>
                    <select class="select2 select2-label form-select" id="label">
                        <option data-color="bg-label-success" value="UX">UX</option>
                        <option data-color="bg-label-warning" value="Images">
                        Images
                        </option>
                        <option data-color="bg-label-info" value="Info">Info</option>
                        <option data-color="bg-label-danger" value="Code Review">
                        Code Review
                        </option>
                        <option data-color="bg-label-secondary" value="App">
                        App
                        </option>
                        <option data-color="bg-label-primary" value="Charts & Maps">
                        Charts & Maps
                        </option>
                    </select>
                    </div>
                    <div class="mb-3">
                    <label class="form-label">Assigned</label>
                    <div class="assigned d-flex flex-wrap"></div>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="attachments">Attachments</label>
                    <input type="file" class="form-control" id="attachments" />
                    </div>
                    <div class="mb-4">
                    <label class="form-label">Comment</label>
                    <div class="comment-editor border-bottom-0"></div>
                    <div class="d-flex justify-content-end">
                        <div class="comment-toolbar">
                        <span class="ql-formats me-0">
                            <button class="ql-bold"></button>
                            <button class="ql-italic"></button>
                            <button class="ql-underline"></button>
                            <button class="ql-link"></button>
                            <button class="ql-image"></button>
                        </span>
                        </div>
                    </div>
                    </div>
                    <div class="d-flex flex-wrap">
                    <button type="button" class="btn btn-primary me-3" data-bs-dismiss="offcanvas">
                        Update
                    </button>
                    <button type="button" class="btn btn-label-danger" data-bs-dismiss="offcanvas">
                        Delete
                    </button>
                    </div>
                </form>
                </div>
                <!-- Activities -->
                <div class="tab-pane fade" id="tab-activity" role="tabpanel">
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-success rounded-circle">HJ</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Jordan</span> Left the board.
                    </p>
                    <small class="text-muted">Today 11:00 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Dianna</span> mentioned
                        <span class="text-primary">@bruce</span> in
                        a comment.
                    </p>
                    <small class="text-muted">Today 10:20 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/2.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Martian</span> added moved
                        Charts & Maps task to the done board.
                    </p>
                    <small class="text-muted">Today 10:00 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Barry</span> Commented on App
                        review task.
                    </p>
                    <small class="text-muted">Today 8:32 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-secondary rounded-circle">BW</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Bruce</span> was assigned
                        task of code review.
                    </p>
                    <small class="text-muted">Today 8:30 PM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-danger rounded-circle">CK</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Clark</span> assigned task UX
                        Research to
                        <span class="text-primary">@martian</span>
                    </p>
                    <small class="text-muted">Today 8:00 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/4.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Ray</span> Added moved
                        <span class="fw-medium">Forms & Tables</span> task
                        from in progress to done.
                    </p>
                    <small class="text-muted">Today 7:45 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Barry</span> Complete all the
                        tasks assigned to him.
                    </p>
                    <small class="text-muted">Today 7:17 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-success rounded-circle">HJ</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Jordan</span> added task to
                        update new images.
                    </p>
                    <small class="text-muted">Today 7:00 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <img src="{{ asset('assets/img/avatars/6.png') }}" alt="Avatar" class="rounded-circle" />
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Dianna</span> moved task
                        <span class="fw-medium">FAQ UX</span> from in
                        progress to done board.
                    </p>
                    <small class="text-muted">Today 7:00 AM</small>
                    </div>
                </div>
                <div class="media mb-4 d-flex align-items-start">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-danger rounded-circle">CK</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Clark</span> added new board
                        with name <span class="fw-medium">Done</span>.
                    </p>
                    <small class="text-muted">Yesterday 3:00 PM</small>
                    </div>
                </div>
                <div class="media d-flex align-items-center">
                    <div class="avatar me-2 flex-shrink-0 mt-1">
                    <span class="avatar-initial bg-label-secondary rounded-circle">BW</span>
                    </div>
                    <div class="media-body">
                    <p class="mb-0">
                        <span class="fw-medium">Bruce</span> added new task
                        in progress board.
                    </p>
                    <small class="text-muted">Yesterday 12:00 PM</small>
                    </div>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    
    <script src="{{asset('assets/vendor//libs/moment/moment.js')}}"></script>
    <script src="{{asset('assets/vendor//libs/flatpickr/flatpickr.js')}}"></script>
    <script src="{{asset('assets/vendor//libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/vendor//libs/jkanban/jkanban.js')}}"></script>
    <script src="{{asset('assets/vendor//libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/vendor//libs/quill/quill.js')}}"></script>

    {{-- page js --}}
    <script src="{{asset('assets/js/app-kanban.js')}}"></script>
@endsection

@section('custom_script')
    
@endsection
