@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Employee Lifecycle Management'))

@section('css_links')
    @include('administration.lifecycle.partials.css-includes')
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
            justify-content: between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 0;
        }

        .search-filter-section {
            display: flex;
            gap: 1rem;
            align-items: center;
            margin-bottom: 2rem;
        }

        .search-input {
            flex: 1;
            max-width: 300px;
            position: relative;
        }

        .search-input input {
            padding-left: 2.5rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .search-input .search-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .filter-btn {
            background: white;
            border: 1px solid #dee2e6;
            color: #666;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-btn:hover {
            background-color: #f8f9fa;
            border-color: #6c757d;
        }

        .employees-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .employees-table thead th {
            background-color: #fafbfc;
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #dee2e6;
            font-size: 0.9rem;
        }

        .employees-table tbody td {
            padding: 1rem;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: middle;
        }

        .employees-table tbody tr:hover {
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .employee-details {
            display: flex;
            flex-direction: column;
        }

        .employee-name {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.25rem;
        }

        .employee-title {
            color: #6c757d;
            font-size: 0.85rem;
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

        .status-active {
            background-color: #d4edda;
            color: #155724;
        }

        .status-active::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #34a853;
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

        .no-employees {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }

        .no-employees i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.3;
        }

        @media (max-width: 768px) {
            .search-filter-section {
                flex-direction: column;
                align-items: stretch;
            }

            .search-input {
                max-width: none;
            }

            .employees-table {
                font-size: 0.85rem;
            }

            .employees-table thead th,
            .employees-table tbody td {
                padding: 0.75rem 0.5rem;
            }

            .employee-info {
                gap: 0.5rem;
            }

            .employee-avatar {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
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
                <!-- Active Employees Tab -->
                <div id="employees">
                    <!-- Section Header -->
                    <div class="section-header">
                        <h2 class="section-title">Active Employees</h2>
                    </div>

                    <!-- Search and Filter Section -->
                    <div class="search-filter-section">
                        <div class="search-input">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="form-control" placeholder="Search employees..." id="employeeSearch">
                        </div>
                        <button class="filter-btn" id="filterBtn">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                    </div>

                    <!-- Employees Table -->
                    <div class="table-responsive">
                        <table class="employees-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Start Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="employeesTableBody">
                                <tr class="employee-row">
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">MC</div>
                                            <div class="employee-details">
                                                <div class="employee-name">Mike Chen</div>
                                                <div class="employee-title">Marketing Manager</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Marketing</td>
                                    <td>2023-03-15</td>
                                    <td>
                                        <span class="status-badge status-active">Active</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="employee-row">
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">SJ</div>
                                            <div class="employee-details">
                                                <div class="employee-name">Sarah Johnson</div>
                                                <div class="employee-title">Software Developer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Engineering</td>
                                    <td>2024-08-01</td>
                                    <td>
                                        <span class="status-badge status-active">Active</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="employee-row">
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">AR</div>
                                            <div class="employee-details">
                                                <div class="employee-name">Alex Rodriguez</div>
                                                <div class="employee-title">UX Designer</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Design</td>
                                    <td>2023-11-20</td>
                                    <td>
                                        <span class="status-badge status-active">Active</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="employee-row">
                                    <td>
                                        <div class="employee-info">
                                            <div class="employee-avatar">LW</div>
                                            <div class="employee-details">
                                                <div class="employee-name">Lisa Wang</div>
                                                <div class="employee-title">HR Specialist</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>Human Resources</td>
                                    <td>2022-05-10</td>
                                    <td>
                                        <span class="status-badge status-active">Active</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="action-btn" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="action-btn" title="Edit Employee">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- No Results Message (Hidden by default) -->
                    <div class="no-employees" id="noEmployees" style="display: none;">
                        <i class="fas fa-users"></i>
                        <h4>No employees found</h4>
                        <p>Try adjusting your search criteria or filters.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script_links')
    @include('administration.lifecycle.partials.js-includes')
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
            // Search functionality
            const searchInput = document.getElementById('employeeSearch');
            const employeeRows = document.querySelectorAll('.employee-row');
            const noEmployeesMessage = document.getElementById('noEmployees');
            const employeesTable = document.querySelector('.employees-table');

            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleRows = 0;

                employeeRows.forEach(row => {
                    const employeeName = row.querySelector('.employee-name').textContent.toLowerCase();
                    const employeeTitle = row.querySelector('.employee-title').textContent.toLowerCase();
                    const department = row.cells[1].textContent.toLowerCase();

                    if (employeeName.includes(searchTerm) || 
                        employeeTitle.includes(searchTerm) || 
                        department.includes(searchTerm)) {
                        row.style.display = '';
                        visibleRows++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleRows === 0 && searchTerm !== '') {
                    employeesTable.style.display = 'none';
                    noEmployeesMessage.style.display = 'block';
                } else {
                    employeesTable.style.display = '';
                    noEmployeesMessage.style.display = 'none';
                }
            });

            // Action button functionality
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const action = this.title;
                    const employeeName = this.closest('tr').querySelector('.employee-name').textContent;
                    
                    if (action.includes('View')) {
                        alert(`Viewing details for ${employeeName}`);
                    } else if (action.includes('Edit')) {
                        alert(`Editing ${employeeName}`);
                    }
                });
            });

            // Filter button functionality
            document.getElementById('filterBtn').addEventListener('click', function() {
                alert('Filter functionality would open a dropdown or modal with filter options');
            });
        });
    </script>
@endsection
