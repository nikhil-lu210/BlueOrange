@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Start or Stop Break'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    * {
      user-select: none; /* Standard syntax */
      -webkit-user-select: none; /* Chrome, Safari */
      -moz-user-select: none; /* Firefox */
      -ms-user-select: none; /* IE10+ */
    }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Start or Stop Break') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Break') }}</li>
    <li class="breadcrumb-item active">{{ __('Start or Stop Break') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    @isset ($activeBreak)
        <div class="col-md-7">
            <div class="card mb-4">
                <div class="card-header header-elements">
                    <h5 class="mb-0 text-bold">Running Break</h5>
                    @isset($activeBreak->break_in_at)
                        <small class="badge bg-dark fs-6 ms-auto" 
                            id="runningBreakTime" 
                            data-break-in-at="{{ $activeBreak->break_in_at->timestamp }}" 
                            title="Running Break" 
                            style="margin-top: -5px;">
                        </small>
                    @endisset
                </div>
                <div class="card-body">
                    <dl class="row mb-1">
                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                            <i class="ti ti-access-point text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Break Type:</span>
                        </dt>
                        <dd class="col-sm-8">
                            @if ($activeBreak->type == 'Short')
                                <span class="badge bg-primary text-bold">{{ __('Short Break') }}</span>
                            @else
                                <span class="badge bg-warning text-bold">{{ __('Long Break') }}</span>
                            @endif
                        </dd>
                    </dl>
                    <dl class="row mb-1">
                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                            <i class="ti ti-clock-play text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Started At:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <span class="text-bold text-primary">{{ show_time($activeBreak->break_in_at) }}</span>
                        </dd>
                    </dl>
                    <dl class="row mb-1">
                        <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                            <i class="ti ti-access-point text-heading"></i>
                            <span class="fw-medium mx-2 text-heading">Break Start IP:</span>
                        </dt>
                        <dd class="col-sm-8">
                            <span>{{ $activeBreak->break_in_ip }}</span>
                        </dd>
                    </dl>
                </div>
    
                <div class="card-footer">
                    <div class="text-end">
                        <form action="{{ route('administration.daily_break.stop') }}" method="post" class="confirm-form-danger">
                            @csrf
                            <button type="submit" name="stop_break" class="btn btn-danger">
                                <span class="tf-icon ti ti-clock-stop me-1"></span>
                                {{ __('Stop Break') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-7">
            <form action="{{ route('administration.daily_break.start') }}" method="POST" autocomplete="off">
                @csrf
                <div class="card mb-4">
                    <div class="card-header header-elements">
                        <h5 class="mb-0">Start Daily Break</h5>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="userid" value="{{ auth()->user()->userid }}" required>
                        <div class="row">
                            <div class="col-md mb-md-0 mb-2">
                                <div class="form-check form-check-primary bg-label-primary custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content" for="shortBreak">
                                        <input name="break_type" value="Short" class="form-check-input" type="radio" id="shortBreak" required/>
                                        <span class="custom-option-header">
                                            <span class="h6 mb-0 text-uppercase text-bold">Short Break</span>
                                            <span class="text-bold">15-20 Min</span>
                                        </span>
                                        <span class="custom-option-body">
                                            <small class="text-muted">You Can Take Maximum 2 Short Break.</small>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-check form-check-warning bg-label-warning custom-option custom-option-basic">
                                    <label class="form-check-label custom-option-content" for="longBreak">
                                        <input name="break_type" value="Long" class="form-check-input" type="radio" id="longBreak" required/>
                                        <span class="custom-option-header">
                                            <span class="h6 mb-0 text-uppercase text-bold">Long Break</span>
                                            <span class="text-bold">30-45 Min</span>
                                        </span>
                                        <span class="custom-option-body">
                                            <small class="text-muted">You Can Take Maximum 1 Long Break.</small>
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="card-footer">
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-check ti-xs me-1"></span>
                                Start Break
                            </button>
                        </div>
                    </div>
                </div>
            </form>        
        </div>
    @endisset

    @if ($breaks)
        <div class="col-md-5">
            <div class="card">
                <div class="card-header header-elements">
                    <h5 class="mb-0">Start Daily Break</h5>
                    @isset ($attendance) 
                        <div class="ms-auto" style="margin-top: -5px;">
                            @isset ($attendance->total_break_time) 
                                <small class="badge bg-dark" title="Total Break Taken">
                                    {{ total_time($attendance->total_break_time) }}
                                </small>
                            @endisset
                            @isset ($attendance->total_over_break) 
                                <small class="badge bg-danger" title="Total Over Break">
                                    {{ total_time($attendance->total_over_break) }}
                                </small>
                            @endisset
                        </div>
                    @endisset
                </div>
                <div class="card-body">
                    <ul class="timeline mb-0 pb-1">
                        @forelse ($breaks as $key => $break) 
                            <li class="timeline-item ps-4 {{ $loop->last ? 'border-transparent' : 'border-left-dashed pb-1' }}">
                                <span class="timeline-indicator-advanced timeline-indicator-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">
                                    <i class="ti ti-{{ $break->break_out_at ? 'clock-stop' : 'clock-play' }}"></i>
                                </span>
                                <div class="timeline-event px-0 pb-0">
                                    <div class="timeline-header">
                                        <small class="text-uppercase fw-medium" title="Click To See Details">
                                            <a href="{{ route('administration.daily_break.show', ['break' => $break]) }}" class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ $break->type }} Break</a>
                                        </small>
                                    </div>
                                    <small class="text-muted mb-0">
                                        {{ show_time($break->break_in_at) }}
                                        @if (!is_null($break->break_out_at)) 
                                            <span>to</span>
                                            <span>{{ show_time($break->break_out_at) }}</span>
                                        @else
                                            -
                                            <span class="text-danger">Break Running</span>
                                        @endif
                                    </small>
                                    <h6 class="mb-1">
                                        @if (is_null($break->total_time))
                                            <span class="text-danger">Break Running</span>
                                        @else
                                            <span class="text-{{ $break->type == 'Short' ? 'primary' : 'warning' }}">{{ total_time($break->total_time) }}</span>
                                            @isset($break->over_break)
                                                <small class="text-danger text-bold mt-1" title="Over Break">({{ total_time($break->over_break) }})</small>
                                            @endisset
                                        @endif
                                    </h6>
                                </div>
                            </li>
                        @empty 
                            <div class="text-center text-bold text-muted fs-2">No Breaks</div>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
<!-- End row -->

@endsection


@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{asset('assets/js/form-layouts.js')}}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            const runningBreakTimeElement = $('#runningBreakTime');
            
            if (runningBreakTimeElement.length) {
                const breakInAt = parseInt(runningBreakTimeElement.data('break-in-at')) * 1000; // Convert to milliseconds
                
                // Function to calculate and display the elapsed time
                function updateRunningBreakTime() {
                    const now = new Date().getTime();
                    const elapsed = now - breakInAt;
    
                    // Calculate hours, minutes, and seconds
                    const hours = Math.floor(elapsed / (1000 * 60 * 60));
                    const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
    
                    // Format the time as hh:mm:ss
                    const formattedTime = 
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                    
                    runningBreakTimeElement.text(formattedTime);
                }
    
                // Update the time every second
                updateRunningBreakTime(); // Initial call
                setInterval(updateRunningBreakTime, 1000); // Update every second
            }
        });
    </script>    
@endsection