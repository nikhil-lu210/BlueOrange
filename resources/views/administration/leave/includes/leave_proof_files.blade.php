<div class="card mb-4">
    <div class="card-header header-elements pt-3 pb-3">
        <h5 class="mb-0">Leave Prescriptions/Proofs</h5>
    </div>
    
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaveHistory->files as $file) 
                        <tr>
                            <td>
                                <b class="text-dark" title="{{ $file->original_name }}">
                                    {{ show_content($file->original_name, 20) }}
                                </b>
                            </td>
                            <td class="text-center">{{ get_file_media_size($file) }}</td>
                            <td class="text-center">
                                @if ($leaveHistory->creator_id == auth()->user()->id) 
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
</div>