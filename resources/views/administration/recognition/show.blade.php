@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Recognition Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .marks {
            width: 80px;
            height: 80px;
            display: grid;
            grid-template-areas:
                "got divider"
                "divider total";
            place-items: center;
            position: relative;
            background: #f5f4ff;
            border: 2px solid #b7b4f2;
            border-radius: 8px;
            font-family: system-ui, sans-serif;
        }
        .marks .mark-got {
            grid-area: got;
            font-size: 24px;
            font-weight: 700;
            color: #2c2c54;
            justify-self: start;
            align-self: start;
            margin: -12px;
        }
        .marks .total-mark {
            grid-area: total;
            font-size: 18px;
            font-weight: 500;
            color: #555;
            justify-self: end;
            align-self: end;
            margin: 8px;
        }
        .marks::before {
            content: "";
            position: absolute;
            width: 120%;
            height: 2px;
            background: #b7b4f2;
            transform: rotate(-45deg);
        }
        .recognition-card {
            border-left: 4px solid #b7b4f2;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Recognition Details') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.recognition.index') }}">{{ __('All Recognitions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Recognition Details') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-8">
        <div class="card card-border-shadow-primary recognition-card mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Recognition Details</h5>

                <div class="card-header-elements ms-auto">
                    @can ('Recognition Update')
                        <a href="{{ route('administration.recognition.edit', $recognition) }}" class="btn btn-sm btn-info">
                            <span class="tf-icon ti ti-pencil ti-xs me-1"></span>
                            Edit Recognition
                        </a>
                    @endcan
                    @can ('Recognition Delete')
                        <a href="{{ route('administration.recognition.destroy', $recognition) }}" class="btn btn-sm btn-danger confirm-danger">
                            <span class="tf-icon ti ti-trash ti-xs me-1"></span>
                            Delete Recognition
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        <div class="marks mx-auto">
                            <span class="mark-got">{{ $recognition->total_mark }}</span>
                            <span class="total-mark">{{ config('recognition.marks.max') }}</span>
                        </div>
                        <h6 class="mt-2 text-muted">Recognition Score</h6>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Employee</label>
                                <div class="d-flex align-items-center">
                                    {!! show_user_name_and_avatar($recognition->user, name: null) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Recognizer</label>
                                <div class="d-flex align-items-center">
                                    {!! show_user_name_and_avatar($recognition->recognizer, name: null) !!}
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <div>
                                    <span class="badge bg-primary fs-6">{{ $recognition->category }}</span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Recognition Date</label>
                                <div>
                                    <b>{{ show_date($recognition->created_at) }}</b>
                                    <br>
                                    <small class="text-muted">{{ show_time($recognition->created_at) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">Recognition Comment</label>
                        <div class="card bg-light">
                            <div class="card-body">
                                {!! $recognition->comment !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recognition Statistics -->
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Employee Recognition Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $recognition->user->received_recognitions->count() }}</h3>
                                <small>Total Recognitions</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <h3 class="mb-0">{{ number_format($recognition->user->received_recognitions->avg('total_mark'), 1) }}</h3>
                                <small>Average Score</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $recognition->user->received_recognitions->sum('total_mark') }}</h3>
                                <small>Total Score</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <h3 class="mb-0">{{ $recognition->user->received_recognitions->max('total_mark') ?? 0 }}</h3>
                                <small>Highest Score</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Recognitions for this Employee -->
@if($recognition->user->received_recognitions->count() > 1)
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Other Recognitions for {{ $recognition->user->alias_name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Category</th>
                                <th>Score</th>
                                <th>Recognizer</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recognition->user->received_recognitions->where('id', '!=', $recognition->id)->take(5) as $otherRecognition)
                                <tr>
                                    <td>{{ show_date($otherRecognition->created_at) }}</td>
                                    <td><span class="badge bg-secondary">{{ $otherRecognition->category }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="fw-bold">{{ $otherRecognition->total_mark }}</span>
                                            <small class="text-muted ms-1">/ {{ config('recognition.marks.max') }}</small>
                                        </div>
                                    </td>
                                    <td>{!! show_user_name_and_avatar($otherRecognition->recognizer, name: null) !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

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
