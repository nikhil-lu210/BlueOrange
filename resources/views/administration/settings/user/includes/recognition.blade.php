@extends('administration.settings.user.show')

@section('css_links_user_show')
    <style>
        .recognition-card {
            transition: all 0.3s ease;
        }

        .recognition-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('profile_content')

<!-- Recognition Statistics -->
@if ($user->received_recognitions->count() > 0)
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card card-border-shadow-primary border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">Recognition Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-primary border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-primary p-2 rounded">
                                        <i class="ti ti-award ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-primary mb-0">{{ $user->received_recognitions->count() }}</h5>
                                        <small class="mb-0 text-muted">Total Recognitions</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-success border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-success p-2 rounded">
                                        <i class="ti ti-sum ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-success mb-0">{{ $user->received_recognitions->sum('total_mark') }}</h5>
                                        <small class="mb-0 text-muted">Total Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-warning border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-warning p-2 rounded">
                                        <i class="ti ti-trophy ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-warning mb-0">{{ $user->received_recognitions->max('total_mark') ?? 0 }}</h5>
                                        <small class="mb-0 text-muted">Highest Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 text-center mb-3">
                        <div class="card bg-label-dark border-0">
                            <div class="card-body">
                                <div class="d-flex align-items-center gap-3">
                                    <span class="bg-label-dark p-2 rounded">
                                        <i class="ti ti-chart-line ti-xl"></i>
                                    </span>
                                    <div class="content-right">
                                        <h5 class="text-dark mb-0">{{ number_format($user->received_recognitions->avg('total_mark'), 1) }}</h5>
                                        <small class="mb-0 text-muted">Average Score</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- User recognitions -->
<div class="row">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('All Recognitions') }}</h5>
                <div class="card-header-elements ms-auto">
                    @if ($user->received_recognitions->count() > 0)
                        <a href="{{ route('administration.recognition.leaderboard') }}" class="btn btn-sm btn-success">
                            <span class="tf-icon ti ti-trophy ti-xs me-1"></span>
                            View Leaderboard
                        </a>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if ($user->received_recognitions->count() > 0)
                    <div class="row g-3">
                        @php
                            $totalMarks = config('recognition.marks.max');
                        @endphp
                        @foreach ($user->received_recognitions->sortByDesc('created_at') as $sl => $recognition)
                            @php
                                $scorePercentage = ($recognition->total_mark / $totalMarks) * 100;
                                $scoreBgClass = 'bg-label-secondary';
                                $borderClass = 'card-border-shadow-secondary';
                                if ($scorePercentage >= 90) {
                                    $scoreBgClass = 'bg-label-success';
                                    $borderClass = 'card-border-shadow-success';
                                } elseif ($scorePercentage >= 80) {
                                    $scoreBgClass = 'bg-label-primary';
                                    $borderClass = 'card-border-shadow-primary';
                                } elseif ($scorePercentage >= 70) {
                                    $scoreBgClass = 'bg-label-warning';
                                    $borderClass = 'card-border-shadow-warning';
                                } elseif ($scorePercentage >= 60) {
                                    $scoreBgClass = 'bg-label-info';
                                    $borderClass = 'card-border-shadow-info';
                                } else {
                                    $scoreBgClass = 'bg-label-danger';
                                    $borderClass = 'card-border-shadow-danger';
                                }
                            @endphp
                            <div class="col-md-6 col-lg-4">
                                <div class="card recognition-card h-100 {{ $borderClass }}">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div class="recognition-category">
                                                @if($recognition->category == 'Behavior')
                                                    <span class="badge bg-label-primary text-primary">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Appreciation')
                                                    <span class="badge bg-label-info text-info">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Leadership')
                                                    <span class="badge bg-label-warning text-warning">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Loyalty')
                                                    <span class="badge bg-label-success text-success">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Dedication')
                                                    <span class="badge bg-label-secondary text-secondary">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Teamwork')
                                                    <span class="badge bg-label-dark text-dark">{{ $recognition->category }}</span>
                                                @elseif($recognition->category == 'Innovation')
                                                    <span class="badge bg-label-danger text-danger">{{ $recognition->category }}</span>
                                                @else
                                                    <span class="badge bg-label-primary text-primary">{{ $recognition->category }}</span>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">{{ show_date($recognition->created_at) }}</small>
                                                <br>
                                                <small class="text-muted">{{ show_time($recognition->created_at) }}</small>
                                            </div>
                                        </div>

                                        <hr>
                                        
                                        <div class="mb-3">
                                            <small class="card-text text-muted">
                                                {!! show_content(strip_tags($recognition->comment), 120) !!}
                                            </small>
                                        </div>
                                        
                                        <div class="d-flex align-items-center justify-content-between mt-auto">
                                            <div class="d-flex align-items-center" title="{{ __('Recognized By') }}">
                                                {!! show_user_name_and_avatar($recognition->recognizer, name: null) !!}
                                            </div>
                                            <div class="text-muted">
                                                <small class="badge {{ $scoreBgClass }}">{{ $recognition->total_mark }}/{{ $totalMarks }}</small>
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
                            <i class="ti ti-award" style="font-size: 4rem; color: #ddd;"></i>
                        </div>
                        <h5 class="text-muted">No Recognitions Yet</h5>
                        <p class="text-muted">This employee hasn't received any recognitions yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--/ User recognitions -->

@endsection


@section('script_links_user_show')
    {{-- Lightbox JS --}}
@endsection
