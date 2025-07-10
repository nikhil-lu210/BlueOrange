@extends('administration.settings.user.show')

@section('css_links_user_show')
    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('profile_content')

<!-- User Files -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('All Files') }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="javascript:void(0);" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addUserFileModal" title="Add File">
                        <span class="tf-icon ti ti-user-plus ti-xs me-1"></span>
                        Add File
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if ($user->files->count() > 0)
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Note</th>
                                        <th>Size</th>
                                        <th>Uploaded</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->files as $file)
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
                                            <td>{!! $file->note !!}</td>
                                            <td>{{ get_file_media_size($file) }}</td>
                                            <td>{{ date_time_ago($file->created_at) }}</td>
                                            <td class="text-center">
                                                @canany (['User Everything', 'User Delete'])
                                                    <a href="{{ file_media_destroy($file) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" title="Delete {{ $file->original_name }}">
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                @endcanany
                                                <a href="{{ file_media_download($file) }}" target="_blank" class="btn btn-icon btn-primary btn-sm waves-effect" title="Download {{ $file->original_name }}">
                                                    <i class="ti ti-download"></i>
                                                </a>
                                                <a href="{{ get_file_media_url($file) }}" target="_blank" class="btn btn-icon btn-dark btn-sm waves-effect" title="View {{ $file->original_name }}">
                                                    <i class="ti ti-eye"></i>
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
        </div>
    </div>
</div>
<!--/ User Files -->


{{-- Add Users File Modal --}}
@include('administration.settings.user.includes.modals.add_file')

@endsection


@section('script_links_user_show')
    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
