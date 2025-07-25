<div class="card mb-4">
    <div class="card-header header-elements pt-3 pb-3">
        <h5 class="mb-0">Penalty Proof Files</h5>
    </div>

    @if ($penalty->files->count() > 0)
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
                        @foreach ($penalty->files as $file)
                            <tr>
                                <td>
                                    @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                        <div class="task-image-container">
                                            <a href="{{ file_media_download($file) }}" data-lightbox="task-images" data-title="{{ $file->original_name }}">
                                                <img src="{{ file_media_download($file) }}" alt="{{ $file->original_name }}" class="img-fluid img-thumbnail" style="width: 150px; height: 100px; object-fit: cover;">
                                            </a>
                                        </div>
                                    @else
                                        <div class="file-thumbnail-container">
                                            <i class="ti ti-file-download fs-2 mb-2 text-primary"></i>
                                            <span class="file-name text-center small fw-medium" title="{{ $file->original_name }}">
                                                {{ show_content($file->original_name, 15) }}
                                            </span>
                                            <small class="text-muted">{{ strtoupper(pathinfo($file->original_name, PATHINFO_EXTENSION)) }}</small>
                                        </div>
                                    @endif
                                </td>
                                <td>{{ get_file_media_size($file) }}</td>
                                <td>{{ date_time_ago($file->created_at) }}</td>
                                <td class="text-center">
                                    @canany(['Penalty Everything', 'Penalty Delete'])
                                        <a href="{{ file_media_destroy($file) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" title="Delete {{ $file->original_name }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    @endcanany
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
