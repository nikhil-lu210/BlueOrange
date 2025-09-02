@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Learning Hub'))

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
    <b class="text-uppercase">{{ __('My Learning Topics') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Learning Hub') }}</li>
    <li class="breadcrumb-item active">{{ __('My Learning Topics') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Learning Topics</h5>

                <div class="card-header-elements ms-auto">
                    @can ('Learning Hub Create')
                        <a href="{{ route('administration.learning_hub.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Learning Topic
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
                                <th>Created At</th>
                                <th>Learning Topic</th>
                                <th>Creator</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($learning_topics as $key => $learning_topic)
                                <tr>
                                    <th>#{{ serial($learning_topics, $key) }}</th>
                                    <td>{{ show_date($learning_topic->created_at) }}</td>
                                    <td>
                                        <b>{{ $learning_topic->title }}</b>
                                        <br>
                                        @if (!is_null($learning_topic->recipients))
                                            <small class="text-primary text-bold cursor-pointer text-left" title="
                                                @foreach ($learning_topic->recipients as $index => $recipient)
                                                    @if ($index < 9)
                                                        <small>{{ show_employee_data($recipient, 'alias_name') }}</small>
                                                        <br>
                                                    @elseif ($index == 9)
                                                        @php
                                                            $remainingCount = count($learning_topic->recipients) - 9;
                                                        @endphp
                                                        {{ $remainingCount }} More
                                                        @break
                                                    @endif
                                                @endforeach
                                            ">
                                                {{ count($learning_topic->recipients) }} Recipients
                                            </small>
                                        @else
                                            <small class="text-muted">All Recipients</small>
                                        @endif
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($learning_topic->creator, name: null) !!}
                                    </td>
                                    <td class="text-center">
                                        @can ('Learning Hub Delete')
                                            <a href="{{ route('administration.learning_hub.destroy', ['learning_topic' => $learning_topic]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Learning Topic?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Learning Hub Update')
                                            <a href="{{ route('administration.learning_hub.edit', ['learning_topic' => $learning_topic]) }}" class="btn btn-sm btn-icon btn-info" data-bs-toggle="tooltip" title="Edit Learning Topic?">
                                                <i class="text-white ti ti-pencil"></i>
                                            </a>
                                        @endcan
                                        @can ('Learning Hub Read')
                                            <a href="{{ route('administration.learning_hub.show', ['learning_topic' => $learning_topic]) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
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
    </script>
@endsection
