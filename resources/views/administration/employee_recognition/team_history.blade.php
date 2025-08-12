@extends('layouts.administration.app')

@section('page_title', __('Team Recognition History'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('Team History') }}</h5>
        <form class="d-flex gap-2" method="get">
            <input type="number" name="year" class="form-control" style="max-width: 110px" placeholder="{{ __('Year') }}" value="{{ $year }}">
            <button class="btn btn-primary">{{ __('Filter') }}</button>
        </form>
    </div>
    <div class="card-body">
        @if($rows->isEmpty())
            <div class="text-center text-muted my-4">
                <p class="mb-2">{{ __('No data available.') }}</p>
                <a href="{{ route('administration.employee_recognition.index', ['month' => now()->format('Y-m-d')]) }}" class="btn btn-sm btn-primary">{{ __('Recognize Now') }}</a>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>{{ __('Month') }}</th>
                        <th>{{ __('Employee') }}</th>
                        <th>{{ __('Total Score') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->month)->format('F Y') }}</td>
                            <td>{!! show_user_name_and_avatar($row->employee, role: null) !!}</td>
                            <td><span class="badge bg-primary">{{ $row->total_score }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
