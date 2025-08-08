@extends('layouts.administration.app')

@section('page_title', __('My Recognitions & Scores'))

@section('page_name')
<b class="text-uppercase">{{ __('My Recognitions & Scores') }}</b>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('My Monthly Scores') }}</h5>
        <form method="GET" class="d-flex align-items-center gap-2">
          <select name="year" class="form-select">
            @for($y = now()->year; $y >= now()->year - 3; $y--)
              <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
          </select>
          <button class="btn btn-secondary" type="submit">{{ __('Load') }}</button>
        </form>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>{{ __('Month') }}</th>
                <th>{{ __('Behavior') }}</th>
                <th>{{ __('Appreciation') }}</th>
                <th>{{ __('Leadership') }}</th>
                <th>{{ __('Loyalty') }}</th>
                <th>{{ __('Dedication') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Rank') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recognitions as $eval)
                <tr>
                  <td>{{ \Illuminate\Support\Carbon::parse($eval->month)->format('M Y') }}</td>
                  <td>{{ $eval->behavior }}</td>
                  <td>{{ $eval->appreciation }}</td>
                  <td>{{ $eval->leadership }}</td>
                  <td>{{ $eval->loyalty }}</td>
                  <td>{{ $eval->dedication }}</td>
                  <td><span class="badge bg-primary">{{ $eval->total_score }}</span></td>
                  <td>
                    @php
                      $rank = $eval->rank; $class = $rank==='first'?'bg-success':($rank==='second'?'bg-info':($rank==='third'?'bg-warning':'bg-secondary'));
                    @endphp
                    <span class="badge {{ $class }}">{{ $rank ? ucfirst($rank) : '-' }}</span>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center text-muted">{{ __('No recognitions found.') }}</td>
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
