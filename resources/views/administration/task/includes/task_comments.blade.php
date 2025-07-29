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

                @foreach ($task->all_comments()->with(['commenter.employee', 'commenter.media', 'files', 'parent_comment.commenter.employee', 'parent_comment.commenter.media'])->orderByDesc('created_at')->get() as $comment)
                    <div class="comment-container mb-4 p-3 rounded chat-message {{ $comment->parent_comment_id ? 'has-parent' : '' }}" id="comment-{{ $comment->id }}" style="{{ $comment->commenter->id == auth()->user()->id ? $senderColor : $receiverColor }}">
                        <!-- Parent Comment Preview (if this is a reply) -->
                        @if ($comment->parent_comment_id)
                            @php
                                $parentComment = $comment->parent_comment;
                                $isReplyToMainComment = $parentComment->parent_comment_id === null;
                            @endphp
                            <div class="parent-comment-preview mb-3 p-2 rounded" style="background-color: rgba(0,0,0,0.05); border-left: 2px solid #dee2e6; cursor: pointer;" onclick="scrollToComment({{ $parentComment->id }})">
                                <div class="d-flex align-items-center mb-1">
                                    {!! show_user_name_and_avatar($parentComment->commenter, name: false, avatar: false) !!}
                                    <small class="text-muted ms-2">
                                        <i class="ti ti-corner-down-left me-1"></i>
                                        Replying to {{ $isReplyToMainComment ? 'this comment' : 'this reply' }}
                                    </small>
                                </div>
                                <div class="text-muted small" style="line-height: 1.2;">
                                    @php
                                        $truncatedComment = strip_tags($parentComment->comment);
                                        $truncatedComment = show_content($truncatedComment, 80);
                                    @endphp
                                    {{ $truncatedComment }}
                                </div>
                            </div>
                        @endif

                        <!-- Comment Content -->
                        <div class="main-comment">
                            <div class="d-flex justify-content-between align-items-center user-name mb-2">
                                {!! show_user_name_and_avatar($comment->commenter, name: false) !!}
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
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    // Function to scroll to a specific comment
    function scrollToComment(commentId) {
        const commentElement = document.getElementById('comment-' + commentId);
        if (commentElement) {
            // Add highlight effect
            commentElement.style.transition = 'all 0.3s ease';
            commentElement.style.boxShadow = '0 0 15px rgba(115, 103, 240, 0.5)';

            // Scroll to the comment
            commentElement.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

            // Remove highlight after 2 seconds
            setTimeout(() => {
                commentElement.style.boxShadow = '';
            }, 2000);
        }
    }
</script>


