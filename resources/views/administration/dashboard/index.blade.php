@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Dashboard'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        @import url('https://fonts.googleapis.com/css2?family=Satisfy&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Indie+Flower&display=swap');
        .birthday-wish > * {
            font-family: "Satisfy", cursive;
        }
        .birthday-wish .birthday-message {
            font-family: "Indie Flower", cursive;
            font-size: 24px;
        }

        /* Table */
        .table-borderless th, .table-bordered th {
            font-weight: bold;
        }
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection



@section('content')
<!-- Start row -->
@if (is_today_birthday(optional(auth()->user()->employee)->birth_date)) 
    <div class="row mb-4 birthday-wish">
        <div class="col-md-12">
            <div class="card card-border-shadow-primary">
                <div class="card-body text-center">
                    <i class="ti ti-balloon text-warning" style="font-size: 8rem;"></i>
                    <h1 class="m-0 text-primary text-bold">Happy Birthday</h1>
                    <h3 class="m-0 text-primary text-bold">{{ get_employee_name(auth()->user()) }}</h3>

                    <p class="birthday-message mt-4 text-bold bg-label-success p-3">{{ $wish }}</p>
                    <i class="fs-3">{{ __('Team Staff-India') }}</i>
                </div>
            </div>
        </div>
    </div>
@endif


<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 border-0">
            <div class="card-body row">
                <div class="col-md-8 card-separator">
                    <div class="d-flex justify-content-between flex-wrap gap-3 me-3">
                        <div class="d-flex align-items-center gap-3 me-4 me-sm-0">
                            <span class="bg-label-success p-2 rounded">
                                <i class="ti ti-briefcase ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-success mb-0" title="{{ __('Total Worked') }}" data-bs-placement="left">{{ format_number($totalWorkedDays) }} <small>Days</small></h5>
                                <small class="mb-0" title="{{ __('Total Days in '). config('app.name') }}" data-bs-placement="left">{{ total_day($user->employee->joining_date) }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-primary p-2 rounded">
                                <i class="ti ti-hourglass-high ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-primary mb-0">{{ total_time($totalRegularWork) }}</h5>
                                <small class="mb-0 text-muted">Total Worked (Regular)</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-warning p-2 rounded">
                                <i class="ti ti-hourglass-low ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-warning mb-0">{{ total_time($totalOvertimeWork) }}</h5>
                                <small class="mb-0 text-muted">Total Worked (Overtime)</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="d-flex justify-content-between flex-wrap gap-3 px-2">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-2">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-calendar-time ti-md"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="mb-0 text-nowrap live-time text-bold" id="liveTime"></h5>
                                <small>{{ date('jS M, Y (l)') }}</small>
                            </div>
                        </div>
                        @if (auth()->user()->hasAllPermissions(['Attendance Everything', 'Attendance Update'])) 
                            @isset($activeAttendance->clock_in)
                                <div class="d-flex align-items-center">
                                    <form action="{{ route('administration.attendance.clockout') }}" method="post" class="confirm-form-danger">
                                        <div class="avatar flex-shrink-0 me-2">
                                            @csrf
                                            <button type="submit" name="attendance" value="clock_out" class="avatar-initial rounded bg-danger border-0" data-bs-placement="top" title="Clock Out?">
                                                <i class="ti ti-clock-off ti-md"></i>
                                            </button>
                                        </div>
                                    </form>
                                    <div>
                                        <h5 class="mb-0 text-nowrap live-working-time text-{{ $activeAttendance->type == 'Regular' ? 'primary' : 'warning' }}" 
                                            id="liveWorkingTime" 
                                            data-clock-in-at="{{ $activeAttendance->clock_in->timestamp }}">
                                        </h5>
                                        <small class="bg-label-{{ $activeAttendance->type == 'Regular' ? 'primary' : 'warning' }} p-1 px-2 rounded-2 text-bold">{{ $activeAttendance->type }}</small>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('administration.attendance.clockin') }}" method="post">
                                    @csrf
                                    <div class="d-flex align-items-center mt-1">
                                        <div class="avatar flex-shrink-0 me-2">
                                            <button type="button" class="avatar-initial rounded bg-primary border-0 submit-regular" data-bs-placement="top" title="Regular Clockin?">
                                                <i class="ti ti-clock-check ti-md"></i>
                                            </button>
                                        </div>
                                        <div class="avatar flex-shrink-0 me-2">
                                            <button type="button" class="avatar-initial rounded bg-warning border-0 submit-overtime" data-bs-placement="top" title="Overtime Clockin?">
                                                <i class="ti ti-clock-check ti-md"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Hidden input to store attendance type -->
                                    <input type="hidden" name="attendance" id="attendanceType">
                                </form>                        
                            @endisset
                        @endif
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>


{{-- Attendances for running month --}}
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Attendances Of <b class="text-bold text-primary">{{ date('F Y') }}</b></h5>
        
                <div class="card-header-elements ms-auto" style="margin-top: -5px;">            
                    <small class="badge bg-primary cursor-pointer" title="Total Working Hour (Regular)" data-bs-placement="top" >
                        {{ total_time($totalRegularWorkingHour) }}
                    </small>
                                                         
                    @isset ($totalOvertimeWorkingHour) 
                        <small class="badge bg-warning cursor-pointer" title="Total Working Hour (Overtime)" data-bs-placement="bottom" >
                            {{ total_time($totalOvertimeWorkingHour) }}
                        </small>
                    @endisset
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-left">Date</th>
                                <th class="text-center">Clock In</th>
                                <th class="text-center">Clock Out</th>
                                <th class="text-center">Total Worked</th>
                                <th class="text-center">Total Break</th>
                                <th class="text-center">Over Break</th>
                                <th class="text-right">Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $key => $attendance) 
                                <tr>
                                    <td class="text-left">
                                        <span class="fw-medium">{{ show_date_month_day($attendance->clock_in_date) }}</span>
                                    </td>
                                    <td class="text-center">{{ show_time($attendance->clock_in) }}</td>
                                    <td class="text-center">
                                        @isset ($attendance->clock_out) 
                                            <span>{{ show_time($attendance->clock_out) }}</span>
                                        @else 
                                            <span class="text-bold text-success">Running</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_time) 
                                            <span>{{ total_time($attendance->total_time) }}</span>
                                        @else 
                                            <span class="text-bold text-success">Running</span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_break_time) 
                                            <span>{{ total_time($attendance->total_break_time) }}</span>
                                        @else 
                                            <span class="text-bold text-success" title="No Break Taken" data-bs-placement="right">
                                                <i class="ti ti-clock-play"></i>
                                            </span>
                                        @endisset
                                    </td>
                                    <td class="text-center">
                                        @isset ($attendance->total_over_break) 
                                            <span class="text-danger">{{ total_time($attendance->total_over_break) }}</span>
                                        @else 
                                            <span class="text-bold text-success" title="No Over Break" data-bs-placement="right">
                                                <i class="ti ti-mood-check"></i>
                                            </span>
                                        @endisset
                                    </td>
                                    <td class="text-right">
                                        @if ($attendance->type == 'Regular') 
                                            <span class="badge bg-label-primary me-1">Regular</span>
                                        @else 
                                            <span class="badge bg-label-warning me-1">Overtime</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>        
    </div>
</div>

<!-- End row -->
@endsection



@section('script_links')
    {{--  External Javascript Links --}}
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // ShowLiveTime
        $(document).ready(function() {
            // Function to update the clock
            function updateTime() {
                var currentTime = new Date();
                
                // Format hours, minutes, and seconds with leading zeros
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();
                
                // Convert to 12-hour format and determine AM/PM
                var ampm = hours >= 12 ? 'PM' : 'AM';
                hours = hours % 12;
                hours = hours ? hours : 12; // the hour '0' should be '12'
                
                // Add leading zeros to minutes and seconds if needed
                minutes = minutes < 10 ? '0'+minutes : minutes;
                seconds = seconds < 10 ? '0'+seconds : seconds;
                
                // Create the time string in the format HH:MM:SS AM/PM
                var timeString = hours + ':' + minutes + ':' + seconds + ' ' + ampm;
                
                // Update the content of the #liveTime element
                $('#liveTime').text(timeString);
            }
            
            // Call the updateTime function every second (1000 milliseconds)
            setInterval(updateTime, 1000);
            
            // Call the function initially to show time immediately when the page loads
            updateTime();
        });
    </script>
    
    <script>
        // LiveClockInTimeCount
        $(document).ready(function() {
            const liveWorkingTimeElement = $('#liveWorkingTime');
            
            if (liveWorkingTimeElement.length) {
                const clockInAt = parseInt(liveWorkingTimeElement.data('clock-in-at')) * 1000; // Convert to milliseconds
                
                // Function to calculate and display the elapsed time
                function updateliveWorkingTime() {
                    const now = new Date().getTime();
                    const elapsed = now - clockInAt;
    
                    // Calculate hours, minutes, and seconds
                    const hours = Math.floor(elapsed / (1000 * 60 * 60));
                    const minutes = Math.floor((elapsed % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((elapsed % (1000 * 60)) / 1000);
    
                    // Format the time as hh:mm:ss
                    const formattedTime = 
                        String(hours).padStart(2, '0') + ':' +
                        String(minutes).padStart(2, '0') + ':' +
                        String(seconds).padStart(2, '0');
                    
                    liveWorkingTimeElement.text(formattedTime);
                }
    
                // Update the time every second
                updateliveWorkingTime(); // Initial call
                setInterval(updateliveWorkingTime, 1000); // Update every second
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // $('form').on('submit', function(e) {
            //     e.preventDefault(); // Prevent default submission
            // });

            $('.submit-regular').click(function() {
                $('#attendanceType').val('Regular'); 
                $(this).closest('form').submit(); 
            });

            $('.submit-overtime').click(function() {
                $('#attendanceType').val('Overtime'); 
                $(this).closest('form').submit(); 
            });
        });

    </script>
@endsection
