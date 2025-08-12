@extends('layouts.administration.app')

@section('page_title', __('Recognitions Browse'))

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ __('All Recognitions') }}</h5>
        <form class="d-flex gap-2" method="get">
            <input type="number" name="year" class="form-control" style="max-width: 110px" placeholder="{{ __('Year') }}" value="{{ $year }}">
            <input type="number" name="month" class="form-control" style="max-width: 90px" placeholder="{{ __('Month') }}" value="{{ $month }}">
            <input type="number" name="team_leader_id" class="form-control" style="max-width: 140px" placeholder="{{ __('TL ID') }}" value="{{ $teamLeaderId }}">
            <input type="number" name="employee_id" class="form-control" style="max-width: 140px" placeholder="{{ __('Emp ID') }}" value="{{ $employeeId }}">
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
                        <th>{{ __('Team Leader') }}</th>
                        <th>{{ __('Total Score') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rows as $row)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($row->month)->format('F Y') }}</td>
                            <td>{!! show_user_name_and_avatar($row->employee, role: null) !!}</td>
                            <td>{!! show_user_name_and_avatar($row->teamLeader, role: null) !!}</td>
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
