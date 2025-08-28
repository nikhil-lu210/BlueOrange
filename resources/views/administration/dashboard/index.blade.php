@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('page_title', __('Dashboard'))

@section('css_links')
    {{--  External CSS  --}}
    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    {{-- FullCalendar --}}
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <link rel="stylesheet" href="{{ asset('assets/css/custom_css/dashboard/index.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Dashboard') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
@endsection

{{-- Recognition Notification --}}
{{-- @include('administration.dashboard.partials._recognition_notification') --}}

{{-- @section('breadcrumb_action')
    <div class="d-flex gap-2">
        @if ($canRecognize)
            <button class="btn btn-primary btn-md" data-bs-toggle="modal" data-bs-target="#recognitionModal">
                <i class="ti ti-badge me-1"></i>
                {{ __('Submit Recognition') }}
            </button>
        @endif
        
        <button class="btn btn-outline-info btn-md" onclick="testRecognitionNotification()">
            <i class="ti ti-bell me-1"></i>
            Test Notification
        </button>
    </div>
@endsection --}}



@section('content')
    {{-- <!-- Start row --> --}}

    {{-- Birdthday Wish --}}
    @include('administration.dashboard.partials._birthday_wish')

    {{-- @include('administration.dashboard.partials._recognition_congratulation') --}}

    {{-- @include('administration.dashboard.partials._recognition_form') --}}

    @if ($autoShowRecognitionModal) 
        @include('administration.dashboard.partials._recognition_urgent_prompt')
    @endif

    @if (!$autoShowRecognitionModal) 
    @include('administration.dashboard.partials._recognition_default_prompt')
    @endif

    {{-- Upcoming Birthdays --}}
    @include('administration.dashboard.partials._upcoming_birthdays')

    {{-- Attendance Summary and Clockin-Clockout --}}
    @include('administration.dashboard.partials._attendance_summary')

    {{-- Currently Working || On Leave Today || Absent Today --}}
    <div class="row mb-4">
        @include('administration.dashboard.partials._currently_working')

        @include('administration.dashboard.partials._on_leave_today')

        @include('administration.dashboard.partials._absent_today')
    </div>

    {{-- Calendar --}}
    @include('administration.dashboard.partials._calendar')

    {{-- Attendances for running month --}}
    @include('administration.dashboard.partials._running_month_attendance')


    {{-- Employee Info Update Modal --}}
    @if ($showEmployeeInfoUpdateModal)
        @include('administration.dashboard.modals.employee_info_update_modal')
    @endif


    @if ($canRecognize)
        {{-- @include('administration.dashboard.modals.employee_recognition_modal') --}}
        @include('administration.dashboard.modals.employee_recognition_modal_form')
    @endif

    @include('administration.dashboard.modals.recognize_congrats_modal')
    {{-- <!-- End row --> --}}
@endsection



@section('script_links')
    {{--  External Javascript Links --}}

    <!-- Page JS -->
    <script src="{{ asset('assets/js/cards-actions.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>

    <!-- Calendar Dependencies -->
    <script src='https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>

    <!-- Dashboard Calendar JS -->
    <script src="{{ asset('assets/js/custom_js/calendar/dashboard_calendar.js') }}"></script>

    <!-- Lucide Icons for Recognition Notification -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <script src="{{ asset('assets/js/custom_js/dashboard/index.js') }}"></script>

@endsection

@section('custom_script')
    {{--  External js  --}}

    <script>
        /* Dashboard Calendar Configuration
        Configuration object for the dashboard calendar */

        const dashboardCalendarConfig = {
            eventsUrl: '{{ route("administration.dashboard.calendar.events") }}',
            weekendsUrl: '{{ route("administration.dashboard.calendar.weekends") }}',
            taskUrl: '{{ route("administration.task.index") }}',
            currentUserId: {{ Auth::id() }}
        };
  
        document.addEventListener("DOMContentLoaded", function () {
            const modalEl = document.getElementById('recognizeCongratsModal');
            if (!modalEl) return;

            const recognitionQueue = [];
            let isShowingRecognition = false;

            const sessionSeenRecognition = new Set();

            function populateModalFromNotification(notification) {
                try {
                    const data = notification.data || {};

                    // Prefer structured fields when available
                    const recognizedName = data?.recognized?.name || null;
                    const recognizerName = data?.recognizer?.name || null;
                    const avatarUrl = data?.recognized?.avatar || null;
                    const category = data?.category || null;
                    const points = typeof data?.points !== 'undefined' ? data.points : null;
                    const comment = data?.comment || null;

                    // Fallback to parsing legacy message if structured fields are absent
                    let fallbackRecognized = null, fallbackRecognizer = null;
                    if (!recognizedName || !recognizerName) {
                        const msg = data.message || '';
                        const token = ' got recognition from ';
                        const idx = msg.indexOf(token);
                        if (idx !== -1) {
                            fallbackRecognized = msg.substring(0, idx).trim();
                            fallbackRecognizer = msg.substring(idx + token.length).trim();
                        }
                    }

                    // Title: "Congratulations To <Name>"
                    const titleEl = modalEl.querySelector('.role-title');
                    const nameToUse = recognizedName || fallbackRecognized;
                    if (titleEl && nameToUse) {
                        titleEl.textContent = 'Congratulations To ' + nameToUse;
                    }

                    // Recognized by: <Recognizer>
                    const byEl = modalEl.querySelector('.recognized-by .fw-semibold');
                    const recognizerToUse = recognizerName || fallbackRecognizer;
                    if (byEl && recognizerToUse) {
                        byEl.textContent = recognizerToUse;
                    }

                    // Avatar
                    const avatarEl = modalEl.querySelector('.profile-circle img');
                    if (avatarEl && avatarUrl) {
                        avatarEl.src = avatarUrl;
                    }

                    // Category + Icon
                    const catSpan = modalEl.querySelector('.award-details .award-badge span');
                    const catIcon = modalEl.querySelector('.award-details .award-badge i');
                    if (catSpan && category) {
                        catSpan.textContent = category;
                    }
                    if (catIcon && category) {
                        catIcon.className = categoryIconClass(category);
                    }

                    // Points
                    const pointsSpan = modalEl.querySelector('.award-details .points-badge span');
                    if (pointsSpan && points !== null) {
                        pointsSpan.textContent = `${points} Points`;
                    }

                    // Comment/Quote
                    const quoteEl = modalEl.querySelector('.quote-section blockquote');
                    if (quoteEl && comment) {
                        quoteEl.textContent = comment;
                    }
                } catch (e) {
                    // Fail silently; modal has sensible defaults
                }
            }

            function categoryIconClass(category) {
                const c = (category || '').toLowerCase();
                // Map common categories to icons (Font Awesome). Adjust as needed.
                if (c.includes('leader')) return 'fas fa-user-tie';
                if (c.includes('team')) return 'fas fa-people-group';
                if (c.includes('innov')) return 'fas fa-lightbulb';
                if (c.includes('excel')) return 'fas fa-star';
                if (c.includes('help') || c.includes('support')) return 'fas fa-hand-holding-heart';
                if (c.includes('quality')) return 'fas fa-check-circle';
                if (c.includes('impact')) return 'fas fa-bullseye';
                return 'fas fa-heart';
            }

            function showNextRecognition() {
                if (isShowingRecognition) return;
                const next = recognitionQueue.shift();
                if (!next) return;

                isShowingRecognition = true;
                populateModalFromNotification(next);

                const bsModal = new bootstrap.Modal(modalEl);
                $(modalEl).off('hidden.bs.modal').on('hidden.bs.modal', function () {
                    if (typeof markNotificationReadUrl !== 'undefined') {
                        $.get(markNotificationReadUrl + next.id)
                        .always(function () {
                            isShowingRecognition = false;
                            showNextRecognition();
                        });
                    } else {
                        isShowingRecognition = false;
                        showNextRecognition();
                    }
                });

                bsModal.show();
            }

            function fetchRecognitionNotifications() {
                if (typeof unreadNotificationsUrl === 'undefined') return;
                $.get(unreadNotificationsUrl, function (list) {
                    if (!Array.isArray(list)) return;
                    list.forEach(function (n) {
                        const isRecognition = n && n.data && (n.data.type === 'recognition' || (n.data.icon === 'badge' && n.data.title === 'User Recognition'));
                        if (isRecognition && !sessionSeenRecognition.has(n.id)) {
                            sessionSeenRecognition.add(n.id);
                            recognitionQueue.push(n);
                        }
                    });
                    if (recognitionQueue.length > 0) {
                        showNextRecognition();
                    }
                });
            }

            // Initial fetch and then periodically/when visible
            fetchRecognitionNotifications();
            document.addEventListener('visibilitychange', function () {
                if (document.visibilityState === 'visible') {
                    fetchRecognitionNotifications();
                }
            });
            setInterval(fetchRecognitionNotifications, 60000);
        });

        @if ($showEmployeeInfoUpdateModal)
            
            $(document).ready(function () {
                // Show the modal
                $('#employeeInfoUpdateModal').modal('show');

                // Wait for the modal to be shown, then initialize Select2
                $('#employeeInfoUpdateModal').on('shown.bs.modal', function () {
                    // Initialize blood group Select2
                    $('#blood_group').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        width: '100%'
                    });

                    // Initialize institute Select2 with tagging
                    $('#institute_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [],
                        createTag: function (params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: 'new:' + term,
                                text: term + ' (New Institute)',
                                newTag: true
                            };
                        },
                        templateResult: function (data) {
                            var $result = $('<span></span>');
                            $result.text(data.text);
                            if (data.newTag) {
                                $result.append(' <em>(will be created)</em>');
                            }
                            return $result;
                        },
                        insertTag: function (data, tag) {
                            data.push(tag);
                        },
                        width: '100%'
                    });

                    // Initialize education level Select2 with tagging
                    $('#education_level_id').select2({
                        dropdownParent: $('#employeeInfoUpdateModal'),
                        tags: true,
                        tokenSeparators: [],
                        createTag: function (params) {
                            var term = $.trim(params.term);
                            if (term === '') {
                                return null;
                            }
                            return {
                                id: 'new:' + term,
                                text: term + ' (New Education Level)',
                                newTag: true
                            };
                        },
                        templateResult: function (data) {
                            var $result = $('<span></span>');
                            $result.text(data.text);
                            if (data.newTag) {
                                $result.append(' <em>(will be created)</em>');
                            }
                            return $result;
                        },
                        insertTag: function (data, tag) {
                            data.push(tag);
                        },
                        width: '100%'
                    });
                });
            });
            
        @endif
    </script>

@endsection
