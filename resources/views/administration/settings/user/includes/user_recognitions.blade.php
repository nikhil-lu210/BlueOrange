@extends('administration.settings.user.show')

@section('css_links_user_show')
@endsection

@section('profile_content')

<!-- User Recognitions -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-body row">
                <div class="d-flex justify-content-between flex-wrap gap-3 me-3">
                    @foreach($avgCriteriaOutput as $row)
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-{{ $row['color'] }} p-2 rounded">
                                <i class="ti ti-star ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-{{ $row['color'] }} mb-0">{{ number_format($row['value'], 2) }}/20</h5>
                                <small class="mb-0 text-muted">{{ __($row['label']) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <h5 class="card-header">{{ __('Badge Timeline') }}</h5>
            <div class="card-body">
                @if($timelinePrepared->isEmpty())
                    <p class="text-muted mb-0">{{ __('No monthly evaluations yet.') }}</p>
                @else
                    <div class="row g-2">
                        @foreach($timelinePrepared as $item)
                            <div class="col-md-3">
                                <div class="border rounded p-2 h-100">
                                    <small class="text-muted d-block">{{ $item['month_label'] }}</small>
                                    <div class="d-flex align-items-center justify-content-between mt-1">
                                        <span class="badge bg-primary">{{ $item['total_score'] }}/100</span>
                                        <span class="badge bg-label-dark">{{ $item['badge_emoji'] }} {{ __($item['badge_label']) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card">
            <h5 class="card-header">{{ __('Monthly Evaluations') }}</h5>
            <div class="card-body pb-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead>
                        <tr>
                            <th>{{ __('Month') }}</th>
                            <th>{{ __('Team Leader') }}</th>
                            <th>{{ __('Behavior') }}</th>
                            <th>{{ __('Appreciation') }}</th>
                            <th>{{ __('Leadership') }}</th>
                            <th>{{ __('Loyalty') }}</th>
                            <th>{{ __('Dedication') }}</th>
                            <th>{{ __('Total') }}</th>
                            <th>{{ __('Badge') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($evaluationsPrepared as $row)
                            <tr>
                                <td>{{ $row['month_label'] }}</td>
                                <td>{!! show_user_name_and_avatar($row['team_leader'], name: null, role: null) !!}</td>
                                <td>{{ $row['behavior'] }}</td>
                                <td>{{ $row['appreciation'] }}</td>
                                <td>{{ $row['leadership'] }}</td>
                                <td>{{ $row['loyalty'] }}</td>
                                <td>{{ $row['dedication'] }}</td>
                                <td><span class="badge bg-primary">{{ $row['total_score'] }}</span></td>
                                <td><span class="badge {{ $row['badge']['class'] }}">{{ $row['badge']['emoji'] }} {{ __($row['badge']['label']) }}</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">{{ __('No evaluations found.') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ User Recognitions -->


@endsection
