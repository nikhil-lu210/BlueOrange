@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Recognition Leaderboard'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .leaderboard-item {
            border-left: 4px solid #007bff;
            transition: all 0.3s ease;
        }
        .leaderboard-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .leaderboard-item.first {
            border-left-color: #FFD700;
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: white;
        }
        .leaderboard-item.second {
            border-left-color: #C0C0C0;
            background: linear-gradient(135deg, #C0C0C0, #A8A8A8);
            color: white;
        }
        .leaderboard-item.third {
            border-left-color: #CD7F32;
            background: linear-gradient(135deg, #CD7F32, #B8860B);
            color: white;
        }
        .rank-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
        }
        .rank-1 { background: #FFD700; color: #000; }
        .rank-2 { background: #C0C0C0; color: #000; }
        .rank-3 { background: #CD7F32; color: #fff; }
        .rank-other { background: #6c757d; color: #fff; }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Recognition Leaderboard') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.recognition.index') }}">{{ __('All Recognitions') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Leaderboard') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.recognition.leaderboard') }}" method="get" autocomplete="off">
            <div class="card card-border-shadow-primary mb-4 border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Filter Leaderboard') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="category" class="form-label">Filter by Category</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="" {{ is_null($category) ? 'selected' : '' }}>All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ $cat == $category ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="limit" class="form-label">Number of Results</label>
                            <select name="limit" id="limit" class="form-select @error('limit') is-invalid @enderror">
                                <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>Top 10</option>
                                <option value="20" {{ request('limit', 10) == 20 ? 'selected' : '' }}>Top 20</option>
                                <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>Top 50</option>
                            </select>
                            @error('limit')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-5 d-flex align-items-end">
                            @if ($category)
                                <a href="{{ route('administration.recognition.leaderboard') }}" class="btn btn-danger me-2">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    Reset Filters
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                Apply Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    @if($category)
                        {{ $category }} Recognition Leaderboard
                    @else
                        Overall Recognition Leaderboard
                    @endif
                </h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.recognition.analytics') }}" class="btn btn-sm btn-info">
                        <span class="tf-icon ti ti-chart-bar ti-xs me-1"></span>
                        Analytics
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($topPerformers->count() > 0)
                    <div class="row">
                        @foreach ($topPerformers as $index => $performer)
                            <div class="col-md-6 mb-3">
                                <div class="card leaderboard-item {{ $index < 3 ? ['first', 'second', 'third'][$index] : '' }}">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="rank-badge rank-{{ $index < 3 ? $index + 1 : 'other' }} me-3">
                                                {{ $index + 1 }}
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center mb-2">
                                                    @if ($performer->hasMedia('avatar'))
                                                        <img src="{{ $performer->getFirstMediaUrl('avatar', 'thumb') }}" 
                                                             alt="Avatar" class="rounded-circle me-2" width="40" height="40">
                                                    @else
                                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" 
                                                             alt="No Avatar" class="rounded-circle me-2" width="40" height="40">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $performer->alias_name }}</h6>
                                                        <small class="text-muted">{{ $performer->employee->designation ?? 'Employee' }}</small>
                                                    </div>
                                                </div>
                                                
                                                <div class="row text-center">
                                                    <div class="col-4">
                                                        <div class="fw-bold fs-5">{{ $performer->total_score ?? 0 }}</div>
                                                        <small>Total Score</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="fw-bold fs-5">{{ $performer->recognition_count ?? 0 }}</div>
                                                        <small>Recognitions</small>
                                                    </div>
                                                    <div class="col-4">
                                                        <div class="fw-bold fs-5">{{ number_format($performer->avg_score ?? 0, 1) }}</div>
                                                        <small>Avg Score</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-trophy" style="font-size: 4rem; color: #ddd;"></i>
                        </div>
                        <h5 class="text-muted">No Leaderboard Data</h5>
                        <p class="text-muted">
                            @if($category)
                                No recognitions found for {{ $category }} category.
                            @else
                                No recognition data available for leaderboard.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Recognition Categories Overview -->
@if(!$category)
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">Category-wise Top Performers</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach ($categories as $cat)
                        <div class="col-md-4 mb-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $cat }}</h6>
                                    <a href="{{ route('administration.recognition.leaderboard', ['category' => $cat]) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        View {{ $cat }} Leaders
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
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
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) {
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection
