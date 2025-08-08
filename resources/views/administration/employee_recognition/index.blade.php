@extends('layouts.administration.app')

@section('page_title', __('Monthly Team Recognitions'))

@section('page_name')
<b class="text-uppercase">{{ __('Monthly Team Recognitions') }}</b>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Recognition Panel') }}</h5>
        <form method="GET" action="{{ route('administration.employee_recognition.index') }}" class="d-flex align-items-center gap-2">
          <input type="month" class="form-control" name="month" value="{{ $month->format('Y-m') }}">
          <button class="btn btn-secondary" type="submit">{{ __('Load') }}</button>
          <a href="{{ route('administration.employee_recognition.leaderboard', ['month' => $month->format('Y-m-d')]) }}" class="btn btn-info">{{ __('Leaderboard') }}</a>
        </form>
      </div>
      <div class="card-body">
        @if(!$isWindowOpen)
          <div class="alert alert-warning">{{ __('Recognition window is from 1st to 5th of each month.') }}</div>
        @endif

        <form method="POST" action="{{ route('administration.employee_recognition.store') }}">
          @csrf
          <input type="hidden" name="month" value="{{ $month->format('Y-m-d') }}">
          <div class="table-responsive">
            <table class="table table-striped align-middle">
              <thead>
              <tr>
                <th>{{ __('Employee') }}</th>
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
              @foreach($teamMembers as $member)
                <tr>
                  <td class="d-flex align-items-center gap-2">
                    {!! show_user_name_and_avatar($member, role: null) !!}
                  </td>
                  @foreach(['behavior','appreciation','leadership','loyalty','dedication'] as $crit)
                    <td style="width:120px">
                      <input type="number" class="form-control" name="scores[{{ $member->id }}][{{ $crit }}]" min="0" max="20"
                             value="{{ old("scores.$member->id.$crit", ($recognitions[$member->id]->$crit ?? null)) }}" {{ (($recognitions[$member->id]->locked_at ?? null)) ? 'readonly' : '' }} />
                    </td>
                  @endforeach
                  <td>
                    <span class="badge bg-primary">{{ isset($recognitions[$member->id]) ? $recognitions[$member->id]->total_score : '-' }}</span>
                  </td>
                  <td>
                    <span class="badge {{ isset($badgeMap[$member->id]) ? $badgeMap[$member->id]['class'] : 'bg-secondary' }}">{{ isset($badgeMap[$member->id]) ? ($badgeMap[$member->id]['emoji'].' '.$badgeMap[$member->id]['label']) : '-' }}</span>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <div class="mt-3 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary" {{ $teamMembers->isEmpty() ? 'disabled' : '' }}>{{ __('Save & Lock Month') }}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
