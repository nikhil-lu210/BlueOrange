<div class="card mb-4">
    <div class="card-header header-elements pt-3 pb-3">
        <h5 class="mb-0">Task Files</h5>

        @if (auth()->user()->id == $task->creator->id)
            <div class="card-header-elements ms-auto">
                <button type="button" class="btn btn-xs btn-primary" title="Click to upload files for {{ $task->title }}" data-bs-toggle="modal" data-bs-target="#addTaskFilesModal">
                    <span class="tf-icon ti ti-upload ti-xs me-1"></span>
                    Upload Files
                </button>
            </div>
        @endif
    </div>

    @if ($task->files->count() > 0)
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Uploaded</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($task->files as $file)
                            <tr>
                                <td>
                                    @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                        <div class="task-image-container">
                                            <a href="{{ file_media_download($file) }}" data-lightbox="task-images" data-title="{{ $file->original_name }}">
                                                <img src="{{ file_media_download($file) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @else
                                        <div class="file-thumbnail-container" style="width: 150px; height: 100px; display: flex; flex-direction: column; justify-content: center; align-items: center; background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 0.25rem;">
                                            <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                            <span class="text-center small fw-medium" style="max-width: 140px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $file->original_name }}">
                                                {{ show_content($file->original_name, 15) }}
                                            </span>
                                            <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ get_file_media_size($file) }}</td>
                                <td>{{ date_time_ago($file->created_at) }}</td>
                                <td class="text-center">
                                    @if ($task->creator_id == auth()->user()->id)
                                        <a href="{{ file_media_destroy($file) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" title="Delete {{ $file->original_name }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    @endif
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
    @endif
</div>


{{-- Add Task Files Modal --}}
@include('administration.task.modals.add_task_files')


