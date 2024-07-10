@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Task Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    .btn-block {
        width: 100%;
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Task Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Task') }}</li>
    @canany (['Task Create', 'Task Update', 'Task Delete'])
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.index') }}">{{ __('All Tasks') }}</a>
        </li>
    @else
        <li class="breadcrumb-item">
            <a href="{{ route('administration.task.my') }}">{{ __('My Tasks') }}</a>
        </li>
    @endcanany
    <li class="breadcrumb-item active">{{ __('Task Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-0">{{ $task->title }}</h4>
                            @if ($task->users)
                                <div class="d-flex align-items-center">
                                    <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2 mt-1">
                                        @foreach ($task->users as $user)
                                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar avatar-sm pull-up">
                                                @if ($user->hasMedia('avatar'))
                                                    <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                                @else
                                                    <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
                                                @endif
                                            </li>
                                        @endforeach
                                        <li class="m-2">
                                            <small class="text-muted">{{ count($task->users) }} Users</small>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @can ('Task Read') 
                            <a href="javascript:void(0);" class="btn btn-success" title="Start Working On This Task">
                                <i class="ti ti-clock-check me-1" style="margin-top: -3px;"></i>
                                Start
                            </a>
                            <a href="javascript:void(0);" class="btn btn-danger" title="Stop  Working On This Task">
                                <i class="ti ti-clock-x me-1" style="margin-top: -3px;"></i>
                                Stop
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Task Details --}}
    <div class="col-md-5">
        {{-- <!-- About Task --> --}}
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">About Task</small>
                <ul class="list-unstyled mb-0 mt-3">
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-hash text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Task-ID:</span> 
                        <span class="text-bold text-primary">{{ $task->taskid }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-user-edit text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Creator:</span> 
                        <span>{{ $task->creator->name }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-clock-up text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Created At:</span> 
                        <span class="text-capitalize">{{ date_time_ago($task->created_at) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-hourglass-off text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Deadline:</span> 
                        <span class="text-capitalize">{{ date_time_ago($task->deadline) }}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-check text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Status:</span> 
                        <span>{!! show_status($task->status) !!}</span>
                    </li>
                    <li class="d-flex align-items-center mb-3">
                        <i class="ti ti-checks text-heading"></i>
                        <span class="fw-medium mx-2 text-heading">Priority:</span> 
                        <span class="text-bold">{{ $task->priority }}</span>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Task Assignees --}}
        <div class="card card-action mb-4">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">Task Assignees</h5>
                @if (auth()->user()->id == $task->creator->id) 
                    <div class="card-action-element">
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-dots-vertical text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="javascript:void(0);">
                                        <i class="ti ti-plus me-1 fs-5" style="margin-top: -2px;"></i>
                                        Add Assignees
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger confirm-danger" href="javascript:void(0);">
                                        <i class="ti ti-x me-1 fs-5" style="margin-top: -2px;"></i>
                                        Remove Assignees
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach ($task->users as $user) 
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <div class="d-flex align-items-start">
                                    <div class="avatar me-2">
                                        @if ($user->hasMedia('avatar'))
                                            <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
                                        @endif
                                    </div>
                                    <div class="me-2 ms-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted fs-tiny">{{ show_date($user->pivot->created_at) }}</small>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <b class="text-muted">{{ $user->pivot->progress }}%</b>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Currently Working --}}
        <div class="card card-action mb-4">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">Currently Working</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    @foreach ($task->users as $user) 
                        <li class="mb-3">
                            <div class="d-flex align-items-start">
                                <div class="d-flex align-items-start">
                                    <div class="avatar me-2">
                                        @if ($user->hasMedia('avatar'))
                                            <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                        @else
                                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
                                        @endif
                                    </div>
                                    <div class="me-2 ms-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted fs-tiny">{{ show_date($user->pivot->created_at) }}</small>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <b class="text-muted">{{ $user->pivot->progress }}%</b>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card card-action mb-4">
            <div class="card-header align-items-center pb-3 pt-3">
                <h5 class="card-action-title mb-0">{{ $task->title }}</h5>
                <div class="card-action-element">
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ti ti-dots-vertical text-muted"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item text-dark" href="{{ route('administration.task.edit', ['task' => $task]) }}">
                                    <i class="ti ti-history me-1 fs-5" style="margin-top: -5px;"></i>
                                    Task History
                                </a>
                            </li>
                            @if (auth()->user()->id == $task->creator->id)
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('administration.task.edit', ['task' => $task]) }}">
                                        <i class="ti ti-edit me-1 fs-5" style="margin-top: -5px;"></i>
                                        Edit Task
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider" />
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger confirm-danger" href="{{ route('administration.task.destroy', ['task' => $task]) }}">
                                        <i class="ti ti-trash me-1 fs-5" style="margin-top: -5px;"></i>
                                        Delete Task
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body border-top pt-3 pb-3">
                <div class="task-details">
                    {!! $task->description !!}
                </div>
            </div>
        </div>
        
        <!-- Task Files -->
        @if ($task->files) 
            <div class="card mb-4">
                <div class="card-header header-elements pt-3 pb-3">
                    <h5 class="mb-0">Task Files</h5>
            
                    @if (auth()->user()->id == $task->creator->id) 
                        <div class="card-header-elements ms-auto">
                            <button type="button" class="btn btn-xs btn-primary">
                                <span class="tf-icon ti ti-upload ti-xs me-1"></span>
                                Upload Files
                            </button>
                        </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Size</th>
                                    <th>Upload Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($task->files as $file) 
                                    <tr>
                                        <td><b class="text-dark">{{ $file->original_name }}</b></td>
                                        <td>{{ get_file_media_size($file) }}</td>
                                        <td>{{ date_time_ago($file->created_at) }}</td>
                                        <td class="text-center">
                                            <a href="{{ file_media_download($file) }}" target="_blank" class="btn btn-icon btn-primary btn-sm waves-effect" title="Download {{ $file->original_name }}">
                                                <i class="ti ti-download"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif


        {{-- Task Comments --}}
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Task Comments</h5>
    
                <div class="card-header-elements ms-auto">
                    <button type="button" class="btn btn-sm btn-primary" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#taskComment" aria-expanded="false" aria-controls="taskComment">
                        <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                        Comment
                    </button>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="collapse" id="taskComment">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <textarea class="form-control" name="comment" rows="2" placeholder="Ex: I Didn't Understand The Task." required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-1">
                                        <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Comment Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                                        @error('files[]')
                                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                                        @enderror
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block mt-2 mb-3">
                                            <i class="ti ti-check"></i>
                                            Submit Comment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12 comments">
                        <table class="table">
                            <tbody>
                                @foreach ($task->comments as $comment) 
                                    <tr class="border-0 border-bottom-0">
                                        <td class="border-0 border-bottom-0">
                                            <div class="d-flex justify-content-between align-items-center user-name">
                                                <div class="d-flex commenter">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            @if (auth()->user()->hasMedia('avatar'))
                                                                <img src="{{ $comment->commenter->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $comment->commenter->name }} Avatar" class="h-auto rounded-circle">
                                                            @else
                                                                <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="{{ $comment->commenter->name }} No Avatar" class="h-auto rounded-circle">
                                                            @endif
                                                        </div>
                                                      </div>
                                                      <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ $comment->commenter->name }}</span>
                                                        <small class="text-muted">{{ $comment->commenter->roles[0]->name }}</small>
                                                    </div>
                                                </div>
                                                <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                                            </div>
                                            <div class="d-flex mt-2">
                                                <p>{{ $comment->comment }}</p>
                                            </div>

                                            @if ($comment->files) 
                                                <div class="d-flex flex-wrap gap-2 pt-1">
                                                    @foreach ($comment->files as $commentFile) 
                                                        <a href="{{ file_media_download($commentFile) }}" target="_blank" class="me-3 badge bg-label-dark" title="Click Here to Download {{ $commentFile->original_name }}">
                                                            <i class="ti ti-file-download fw-bold fs-6"></i>
                                                            <span class="fw-medium">{{ $commentFile->original_name }}</span>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    {{-- Task Details --}}
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
@endsection
