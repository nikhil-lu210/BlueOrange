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

    <div class="col-md-5">
        {{-- <!-- About Task --> --}}
        @include('administration.task.includes.about_task')
        
        {{-- Task Assignees --}}
        @include('administration.task.includes.task_assignees')
        
        {{-- Task History Summary --}}
        @include('administration.task.includes.task_history_summary')
    </div>
    
    <div class="col-md-7">
        {{-- Task Details --}}
        @include('administration.task.includes.task_details')
        
        {{-- <!-- Task Files --> --}}
        @if ($task->files)
            @include('administration.task.includes.task_files')
        @endif


        {{-- Task Comments --}}
        @include('administration.task.includes.task_comments')
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
