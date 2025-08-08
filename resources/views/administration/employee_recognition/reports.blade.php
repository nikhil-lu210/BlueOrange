@extends('layouts.administration.app')

@section('page_title', __('ERS Reports'))

@section('page_name')
<b class="text-uppercase">{{ __('ERS Reports') }}</b>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Top Performers & Team Comparison') }}</h5>
        <form method="GET" class="d-flex align-items-center gap-2">
          <input type="month" class="form-control" name="month" value="{{ $month->format('Y-m') }}">
          <select name="badge" class="form-select">
            <option value="">{{ __('All Badges') }}</option>
            @foreach($badgeOptions as $code => $text)
              <option value="{{ $code }}" {{ (isset($badge) && $badge === $code) ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
          </select>
          <button class="btn btn-secondary" type="submit">{{ __('Load') }}</button>
        </form>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-7">
            <h6>{{ __('Top Performers') }}</h6>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>{{ __('Employee') }}</th>
                    <th>{{ __('Team Leader') }}</th>
                    <th>{{ __('Score') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($topPerformers as $i => $row)
                    <tr>
                      <td>{{ $i + 1 }}</td>
                      <td>{!! show_user_name_and_avatar($row->employee, role: null) !!}</td>
                      <td>{!! show_user_name_and_avatar($row->teamLeader, name: null) !!}</td>
                      <td>
                        <span class="badge bg-primary">{{ $row->total_score }}</span>
                        @php $bd = $topBadges[$row->id] ?? null; @endphp
                        <span class="badge bg-label-dark">{{ $bd ? ($bd['emoji'].' '. __($bd['label'])) : '' }}</span>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="4" class="text-center text-muted">{{ __('No data') }}</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-5">
            <h6>{{ __('Team Comparison (Avg Score)') }}</h6>
            <div class="table-responsive">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th>{{ __('Team Leader') }}</th>
                    <th>{{ __('Avg Score') }}</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($teamComparison as $row)
                    <tr>
                      <td>{!! show_user_name_and_avatar($row->teamLeader, name: null, role: null) !!}</td>
                      <td><span class="badge bg-info">{{ number_format($row->avg_score, 2) }}</span></td>
                    </tr>
                  @empty
                    <tr><td colspan="2" class="text-center text-muted">{{ __('No data') }}</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
