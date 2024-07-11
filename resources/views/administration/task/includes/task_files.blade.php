<div class="card mb-4">
    <div class="card-header header-elements pt-3 pb-3">
        <h5 class="mb-0">Task Files</h5>

        @if (auth()->user()->id == $task->creator->id) 
            <div class="card-header-elements ms-auto">
                <button type="button" class="btn btn-xs btn-primary" title="Click to upload files for {{ $task->title }}">
                    <span class="tf-icon ti ti-upload ti-xs me-1"></span>
                    Upload Files
                </button>
            </div>
        @endif
    </div>
    
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
                            <td><b class="text-dark">{{ $file->original_name }}</b></td>
                            <td>{{ get_file_media_size($file) }}</td>
                            <td>{{ date_time_ago($file->created_at) }}</td>
                            <td class="text-center">
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
</div>