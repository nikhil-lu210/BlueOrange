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
                                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="{{ $comment->commenter->name }} No Avatar" class="h-auto rounded-circle">
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

                                    @if ($comment->files->count() > 0) 
                                        <div class="d-flex flex-wrap gap-2 pt-1 mb-3">
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