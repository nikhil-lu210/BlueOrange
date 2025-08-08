@extends('layouts.administration.app')

@section('page_title', __('Monthly Leaderboard'))

@section('page_name')
<b class="text-uppercase">{{ __('Monthly Leaderboard') }}</b>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Leaderboard for') }} {{ $month->format('F Y') }}</h5>
        <form method="GET" action="{{ route('administration.employee_recognition.leaderboard') }}" class="d-flex align-items-center gap-2">
          <input type="month" class="form-control" name="month" value="{{ $month->format('Y-m') }}">
          <select name="badge" class="form-select">
            <option value="">{{ __('All Badges') }}</option>
            @foreach($badgeOptions as $code => $text)
              <option value="{{ $code }}" {{ (isset($badge) && $badge === $code) ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
          </select>
          <button class="btn btn-secondary" type="submit">{{ __('Load') }}</button>
          <a href="{{ route('administration.employee_recognition.index', ['month' => $month->format('Y-m-d')]) }}" class="btn btn-primary">{{ __('Back to Recognitions') }}</a>
        </form>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-hover align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ __('Employee') }}</th>
                <th>{{ __('Total Score') }}</th>
                <th>{{ __('Badge') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($leaderboard as $i => $row)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td class="d-flex align-items-center gap-2">{!! show_user_name_and_avatar($row->employee, name: null, role: null) !!}</td>
                  <td><span class="badge bg-primary">{{ $row->total_score }}</span></td>
                  <td>
                    @php $bd = $rowBadges[$row->id] ?? null; @endphp
                    <span class="badge {{ $bd ? $bd['class'] : 'bg-secondary' }}">{{ $bd ? ($bd['emoji'].' '. __($bd['label'])) : '-' }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center text-muted">{{ __('No recognitions for this month.') }}</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
