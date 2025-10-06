@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Employee Lifecycle Management'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />

@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
		.nav-pills .nav-link {
			transition: all 0.25s ease;
			border-radius: 0.5rem;
			background-color: rgba(0, 0, 0, 0.03);
		}

		.nav-pills .nav-link:hover {
			background-color: rgba(13, 110, 253, 0.08);
			color: #0d6efd !important;
		}

		.nav-pills .nav-link.active {
			background: linear-gradient(90deg, #0d6efd 0%, #3d8bfd 100%);
			color: #ffffff !important;
			box-shadow: 0 0.25rem 0.75rem rgba(13, 110, 253, 0.25);
		}
		/* Navigation icons */
		.nav-pills .nav-link i { font-size: 1rem; opacity: 0.9; }

		.content-section {
            background: white;
            padding: 2rem;
            margin-top: 0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .btn-start-offboarding {
            background-color: #ff9800;
            border-color: #ff9800;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .btn-start-offboarding:hover {
            background-color: #f57c00;
            border-color: #f57c00;
            color: white;
        }

        .employee-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            background: white;
            transition: all 0.3s ease;
        }

        .employee-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .employee-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .employee-role {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .employee-date {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .progress-bar-custom {
            height: 6px;
            background-color: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #ff9800, #ffb74d);
            border-radius: 3px;
            transition: width 0.3s ease;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background-color: #fff3e0;
            color: #e65100;
        }

        .checklist-section {
            margin-top: 1rem;
        }

        .checklist-item {
            display: flex;
            align-items: center;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            background: white;
            transition: all 0.3s ease;
        }

        .checklist-item.completed {
            background-color: #e8f5e8;
            border-color: #34a853;
        }

        .checklist-item.pending {
            background-color: #f8f9fa;
            border-color: #dee2e6;
        }

        .checklist-checkbox {
            margin-right: 1rem;
            transform: scale(1.2);
        }

        .checklist-checkbox:checked {
            accent-color: #34a853;
        }

        .checklist-text {
            flex: 1;
            font-weight: 500;
            color: #333;
        }

        .checklist-item.completed .checklist-text {
            text-decoration: line-through;
            color: #666;
        }

        .status-icon {
            font-size: 1.2rem;
            margin-left: 0.5rem;
        }

        .status-completed {
            color: #34a853;
        }

        .status-pending {
            color: #6c757d;
        }

        .employee-actions {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .btn-sm-custom {
            padding: 0.25rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 4px;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Offboarding') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Employee Lifecycle Management') }}</li>
    <li class="breadcrumb-item active">{{ __('Offboarding') }}</li>
@endsection

@section('content')

<!-- Events Table -->
<div class="row">
    <div class="container-fluid">
        <!-- Header Section -->
        @include('administration.lifecycle.partials.header')

        @include('administration.lifecycle.partials.nav')
        <div class="content-section">
            <div class="container">
                <!-- Offboarding Tab -->
                <div id="offboarding">
                    <!-- Departing Employees Section -->
                    <div class="section-header">
                        <h2 class="section-title">Departing Employees</h2>
                        <button class="btn btn-start-offboarding">
                            <i class="fas fa-arrow-right me-2"></i>Start Offboarding
                        </button>
                    </div>

                    <!-- Employee Card -->
                    <div class="employee-card">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <div class="employee-name">Lisa Rodriguez</div>
                                <div class="employee-role">HR Specialist</div>
                                <div class="employee-date">End Date: 08-15</div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="status-badge">Offboarding</span>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-8">
                                <div class="progress-bar-custom">
                                    <div class="progress-fill" style="width: 33%"></div>
                                </div>
                                <div class="text-muted small">33% Complete</div>
                            </div>
                        </div>
                        <div class="employee-actions">
                            <button class="btn btn-outline-primary btn-sm-custom">
                                <i class="fas fa-eye me-1"></i>View Details
                            </button>
                            <button class="btn btn-outline-secondary btn-sm-custom">
                                <i class="fas fa-edit me-1"></i>Edit
                            </button>
                        </div>
                    </div>

                    <!-- Offboarding Checklist -->
                    <div class="checklist-section">
                        <h3 class="section-title mb-3">Offboarding Checklist Template</h3>
                        
                        <div class="checklist-item completed">
                            <input type="checkbox" class="form-check-input checklist-checkbox" checked disabled>
                            <span class="checklist-text">Exit Interview Scheduled</span>
                            <i class="fas fa-check-circle status-icon status-completed"></i>
                        </div>

                        <div class="checklist-item completed">
                            <input type="checkbox" class="form-check-input checklist-checkbox" checked disabled>
                            <span class="checklist-text">Knowledge Transfer Session</span>
                            <i class="fas fa-check-circle status-icon status-completed"></i>
                        </div>

                        <div class="checklist-item pending">
                            <input type="checkbox" class="form-check-input checklist-checkbox">
                            <span class="checklist-text">Project Handover</span>
                            <i class="fas fa-clock status-icon status-pending"></i>
                        </div>

                        <div class="checklist-item pending">
                            <input type="checkbox" class="form-check-input checklist-checkbox">
                            <span class="checklist-text">Return Laptop</span>
                            <i class="fas fa-clock status-icon status-pending"></i>
                        </div>

                        <div class="checklist-item pending">
                            <input type="checkbox" class="form-check-input checklist-checkbox">
                            <span class="checklist-text">Return Access Card</span>
                            <i class="fas fa-clock status-icon status-pending"></i>
                        </div>

                        <div class="checklist-item pending">
                            <input type="checkbox" class="form-check-input checklist-checkbox">
                            <span class="checklist-text">Final Payroll Processing</span>
                            <i class="fas fa-clock status-icon status-pending"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    {{--  External JS  --}}
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>
    
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>

@endsection

@section('custom_script')
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

        });
    </script>
    <script>
        // Interactive checkbox functionality
        document.addEventListener('DOMContentLoaded', function() {
            const checkboxes = document.querySelectorAll('.checklist-checkbox:not([disabled])');
            
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const item = this.closest('.checklist-item');
                    const statusIcon = item.querySelector('.status-icon');
                    
                    if (this.checked) {
                        item.classList.remove('pending');
                        item.classList.add('completed');
                        statusIcon.className = 'fas fa-check-circle status-icon status-completed';
                        this.disabled = true;
                    }
                    
                    updateProgress();
                });
            });
            
            function updateProgress() {
                const allCheckboxes = document.querySelectorAll('.checklist-checkbox');
                const checkedBoxes = document.querySelectorAll('.checklist-checkbox:checked');
                const progressFill = document.querySelector('.progress-fill');
                const progressText = document.querySelector('.text-muted.small');
                
                const percentage = Math.round((checkedBoxes.length / allCheckboxes.length) * 100);
                progressFill.style.width = percentage + '%';
                progressText.textContent = percentage + '% Complete';
            }
        });

        // Action button functionality
        document.querySelectorAll('.btn-sm-custom').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const action = this.textContent.trim();
                
                if (action.includes('View Details')) {
                    alert('Viewing offboarding details for Lisa Rodriguez');
                } else if (action.includes('Edit')) {
                    alert('Editing offboarding process for Lisa Rodriguez');
                }
            });
        });

        // Start Offboarding button functionality
        document.querySelector('.btn-start-offboarding').addEventListener('click', function() {
            alert('Starting new offboarding process...');
        });
    </script>
@endsection
