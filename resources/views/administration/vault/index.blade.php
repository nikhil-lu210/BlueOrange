@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Credential'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Credentials') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Credential') }}</li>
    <li class="breadcrumb-item active">{{ __('All Credentials') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">All Credentials</h5>
        
                <div class="card-header-elements ms-auto">
                    @can ('Vault Create') 
                        <a href="{{ route('administration.vault.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Store Credential
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Name</th>
                                <th class="text-center">Username/Email</th>
                                <th class="text-center">Password</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vaults as $key => $vault) 
                                <tr>
                                    <th>#{{ serial($vaults, $key) }}</th>
                                    <td>
                                        <b>{{ $vault->name }}</b>
                                        @isset ($vault->url) 
                                            <br>
                                            <small>
                                                <a href="{{ $vault->url }}" target="_blank" class="text-bold text-primary">{{ $vault->url }}</a>
                                            </small>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy" data-copy="{{ $vault->username }}">
                                            <i class="ti ti-copy"></i> Copy Username
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy" data-copy="{{ $vault->password }}">
                                            <i class="ti ti-copy"></i> Copy Password
                                        </button>
                                    </td>                                    
                                    <td class="text-center">
                                        @can ('Vault Delete') 
                                            <a href="{{ route('administration.vault.destroy', ['vault' => $vault]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Vault?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Vault Update') 
                                            <a href="{{ route('administration.vault.edit', ['vault' => $vault]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Vault?">
                                                <i class="text-white ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can ('Vault Read') 
                                            <a href="{{ route('administration.vault.show', ['vault' => $vault]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function () {
            $('.data-table').on('click', '.copy-btn', function () {
                const button = $(this);
                const textToCopy = button.data('copy'); // Get text from data-copy attribute

                // Copy to clipboard
                navigator.clipboard.writeText(textToCopy).then(() => {
                    // Change button text to "Copied"
                    const originalText = button.html();
                    button.html('<i class="ti ti-check"></i> Copied');

                    // Revert back to original text after 5 seconds
                    setTimeout(() => {
                        button.html(originalText);
                    }, 5000);
                }).catch(err => {
                    alert('Failed to copy text: ', err);
                });
            });
        });
    </script>
@endsection
