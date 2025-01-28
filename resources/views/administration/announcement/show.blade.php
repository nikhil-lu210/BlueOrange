@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Announcement Details'))

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
    <b class="text-uppercase">{{ __('Announcement Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Announcement') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.announcement.my') }}">{{ __('My Announcements') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Announcement Details') }}</li>
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
                            <h4 class="mb-0">{{ $announcement->title }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Announcer" data-bs-placement="bottom">
                                    <i class="ti ti-crown"></i> 
                                    {{ $announcement->announcer->name }}
                                </li>
                                <li class="list-inline-item d-flex gap-1" data-bs-toggle="tooltip" title="Announcement Date & Time">
                                    <i class="ti ti-calendar"></i> 
                                    {{ show_date_time($announcement->created_at) }}
                                </li>
                            </ul>
                            @if (!is_null($announcement->recipients)) 
                                <ul class="list-inline mb-0 mt-3 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-1">
                                    @foreach ($announcement->recipients as $recipient) 
                                        <li class="list-inline-item d-flex gap-1 badge bg-black">
                                            {{ show_user_data($recipient, 'name') }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        @can ('Announcement Update') 
                            <a href="{{ route('administration.announcement.edit', ['announcement' => $announcement]) }}" class="btn btn-dark btn-icon rounded-pill" data-bs-toggle="tooltip" title="Edit Announcement">
                                <i class="ti ti-pencil"></i>
                            </a>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Announcement Details --}}
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Announcement Details</h5>
    
                <div class="card-header-elements ms-auto">
                    <button type="button" class="btn btn-primary btn-sm btn-icon rounded-pill" title="Seen & Read By" data-bs-toggle="modal" data-bs-target="#showAnnouncementReadersModal">
                        <span class="ti ti-eye"></span>
                    </button>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                {!! $announcement->description !!}
            </div>
        </div>
    </div>

    {{-- Announcement Comments --}}
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Announcement Comments</h5>
    
                <div class="card-header-elements ms-auto">
                    <button type="button" class="btn btn-sm btn-primary" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                        Comment
                    </button>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <form action="{{ route('administration.announcement.comment.store', ['announcement' => $announcement]) }}" method="post">
                            @csrf
                            <div class="collapse" id="collapseExample">
                                <div class="row">
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="comment" rows="2" placeholder="Ex: Congratulations Jhon Doe." required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <span class="text-danger">{{ $message }}</span>
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
                                @foreach ($announcement->comments as $comment) 
                                    <tr class="border-0 border-bottom-0">
                                        <td class="border-0 border-bottom-0">
                                            <div class="d-flex justify-content-between align-items-center user-name">
                                                <div class="d-flex commenter">
                                                    <div class="avatar-wrapper">
                                                        <div class="avatar me-2">
                                                            @if (auth()->user()->hasMedia('avatar'))
                                                                <img src="{{ $comment->commenter->getFirstMediaUrl('avatar', 'thumb') }}" alt="{{ $comment->commenter->name }} Avatar" class="h-auto rounded-circle">
                                                            @else
                                                                <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $comment->commenter->name }} No Avatar" class="h-auto rounded-circle">
                                                            @endif
                                                        </div>
                                                      </div>
                                                      <div class="d-flex flex-column">
                                                        <span class="fw-medium">{{ $comment->commenter->name }}</span>
                                                        <small class="text-muted">{{ $comment->commenter->role->name }}</small>
                                                    </div>
                                                </div>
                                                <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                                            </div>
                                            <div class="d-flex mt-2">
                                                <p>{{ $comment->comment }}</p>
                                            </div>
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
</div>
<!-- End row -->


{{-- Read By At Modal --}}
<div class="modal fade" data-bs-backdrop="static" id="showAnnouncementReadersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Announcement Readers</h3>
                    <p class="text-muted">Details of Announcement Readers</p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Read By</th>
                                    <th>Read At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach (json_decode($announcement->read_by_at, true) as $readByAt)
                                    <tr>
                                        <td>{{ show_user_data($readByAt['read_by'], 'name') }}</td>
                                        <td>{{ show_date_time($readByAt['read_at']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Read By At Modal --}}

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
@endsection
