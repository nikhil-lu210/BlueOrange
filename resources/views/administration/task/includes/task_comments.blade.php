<div class="card mb-4">
    <div class="card-header header-elements">
        <h5 class="mb-0">Task Comments</h5>

        <div class="card-header-elements ms-auto">
            {{-- @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                <button type="button" class="btn btn-primary btn-xs" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#taskComment" aria-expanded="false" aria-controls="taskComment">
                    <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                    Comment
                </button>
            @endif --}}
        </div>
    </div>
    <!-- Account -->
    <div class="card-body">
        @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
            <div class="row">
                <div class="col-md-12">
                    <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off" id="taskCommentForm">
                        @csrf
                        <div class="collapse show" id="taskComment">
                            <div class="row">
                                <div class="mb-3 col-md-12">
                                    <div name="comment" id="taskCommentEditor">{!! old('comment') !!}</div>
                                    <textarea class="d-none" name="comment" id="commentInput">{{ old('comment') }}</textarea>
                                    @error('comment')
                                        <b class="text-danger">{{ $message }}</b>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-1">
                                    <input type="file" id="comment-files" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Comment Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
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
        @endif

        <div class="row">
            <div class="col-md-12 comments">
                @php
                    $senderColor = 'background-color: #f0676714 !important; border: 1px solid #f067675c !important;';
                    $receiverColor = 'background-color: #7367f014 !important; border: 1px solid #7367f05c !important;';
                @endphp

                @foreach ($task->comments as $comment)
                    <div class="comment-container mb-4 p-3 rounded" style="{{ $comment->commenter->id == auth()->user()->id ? $senderColor : $receiverColor }}">
                        <!-- Main Comment -->
                        <div class="main-comment">
                            <div class="d-flex justify-content-between align-items-center user-name mb-2">
                                {!! show_user_name_and_avatar($comment->commenter, name: null) !!}
                                <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                            </div>
                            <div class="comment-content mb-2">
                                {!! $comment->comment !!}
                            </div>

                            @if ($comment->files->count() > 0)
                                <div class="d-flex flex-wrap gap-2 mb-3">
                                    @foreach ($comment->files as $commentFile)
                                        @if (in_array($commentFile->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                            <div class="comment-image-container" title="Click to view {{ $commentFile->original_name }}">
                                                <a href="{{ file_media_download($commentFile) }}" data-lightbox="comment-images-{{ $comment->id }}" data-title="{{ $commentFile->original_name }}">
                                                    <img src="{{ file_media_download($commentFile) }}" alt="{{ $commentFile->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                                </a>
                                            </div>
                                        @else
                                            <div class="file-thumbnail-container" title="Click to Download {{ $commentFile->original_name }}">
                                                <a href="{{ file_media_download($commentFile) }}" target="_blank" class="text-decoration-none">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                                        <span class="file-name text-center small fw-medium">
                                                            {{ show_content($commentFile->original_name, 15) }}
                                                        </span>
                                                        <small class="text-muted">{{ strtoupper(pathinfo($commentFile->original_name, PATHINFO_EXTENSION)) }}</small>
                                                    </div>
                                                </a>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            <!-- Reply Button -->
                            @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                                <div class="d-flex justify-content-end mb-2">
                                    <a href="javascript:void(0);" class="text-primary text-bold reply-btn" data-comment-id="{{ $comment->id }}" title="Reply">
                                        <i class="ti ti-arrow-back-up me-1"></i>
                                        Reply
                                    </a>
                                </div>
                            @endif

                            <!-- Reply Form -->
                            @if (($task->users->contains(auth()->user()->id) && !is_null($hasUnderstood)) || $task->creator_id == auth()->user()->id)
                                <div class="reply-form-container" id="replyForm-{{ $comment->id }}" style="display: none;">
                                    <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off" class="reply-form">
                                        @csrf
                                        <input type="hidden" name="parent_comment_id" value="{{ $comment->id }}">
                                        <div class="row">
                                            <div class="mb-3 col-md-12">
                                                <div class="reply-editor" id="replyEditor-{{ $comment->id }}"></div>
                                                <textarea class="d-none reply-input" name="comment"></textarea>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <input type="file" name="files[]" class="form-control" multiple/>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary btn-sm me-2">
                                                    <i class="ti ti-check"></i>
                                                    Submit Reply
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm cancel-reply" data-comment-id="{{ $comment->id }}">
                                                    Cancel
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <!-- Replies -->
                        @if ($comment->replies->count() > 0)
                            <div class="replies-container mt-3">
                                @foreach ($comment->replies as $reply)
                                    <div class="reply-item mb-3 p-2 rounded" style="background-color: rgba(0,0,0,0.05); border-left: 1px solid #7367f0;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            {!! show_user_name_and_avatar($reply->commenter, name: null) !!}
                                            <small class="date-time text-muted">{{ date_time_ago($reply->created_at) }}</small>
                                        </div>

                                        <div class="reply-content">
                                            {!! $reply->comment !!}
                                        </div>

                                        @if ($reply->files->count() > 0)
                                            <div class="d-flex flex-wrap gap-2 mt-2">
                                                @foreach ($reply->files as $replyFile)
                                                    @if (in_array($replyFile->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                        <div class="comment-image-container" title="Click to view {{ $replyFile->original_name }}">
                                                            <a href="{{ file_media_download($replyFile) }}" data-lightbox="reply-images-{{ $reply->id }}" data-title="{{ $replyFile->original_name }}">
                                                                <img src="{{ file_media_download($replyFile) }}" alt="{{ $replyFile->original_name }}" class="img-fluid img-thumbnail" style="width: 100px; height: 70px; object-fit: cover;">
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="file-thumbnail-container" title="Click to Download {{ $replyFile->original_name }}">
                                                            <a href="{{ file_media_download($replyFile) }}" target="_blank" class="text-decoration-none">
                                                                <div class="d-flex flex-column align-items-center">
                                                                    <i class="ti ti-file-download fs-4 mb-1 text-primary"></i>
                                                                    <span class="file-name text-center small fw-medium">
                                                                        {{ show_content($replyFile->original_name, 10) }}
                                                                    </span>
                                                                    <small class="text-muted">{{ strtoupper(pathinfo($replyFile->original_name, PATHINFO_EXTENSION)) }}</small>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>


