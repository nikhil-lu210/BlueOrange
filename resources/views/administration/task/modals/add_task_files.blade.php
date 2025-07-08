<!-- Holiday Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="addTaskFilesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Upload Task Files</h3>
                    <p class="text-muted">Upload Files For This Task</p>
                </div>
                <!-- Holiday Create form -->
                <form method="post" action="{{ route('administration.task.upload.files', ['task' => $task]) }}" enctype="multipart/form-data" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="files[]" class="form-label">{{ __('Task Files') }} <strong class="text-danger">*</strong></label>
                        <input type="file" id="files[]" name="files[]" value="{{ old('files[]') }}" placeholder="{{ __('Task Files') }}" class="form-control @error('files[]') is-invalid @enderror" multiple required/>
                        @error('files[]')
                            <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ti ti-x"></i>
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-upload"></i>
                            Upload Files
                        </button>
                    </div>
                </form>
                <!--/ Holiday Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Holiday Create Modal -->
