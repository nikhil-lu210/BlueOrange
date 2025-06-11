@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Penalties'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    <style>
        .penalty-card {
            border: 1px solid #e7eaf3;
            border-radius: 12px;
            box-shadow: 0 0.25rem 1.125rem rgba(75, 70, 92, 0.05);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .penalty-card:hover {
            border-color: #696cff;
            box-shadow: 0 4px 12px rgba(105, 108, 255, 0.15);
        }
        .penalty-type-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
        .penalty-time-badge {
            font-size: 0.875rem;
            font-weight: 600;
        }
        .attendance-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 12px;
        }
        .no-penalties {
            text-align: center;
            padding: 60px 20px;
            color: #8592a3;
        }
        .no-penalties i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('My Penalties') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('administration.dashboard.index') }}">{{ __('Dashboard') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('My Penalties') }}</li>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ti ti-gavel me-2"></i>{{ __('My Penalties') }}
                </h5>
                <div class="d-flex align-items-center">
                    <span class="badge bg-label-dark me-2">{{ __('Total Penalties: ') }} {{ $penalties->count() }}</span>
                    @if($penalties->count() > 0)
                        <span class="badge bg-dark">
                            {{ __('Total Time: ') }}{{ $total_penalty_time }}
                        </span>
                    @endif
                </div>
            </div>

            <div class="card-body">
                @if($penalties->count() > 0)
                    <div class="row">
                        @foreach($penalties as $penalty)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="penalty-card">
                                    <div class="card-body">
                                        <!-- Penalty Header -->
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <span class="badge bg-danger penalty-type-badge">
                                                {{ $penalty->type }}
                                            </span>
                                            <span class="badge bg-dark penalty-time-badge">
                                                {{ $penalty->total_time_formatted }}
                                            </span>
                                        </div>

                                        <!-- Attendance Information -->
                                        <div class="attendance-info mb-3">
                                            <h6 class="mb-2">
                                                <i class="ti ti-calendar me-1"></i>{{ __('Attendance Details') }}
                                                <strong class="float-right">{{ $penalty->attendance->type }}</strong>
                                            </h6>
                                            <div class="row mt-3">
                                                <div class="col-12">
                                                    <small class="text-muted">{{ __('Date:') }}</small><br>
                                                    <strong>{{ show_date($penalty->attendance->clock_in_date) }}</strong>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-6">
                                                    <small class="text-muted">{{ __('Clock In:') }}</small><br>
                                                    <strong>{{ show_time($penalty->attendance->clock_in) }}</strong>
                                                </div>
                                                <div class="col-6 text-right">
                                                    <small class="text-muted">{{ __('Clock Out:') }}</small><br>
                                                    <strong>
                                                        {{ $penalty->attendance->clock_out ? show_time($penalty->attendance->clock_out) : __('Ongoing') }}
                                                    </strong>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Footer Information -->
                                        <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                                            <div>
                                                {!! show_user_name_and_avatar($penalty->creator, name: null) !!}
                                            </div>
                                            <div class="text-end">
                                                <a href="{{ route('administration.penalty.show', $penalty) }}"
                                                   class="btn btn-sm btn-icon btn-outline-primary" target="_blank" title="{{ __('View Details') }}">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="no-penalties">
                        <i class="ti ti-mood-happy"></i>
                        <h4>{{ __('No Penalties Found') }}</h4>
                        <p class="mb-0">{{ __('You have no penalties recorded. Keep up the good work!') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External Javascript Links  --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
@endsection
