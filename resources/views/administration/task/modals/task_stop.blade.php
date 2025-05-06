<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="stopTaskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Stop Task</h3>
                    <p class="text-muted">Stop The Task For Now</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.task.history.stop', ['task' => $task, 'taskHistory' => $lastActiveTaskHistory]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off" id="stopTaskForm">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">Files</label>
                        <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Comment Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple/>
                        @error('files[]')
                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Progress <sup>(In %)</sup> <strong class="text-danger">*</strong></label>
                        <input type="number" min="0" max="100" step="1" name="progress" value="{{ old('progress') }}" class="form-control" placeholder="Ex: 10" tabindex="-1" required/>
                        @error('progress')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Note <strong class="text-danger">*</strong></label>
                        <div name="note" id="taskStopNoteEditor">{!! old('note') !!}</div>
                        <textarea class="d-none" name="note" id="note-input">{{ old('note') }}</textarea>
                        @error('note')
                            <b class="text-danger">{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Stop Task Now
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
