@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Holidays'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Holidays') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('System Settings') }}</li>
    <li class="breadcrumb-item active">{{ __('All Holidays') }}</li>
@endsection


@section('content')

<!-- Start row -->

<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
    </script>
@endsection
