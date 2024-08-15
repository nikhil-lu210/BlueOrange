@extends('administration.settings.user.show')

@section('profile_content')

<!-- User Profile Content -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Tasks of {{ $user->name }}</h5>
            </div>
            <div class="card-body">
                <table class="table data-table table-bordered table-responsive" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Title</th>
                            <th>Assigner & Assignees</th>
                            <th>Deadline</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $key => $task) 
                            <tr>
                                <th>#{{ serial($tasks, $key) }}</th>
                                <td>
                                    <b title="{{ $task->title }}">{{ show_content($task->title, 30) }}</b>
                                    <br>
                                    <small>Priority: <span class="text-muted">{{ $task->priority }}</span></small>
                                </td>
                                <td>
                                    <b class="text-dark">{{ $task->creator->first_name.' '.$task->creator->last_name }}</b>
                                    <br>
                                    @if ($task->users->count() > 0)
                                        <div class="d-flex align-items-center">
                                            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0 zindex-2 mt-1">
                                                @foreach ($task->users->take(6) as $user)
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $user->name }}" class="avatar avatar-sm pull-up">
                                                        @if ($user->hasMedia('avatar'))
                                                            <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" alt="Avatar" class="rounded-circle">
                                                        @else
                                                            <img src="https://fakeimg.pl/300/dddddd/?text=No-Image" alt="No Avatar" class="rounded-circle">
                                                        @endif
                                                    </li>
                                                @endforeach
                                                @if ($task->users->count() > 6)
                                                    <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="{{ $task->users->count() - 6 }} More" class="avatar avatar-sm pull-up more-user-avatar">
                                                        <small>{{ $task->users->count() - 6 }}+</small>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if (!is_null($task->deadline)) 
                                        <b>{{ show_date($task->deadline) }}</b>
                                    @else 
                                        <span class="badge bg-success">Ongoing Task</span>
                                    @endif
                                    <br>
                                    <small>Created: <span class="text-muted">{{ show_date($task->created_at) }}</span></small>
                                </td>
                                <td>{!! show_status($task->status) !!}</td>
                                <td class="text-center">
                                    @can ('Task Read') 
                                        <a href="{{ route('administration.task.show', ['task' => $task, 'taskid' => $task->taskid]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Task Details">
                                            <i class="ti ti-info-hexagon"></i>
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<!--/ User Profile Content -->
@endsection