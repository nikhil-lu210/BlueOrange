@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Inventory Details'))

@section('css_links')
    {{--  External CSS  --}}

    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        .img-thumbnail {
            padding: 3px;
            border: 3px solid var(--bs-border-color);
            border-radius: 5px;
        }
        .file-thumbnail-container {
            width: 150px;
            height: 100px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }
        .file-thumbnail-container .file-name {
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Inventory Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Inventory') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.inventory.index') }}">{{ __('All Inventories') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Inventory Details') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card border-bottom-primary mb-4">
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-grow-1 mt-4">
                    <div class="d-flex align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4 class="mb-1 text-bold text-dark">{{ $inventory->name }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex" title="Office Inventory Code (OIC)" data-bs-toggle="tooltip" data-bs-placement="right">
                                    <span class="text-bold text-muted">{{ $inventory->oic }}</span>
                                </li>
                            </ul>
                            <div class="mt-2">
                                <span class="{{ $inventory->status_badge }}">
                                    {{ $inventory->status }}
                                </span>
                            </div>
                        </div>

                        <div class="actions d-flex">
                            @canany (['Inventory Everything', 'Inventory Delete'])
                                <a href="{{ route('administration.inventory.destroy', ['inventory' => $inventory]) }}" class="btn btn-label-danger btn-icon waves-effect me-1 confirm-danger" title="{{ __('Delete Inventory') }}">
                                    <span class="tf-icon ti ti-trash"></span>
                                </a>
                            @endcanany

                            @canany (['Inventory Everything', 'Inventory Update'])
                                <a href="{{ route('administration.inventory.edit', ['inventory' => $inventory]) }}" class="btn btn-label-dark btn-icon waves-effect me-1" title="{{ __('Edit Inventory') }}">
                                    <span class="tf-icon ti ti-pencil"></span>
                                </a>
                            @endcanany

                            @canany (['Inventory Everything', 'Inventory Update'])
                                <a href="javascript:void(0);" class="btn btn-label-primary btn-icon waves-effect me-1" data-bs-toggle="modal" data-bs-target="#inventoryStatusUpdateModal" title="{{ __('Update Status') }}">
                                    <i class="ti ti-check"></i>
                                </a>
                            @endcanany
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Inventory Details --}}
    <div class="col-md-7">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Inventory Details</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Inventory Name</label>
                        <p class="mb-0">{{ $inventory->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Category</label>
                        <p class="mb-0">{{ $inventory->category->name }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Unique Number</label>
                        <p class="mb-0">{{ $inventory->unique_number ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Price</label>
                        <p class="mb-0">{{ $inventory->price ? '৳' . number_format($inventory->price, 2) : 'N/A' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Usage Purpose</label>
                        <p class="mb-0">{{ $inventory->usage_for }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Status</label>
                        <p class="mb-0">
                            <span class="{{ $inventory->status_badge }}">
                                {{ $inventory->status }}
                            </span>
                        </p>
                    </div>
                    @if($inventory->description)
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <p class="mb-0">{!! nl2br(e($inventory->description)) !!}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($inventory->files->count() > 0)
            <div class="card mb-4">
                <div class="card-header header-elements pt-3 pb-3">
                    <h5 class="mb-0">Inventory Files</h5>
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
                                @foreach ($inventory->files as $file)
                                    <tr>
                                        <td>
                                            @if (in_array($file->mime_type, ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml']))
                                                <div class="task-image-container">
                                                    <a href="{{ file_media_download($file) }}" data-lightbox="inventory-images" data-title="{{ $file->original_name }}">
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
                                            @if ($inventory->creator_id == auth()->user()->id)
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
        @endif
    </div>

    {{-- Inventory Information --}}
    <div class="col-md-5">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Inventory Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Created By</label>
                        <p class="mb-0">
                            {!! show_user_name_and_avatar($inventory->creator, name: null) !!}
                        </p>
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Created Date</label>
                        <p class="mb-0">{{ show_date_time($inventory->created_at) }}</p>
                    </div>
                    @if($inventory->updated_at != $inventory->created_at)
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">Last Updated</label>
                            <p class="mb-0">{{ show_date_time($inventory->updated_at) }}</p>
                        </div>
                    @endif
                    <div class="col-md-12 mb-3">
                        <label class="form-label fw-semibold">Inventory Code (OIC)</label>
                        <p class="mb-0">
                            <code>{{ $inventory->oic }}</code>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @if($inventory->files->count() > 0)
            <div class="card mb-4">
                <div class="card-header header-elements">
                    <h5 class="mb-0">File Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="mb-1">{{ $inventory->files->count() }}</h4>
                                <small class="text-muted">Total Files</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="mb-1">{{ $inventory->files->whereIn('mime_type', ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'])->count() }}</h4>
                            <small class="text-muted">Images</small>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- End row -->

@include('administration.inventory.modals.status_update_modal')

@endsection

@section('script_links')
    {{--  External Javascript Links --}}

    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function () {
            // Lightbox configuration
            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true,
                'albumLabel': "Image %1 of %2"
            });
        });
    </script>
@endsection
