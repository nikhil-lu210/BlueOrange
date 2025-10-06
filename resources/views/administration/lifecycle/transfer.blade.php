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

        .btn-process-transfer {
            background-color: #9c27b0;
            border-color: #9c27b0;
            color: white;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 6px;
        }

        .btn-process-transfer:hover {
            background-color: #7b1fa2;
            border-color: #7b1fa2;
            color: white;
        }

        .transfer-card {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            background: white;
            transition: all 0.3s ease;
        }

        .transfer-card:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .employee-name {
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
            margin-bottom: 0.25rem;
        }

        .transfer-details {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .transfer-date {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-approved {
            background-color: #e8f5e8;
            color: #155724;
        }

        .status-in-progress {
            background-color: #f3e5f5;
            color: #6a1b9a;
        }

        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        .empty-state h4 {
            margin-bottom: 1rem;
            color: #333;
        }

        .transfer-process-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 2rem;
            margin-top: 2rem;
        }

        .process-title {
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
            text-align: center;
        }

        .process-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .process-list li {
            padding: 0.5rem 0;
            color: #666;
            position: relative;
            padding-left: 1.5rem;
        }

        .process-list li::before {
            content: '•';
            color: #9c27b0;
            font-weight: bold;
            position: absolute;
            left: 0;
        }

        .transfer-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #9c27b0;
            display: block;
        }

        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .transfers-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 1rem;
        }

        .transfers-table thead th {
            background-color: #fafbfc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
        }

        .transfers-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .transfers-table tbody tr:hover {
            background-color: #fafbfc;
        }

        .employee-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #9c27b0 0%, #e1bee7 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 4px;
            background: #f8f9fa;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            background-color: #e9ecef;
            color: #495057;
        }

        @media (max-width: 768px) {
            .section-header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }

            .transfer-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Active') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Employee Lifecycle Management') }}</li>
    <li class="breadcrumb-item active">{{ __('Active') }}</li>
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
                <!-- Transfers Tab -->
                <div id="transfers">
                    <!-- Section Header -->
                    <div class="section-header">
                        <h2 class="section-title">Department Transfers & Promotions</h2>
                        <button class="btn btn-process-transfer">
                            <i class="fas fa-plus me-2"></i>Process Transfer
                        </button>
                    </div>

                    <!-- Transfer Statistics -->
                    <div class="transfer-stats">
                        <div class="stat-card">
                            <span class="stat-number">12</span>
                            <div class="stat-label">Total Transfers</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">3</span>
                            <div class="stat-label">Pending Approvals</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">5</span>
                            <div class="stat-label">In Progress</div>
                        </div>
                        <div class="stat-card">
                            <span class="stat-number">4</span>
                            <div class="stat-label">Completed This Month</div>
                        </div>
                    </div>

                    <!-- Active Transfers Table -->
                    <h3 class="section-title mb-3">Active Transfers</h3>
                    <div class="table-responsive">
                        <table class="transfers-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Current Department</th>
                                    <th>New Department</th>
                                    <th>Transfer Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">JD</div>
                                            <div>
                                                <div class="employee-name">John Davis</div>
                                                <div class="text-muted small">Software Engineer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Engineering</td>
                                    <td>Product Management</td>
                                    <td>2024-09-15</td>
                                    <td>
                                        <span class="status-badge status-pending">Pending Approval</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Transfer">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">EM</div>
                                            <div>
                                                <div class="employee-name">Emily Martinez</div>
                                                <div class="text-muted small">Marketing Coordinator</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Marketing</td>
                                    <td>Sales</td>
                                    <td>2024-09-20</td>
                                    <td>
                                        <span class="status-badge status-in-progress">In Progress</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Transfer">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">RK</div>
                                            <div>
                                                <div class="employee-name">Robert Kim</div>
                                                <div class="text-muted small">Data Analyst</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Analytics</td>
                                    <td>Engineering</td>
                                    <td>2024-09-25</td>
                                    <td>
                                        <span class="status-badge status-approved">Approved</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Transfer">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">SC</div>
                                            <div>
                                                <div class="employee-name">Susan Chen</div>
                                                <div class="text-muted small">HR Specialist</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Human Resources</td>
                                    <td>Operations</td>
                                    <td>2024-10-01</td>
                                    <td>
                                        <span class="status-badge status-pending">Pending Approval</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Transfer">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">DL</div>
                                            <div>
                                                <div class="employee-name">David Lee</div>
                                                <div class="text-muted small">Senior Developer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Engineering</td>
                                    <td>Architecture</td>
                                    <td>2024-10-05</td>
                                    <td>
                                        <span class="status-badge status-in-progress">In Progress</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Transfer">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Transfer Process Information -->
                    <div class="transfer-process-info">
                        <div class="process-title">Transfer Process Includes:</div>
                        <ul class="process-list">
                            <li>Role-based task templates</li>
                            <li>Permission updates</li>
                            <li>Training assignments</li>
                            <li>Manager notifications</li>
                            <li>System access changes</li>
                            <li>Documentation updates</li>
                            <li>Salary adjustments (if applicable)</li>
                            <li>Benefits transition management</li>
                        </ul>
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
        document.addEventListener('DOMContentLoaded', function() {
            // Action button functionality
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.title;
                    const employeeName = this.closest('tr').querySelector('.employee-name').textContent;
                    
                    if (action.includes('View')) {
                        alert(`Viewing transfer details for ${employeeName}`);
                    } else if (action.includes('Edit')) {
                        alert(`Editing transfer for ${employeeName}`);
                    }
                });
            });

            // Process Transfer button functionality
            document.querySelector('.btn-process-transfer').addEventListener('click', function() {
                alert('Opening new transfer request form...');
            });

            // Simulate real-time updates
            setInterval(function() {
                const stats = document.querySelectorAll('.stat-number');
                stats.forEach(stat => {
                    const current = parseInt(stat.textContent);
                    if (Math.random() > 0.95) { // 5% chance to update
                        stat.textContent = current + (Math.random() > 0.5 ? 1 : -1);
                    }
                });
            }, 10000); // Update every 10 seconds
        });
    </script>
    
@endsection
