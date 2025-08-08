{{-- ERS Monthly Summary --}}
<div class="row mb-4">
  @if($isTeamLeader)
    <div class="col-lg-7 col-md-12 mb-3">
      <div class="card h-100">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('Top Performers (This Month)') }}</h5>
          <a href="{{ route('administration.employee_recognition.monthly.index', ['month' => now()->format('Y-m-01')]) }}" class="btn btn-sm btn-primary">{{ __('Evaluate Now') }}</a>
        </div>
        <div class="card-body">
          @if(!$tlHasMonthEval)
            <div class="alert alert-warning d-flex align-items-center" role="alert">
              <i class="ti ti-alert-triangle me-2"></i>
              <div>
                {{ __('You have not submitted this month\'s evaluations. Please evaluate your team members.') }}
                <a href="{{ route('administration.employee_recognition.monthly.index', ['month' => now()->format('Y-m-01')]) }}" class="btn btn-sm btn-outline-primary ms-2">{{ __('Go to Evaluation') }}</a>
              </div>
            </div>
          @endif

          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>#</th>
                  <th>{{ __('Employee') }}</th>
                  <th>{{ __('Score') }}</th>
                  <th>{{ __('Badge') }}</th>
                </tr>
              </thead>
              <tbody>
                @forelse($tlTop5 as $i => $e)
                  @php $bd = ers_badge_for_score((int)$e->total_score); @endphp
                  <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{!! show_user_name_and_avatar($e->employee, name: null, role: null) !!}</td>
                    <td><span class="badge bg-primary">{{ $e->total_score }}</span></td>
                    <td><span class="badge {{ ers_badge_class($bd['code']) }}">{{ $bd['emoji'] }} {{ __($bd['label']) }}</span></td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="text-center text-muted">{{ __('No evaluations yet.') }}</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  @endif

  <div class="col-lg-{{ $isTeamLeader ? '5' : '12' }} col-md-12 mb-3">
    <div class="card h-100">
      <div class="card-header">
        <h5 class="mb-0">{{ __('Recognition Summary') }}</h5>
      </div>
      <div class="card-body">
        @if($employeeEval && $employeeBadge)
          <div class="d-flex align-items-center">
            {!! show_user_name_and_avatar($user, name: null, role: null) !!}
            <div class="ms-3">
              <div class="mb-1">{{ __('Congratulations!') }}</div>
              <div>
                @php $monthLabel = \Illuminate\Support\Carbon::parse($employeeEval->month)->format('F Y'); @endphp
                <span class="badge {{ ers_badge_class($employeeBadge['code']) }}">{{ $employeeBadge['emoji'] }} {{ __($employeeBadge['label']) }}</span>
                <span class="ms-2">{{ __('for') }} {{ $monthLabel }} ({{ $employeeEval->total_score }}/100)</span>
              </div>
              <div class="text-muted small mt-1">{{ __('Team Leader') }}: {!! show_user_name_and_avatar($employeeEval->teamLeader, name: null, role: null) !!}</div>
            </div>
          </div>
        @else
          <p class="text-muted mb-0">{{ __('No recognition available yet.') }}</p>
        @endif
      </div>
    </div>
  </div>
</div>
