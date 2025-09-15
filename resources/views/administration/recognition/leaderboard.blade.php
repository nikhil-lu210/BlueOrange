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
        
        .rankings-list {
            padding: 0 1.5rem 1.5rem;
        }
        
        .ranking-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f3f4;
        }
        
        .ranking-item:last-child {
            border-bottom: none;
        }
        
        .ranking-position {
            width: 30px;
            font-weight: 600;
            color: #6c757d;
            text-align: center;
        }
        
        .ranking-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin: 0 1rem;
            object-fit: cover;
        }
        
        .ranking-info {
            flex: 1;
        }
        
        .ranking-name {
            font-weight: 500;
            margin-bottom: 0;
            color: #333;
        }
        
        .ranking-points {
            color: #6c757d;
            font-weight: 500;
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
                            <!-- 2nd Place -->
                            <div class="top-user second">
                                <div class="rank-badge second">2<sup>nd</sup></div>
                                @if ($topPerformers[1]->hasMedia('avatar'))
                                    <img src="{{ $topPerformers[1]->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="user-avatar">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="user-avatar">
                                @endif
                                <div class="user-name">
                                    <span class="alias-name">{{ $topPerformers[1]->alias_name }}</span>
                                    <br>
                                    <span class="role-name text-muted">{{ $topPerformers[1]->roles->first()->name }}</span>
                                </div>
                                <div class="user-points">{{ $topPerformers[1]->recognition_count ?? $topPerformers[1]->category_count ?? 0 }} recognitions</div>
                            </div>

                            <!-- 1st Place -->
                            <div class="top-user first">
                                <div class="rank-badge first">1<sup>st</sup></div>
                                @if ($topPerformers[0]->hasMedia('avatar'))
                                    <img src="{{ $topPerformers[0]->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="user-avatar">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="user-avatar">
                                @endif
                                <div class="user-name">
                                    <span class="alias-name">{{ $topPerformers[0]->alias_name }}</span>
                                    <br>
                                    <span class="role-name text-muted">{{ $topPerformers[0]->roles->first()->name }}</span>
                                </div>
                                <div class="user-points">{{ $topPerformers[0]->recognition_count ?? $topPerformers[0]->category_count ?? 0 }} recognitions</div>
                            </div>

                            <!-- 3rd Place -->
                            <div class="top-user third">
                                <div class="rank-badge third">3<sup>rd</sup></div>
                                @if ($topPerformers[2]->hasMedia('avatar'))
                                    <img src="{{ $topPerformers[2]->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="user-avatar">
                                @else
                                    <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="user-avatar">
                                @endif
                                <div class="user-name">
                                    <span class="alias-name">{{ $topPerformers[2]->alias_name }}</span>
                                    <br>
                                    <span class="role-name text-muted">{{ $topPerformers[2]->roles->first()->name }}</span>
                                </div>
                                <div class="user-points">
                                    total_marks_here_with_color
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Remaining Performers List -->
                    @if($topPerformers->count() > 3)
                        <div class="rankings-list">
                            @foreach ($topPerformers->skip(3) as $index => $performer)
                                <div class="ranking-item">
                                    <div class="ranking-position">{{ $index + 4 }}</div>
                                    @if ($performer->hasMedia('avatar'))
                                        <img src="{{ $performer->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="ranking-avatar">
                                    @else
                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="ranking-avatar">
                                    @endif
                                    <div class="ranking-info">
                                        <div class="ranking-name">{{ $performer->alias_name }}</div>
                                    </div>
                                    <div class="ranking-points">{{ $performer->recognition_count ?? $performer->category_count ?? 0 }} recognitions</div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($topPerformers->count() < 3)
                        <!-- If less than 3 performers, show in simple list format -->
                        <div class="rankings-list">
                            @foreach ($topPerformers as $index => $performer)
                                <div class="ranking-item">
                                    <div class="ranking-position">{{ $index + 1 }}</div>
                                    @if ($performer->hasMedia('avatar'))
                                        <img src="{{ $performer->getFirstMediaUrl('avatar', 'profile_view_color') }}" alt="Avatar" class="ranking-avatar">
                                    @else
                                        <img src="{{ asset('assets/img/avatars/no_image.png') }}" alt="No Avatar" class="ranking-avatar">
                                    @endif
                                    <div class="ranking-info">
                                        <div class="ranking-name">{{ $performer->alias_name }}</div>
                                    </div>
                                    <div class="ranking-points">{{ $performer->recognition_count ?? $performer->category_count ?? 0 }} recognitions</div>
                                </div>
                            @endforeach
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
