{{-- ERS Monthly Summary --}}
<div class="row mb-4">
    <div class="col-md-12 mb-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ __('Top Performers ('.now()->format('F Y').')') }}</h5>
            </div>
            <div class="card-body">
                @if ($top10TeamRecognitions->isEmpty())
                    <div class="d-flex justify-content-center align-items-center" style="min-height: 60px;">
                        <div class="text-center">
                            <div class="fs-xl text-muted mb-3">{{ __('No data available.') }}</div>
                            <a href="{{ route('administration.employee_recognition.index', ['month' => now()->format('Y-m-d')]) }}" class="btn btn-primary btn-md">
                                {{ __('Recognize Now') }}
                            </a>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @foreach ($top10TeamRecognitions->chunk(5) as $column)
                            <div class="col-md-6">
                                <div class="list-group">
                                    @foreach ($column as $recognition)
                                        @php $badgeCode = ers_badge_for_score($recognition->total_score)['code']; @endphp
                                        <div class="list-group-item list-group-item-action d-flex align-items-center gap-3">
                                            {!! show_user_avatar($recognition->employee, 'rounded', 60) !!}
                                            <div class="w-100 d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">
                                                        <a href="{{ route('administration.settings.user.show.profile', $recognition->employee) }}" class="text-bold">
                                                            {{ $recognition->employee->alias_name }}
                                                        </a>
                                                    </h6>
                                                    <small class="mb-1">{{ $recognition->employee->roles()->first()->name }}</small>
                                                    <div class="user-status">
                                                        <span class="badge badge-dot {{ ers_badge_class($badgeCode) }}"></span>
                                                        <small>Team - {{ $recognition->teamLeader->alias_name }}</small>
                                                    </div>
                                                </div>
                                                <span class="badge {{ ers_badge_class($badgeCode) }}">{{ show_badge($badgeCode) }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
