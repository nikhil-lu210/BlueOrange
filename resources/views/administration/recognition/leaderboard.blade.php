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

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .top-users {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            gap: 5rem;
            padding: 3rem 1.5rem 2rem;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
        }
        
        .top-user {
            text-align: center;
            position: relative;
        }
        
        .top-user.first {
            order: 2;
            transform: scale(1.1);
        }
        
        .top-user.second {
            order: 1;
        }
        
        .top-user.third {
            order: 3;
        }
        
        .user-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 0.5rem;
            border: 4px solid #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            object-fit: cover;
        }
        
        .top-user.first .user-avatar {
            width: 150px;
            height: 150px;
            border-color: #ffd700;
        }
        
        .top-user.second .user-avatar {
            width: 120px;
            height: 120px;
            border-color: #a8aaae;
        }
        
        .top-user.third .user-avatar {
            width: 100px;
            height: 100px;
            border-color: #cd7f32;
        }
        
        .rank-badge {
            position: absolute;
            top: -20px;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }
        
        .rank-badge.first {
            background: #ffd700;
            color: #333;
        }
        
        .rank-badge.second {
            background: #c0c0c0;
            color: #333;
        }
        
        .rank-badge.third {
            background: #cd7f32;
        }
        
        .user-name {
            font-weight: 600;
            margin-bottom: 0.25rem;
            color: #333;
        }
        
        .user-points {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        
        @media (max-width: 768px) {
            .top-users {
                flex-direction: column;
                gap: 1.5rem;
            }
            
            .top-user {
                order: initial !important;
                transform: none !important;
            }
        }
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
@include('administration.recognition.includes._filter_leaderboard')

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <i class="ti ti-trophy me-2"></i>
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
            <div class="card-body p-0">
                @if($topPerformers->count() > 0)
                    <!-- Top 3 Podium Display -->
                    @if($topPerformers->count() >= 3)
                        <div class="top-users">
                            @foreach([0, 1, 2] as $position)
                                @php
                                    $performer = $topPerformers[$position];
                                    $rankClass = ['first', 'second', 'third'][$position];
                                    $rankText = ['1<sup>st</sup>', '2<sup>nd</sup>', '3<sup>rd</sup>'][$position];
                                    $recognitionCount = $performer->recognition_count ?? $performer->category_count ?? 0;
                                    $totalScore = $performer->total_score ?? $performer->category_score ?? 0;
                                @endphp
                                <div class="top-user {{ $rankClass }}">
                                    <div class="rank-badge {{ $rankClass }}">{!! $rankText !!}</div>
                                    @if ($performer->hasMedia('avatar'))
                                        <img src="{{ $performer->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="user-avatar">
                                    @else
                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="user-avatar">
                                    @endif
                                    <div class="user-name">
                                        <span class="alias-name">{{ $performer->alias_name }}</span>
                                        <br>
                                        <span class="role-name text-muted">{{ $performer->roles->first()->name ?? 'Employee' }}</span>
                                    </div>
                                    <div class="user-points badge bg-primary text-white" title="For {{ $recognitionCount }} Recognitions">{{ $totalScore }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Remaining Performers Table -->
                    @if($topPerformers->count() > 3)
                        <div class="table-responsive mt-3">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach ($topPerformers->skip(3) as $index => $performer)
                                        @php
                                            $recognitionCount = $performer->recognition_count ?? $performer->category_count ?? 0;
                                            $totalScore = $performer->total_score ?? $performer->category_score ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <span class="badge bg-label-primary text-primary fs-6 fw-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td>
                                                {!! show_user_name_and_avatar($performer, role: null) !!}
                                            </td>
                                            <td>
                                                <div class="fw-bold text-primary fs-5">{{ $recognitionCount }} Recognitions</div>
                                            </td>
                                            <td>
                                                <small class="badge bg-label-primary fs-6 fw-bold">{{ $totalScore }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @elseif($topPerformers->count() < 3)
                        <!-- If less than 3 performers, show in table format -->
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tbody>
                                    @foreach ($topPerformers as $index => $performer)
                                        @php
                                            $recognitionCount = $performer->recognition_count ?? $performer->category_count ?? 0;
                                            $totalScore = $performer->total_score ?? $performer->category_score ?? 0;
                                        @endphp
                                        <tr>
                                            <td class="text-center" style="width: 60px;">
                                                <span class="badge bg-label-primary text-primary fs-6 fw-bold">
                                                    {{ $index + 1 }}
                                                </span>
                                            </td>
                                            <td style="width: 50px;">
                                                @if ($performer->hasMedia('avatar'))
                                                    <img src="{{ $performer->getFirstMediaUrl('avatar', 'profile_view_color') }}" 
                                                         alt="Avatar" class="rounded-circle" width="40" height="40">
                                                @else
                                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" 
                                                         alt="No Avatar" class="rounded-circle" width="40" height="40">
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $performer->alias_name }}</h6>
                                                    <small class="text-muted">{{ $performer->roles->first()->name ?? 'Employee' }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="fw-bold text-primary fs-5">{{ $recognitionCount }} recognitions</div>
                                                <small class="text-muted">{{ $totalScore }} total score</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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

<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
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

            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>
@endsection
