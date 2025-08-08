@extends('layouts.administration.app')

@section('page_title', __('Employee Performance Trend'))

@section('page_name')
<b class="text-uppercase">{{ __('Employee Performance Trend') }}</b>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Trend for') }} {{ $user->alias_name }}</h5>
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
          <table class="table table-striped">
            <thead>
              <tr>
                <th>{{ __('Month') }}</th>
                <th>{{ __('Total') }}</th>
                <th>{{ __('Rank') }}</th>
              </tr>
            </thead>
            <tbody>
              @forelse($trend as $row)
                @php $rank = $row->rank; $class = $rank==='first'?'bg-success':($rank==='second'?'bg-info':($rank==='third'?'bg-warning':'bg-secondary')); @endphp
                <tr>
                  <td>{{ \Illuminate\Support\Carbon::parse($row->month)->format('M Y') }}</td>
                  <td><span class="badge bg-primary">{{ $row->total_score }}</span></td>
                  <td><span class="badge {{ $class }}">{{ $rank ? ucfirst($rank) : '-' }}</span></td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center text-muted">{{ __('No data') }}</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
