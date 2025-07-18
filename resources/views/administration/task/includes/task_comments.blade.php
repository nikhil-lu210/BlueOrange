<div class="card mb-4">
    <div class="card-header header-elements">
        <h5 class="mb-0">Task Comments</h5>

        <div class="card-header-elements ms-auto">
            @if ($task->users->contains(auth()->user()->id) || $task->creator_id == auth()->user()->id)
                <button type="button" class="btn btn-primary btn-xs" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#taskComment" aria-expanded="false" aria-controls="taskComment">
                    <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                    Comment
                </button>
            @endif
        </div>
    </div>
    <!-- Account -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('administration.task.comment.store', ['task' => $task]) }}" method="post" enctype="multipart/form-data" autocomplete="off" id="taskCommentForm">
                    @csrf
                    <div class="collapse show" id="taskComment">
                        <div class="row">
                            <div class="mb-3 col-md-12">
                                <div name="comment" id="taskCommentEditor">{!! old('comment') !!}</div>
                                <textarea class="d-none" name="comment" id="comment-input">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <b class="text-danger">{{ $message }}</b>
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
                <table class="table" style="border-spacing: 0 15px; border-collapse: separate;">
                    <tbody>
                        @foreach ($task->comments as $comment)
                            <tr class="mb-3 rounded pt-3" style="background-color: #7367f014 !important; border: 1px solid #7367f05c !important;">
                                <td class="border-0 border-bottom-0">
                                    <div class="d-flex justify-content-between align-items-center user-name">
                                        {!! show_user_name_and_avatar($comment->commenter, name: null) !!}
                                        <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <div class="d-block">{!! $comment->comment !!}</div>
                                    </div>

                                    @if ($comment->files->count() > 0)
                                        <div class="d-flex flex-wrap gap-2 pt-1 mb-3">
                                            @foreach ($comment->files as $commentFile)
                                                @if (in_array($commentFile->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                    <div class="comment-image-container" title="Click to view {{ $commentFile->original_name }}">
                                                        <a href="{{ file_media_download($commentFile) }}" data-lightbox="comment-images" data-title="{{ $commentFile->original_name }}">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


