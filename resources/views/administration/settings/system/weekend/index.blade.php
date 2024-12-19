@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Weekends'))

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
    <b class="text-uppercase">{{ __('All Weekends') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Role & Permission') }}</li>
    <li class="breadcrumb-item">{{ __('Permission') }}</li>
    <li class="breadcrumb-item active">{{ __('All Weekends') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center mt-5">
    @foreach ($weekends as $key => $weekend) 
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 @if ($weekend->is_active) card-border-shadow-primary @endif">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="card-title mb-0">
                        <h5 class="mb-0 me-2">{{ $weekend->day }}</h5>
                    </div>
                    @canany (['Weekend Create', 'Weekend Update']) 
                        <div class="card-icon">
                            <form action="{{ route('administration.settings.system.weekend.update', ['weekend' => $weekend]) }}" method="POST" id="form-{{ $weekend->id }}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id" value="{{ $weekend->id }}">
                                <label class="switch switch-square" style="margin-right: 2rem;">
                                    <input type="checkbox" name="is_active" value="1" class="switch-input" @checked($weekend->is_active) onchange="document.getElementById('form-{{ $weekend->id }}').submit();">
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"><i class="ti ti-check"></i></span>
                                        <span class="switch-off"><i class="ti ti-x"></i></span>
                                    </span>
                                </label>
                            </form>
                        </div>
                    @endcanany
                </div>
            </div>
        </div>
    @endforeach
</div>
<!-- End row -->


{{-- Page Modal --}}
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