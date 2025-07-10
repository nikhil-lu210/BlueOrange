@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}

@endsection

@section('page_title', __('Daily Work Update'))

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
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('All Daily Work Updates') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Daily Work Update') }}</li>
    <li class="breadcrumb-item active">{{ __('All Daily Work Updates') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <form action="{{ route('administration.daily_work_update.index') }}" method="get" autocomplete="off">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="team_leader_id" class="form-label">{{ __('Select Team Leader') }}</label>
                            <select name="team_leader_id" id="team_leader_id" class="select2 form-select @error('team_leader_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->team_leader_id) ? 'selected' : '' }}>{{ __('Select Team Leader') }}</option>
                                @foreach ($teamLeaders as $leader)
                                    <option value="{{ $leader->id }}" {{ $leader->id == request()->team_leader_id ? 'selected' : '' }}>
                                        {{ get_employee_name($leader) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team_leader_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">Select Employee</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>Select Employee</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $user)
                                            <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                                {{ get_employee_name($user) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-2">
                            <label class="form-label">Work Updates Of</label>
                            <input type="text" name="created_month_year" value="{{ request()->created_month_year ?? old('created_month_year') }}" class="form-control month-year-picker" placeholder="MM yyyy" tabindex="-1"/>
                            @error('created_month_year')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-2">
                            <label for="status" class="form-label">{{ __('Status') }}</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror"  data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>{{ __('Select status') }}</option>
                                <option value="Reviewed" {{ request()->status == 'Reviewed' ? 'selected' : '' }}>{{ __('Reviewed') }}</option>
                                <option value="Pending" {{ request()->status == 'Pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->user_id || request()->created_month_year)
                            <a href="{{ route('administration.daily_work_update.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" name="filter_work_updates" value="true" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Work Updates
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">
                    <span>All Work Updates</span>
                    <span>of</span>
                    <span class="text-bold">{{ request()->user_id ? show_user_data(request()->user_id, 'name') : 'All Users' }}</span>
                    <sup>(<b>Month: </b> {{ request()->created_month_year ? request()->created_month_year : date('F Y') }})</sup>
                </h5>

                <div class="card-header-elements ms-auto">
                    @can ('Daily Work Update Create')
                        <a href="{{ route('administration.daily_work_update.create') }}" class="btn btn-sm btn-primary">
                            <span class="tf-icon ti ti-plus ti-xs me-1"></span>
                            Create Work Update
                        </a>
                    @endcan
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive-md table-responsive-sm w-100">
                    <table class="table data-table table-bordered">
                        <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Employee</th>
                                <th>Team Leader</th>
                                <th>Submitted At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dailyWorkUpdates as $key => $dailyUpdate)
                                <tr class="@if (is_null($dailyUpdate->rating)) bg-label-danger @endif">
                                    <th>#{{ serial($dailyWorkUpdates, $key) }}</th>
                                    <td>
                                        <b>{{ show_date($dailyUpdate->date) }}</b>
                                        <br>
                                        @if (!is_null($dailyUpdate->rating))
                                            @php
                                                switch ($dailyUpdate->rating) {
                                                    case '1':
                                                        $color = 'danger';
                                                        break;
                                                    case '2':
                                                        $color = 'warning';
                                                        break;
                                                    case '3':
                                                        $color = 'dark';
                                                        break;
                                                    case '4':
                                                        $color = 'primary';
                                                        break;
                                                    default:
                                                        $color = 'success';
                                                        break;
                                                }
                                            @endphp
                                            <small class="badge bg-{{ $color }} rating-display">
                                                {{ $dailyUpdate->rating }} out of 5
                                            </small>
                                        @else
                                            <small class="badge bg-danger rating-display">
                                                {{ __('Not Reviewed') }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($dailyUpdate->user, role: null) !!}
                                    </td>
                                    <td>
                                        {!! show_user_name_and_avatar($dailyUpdate->team_leader, role: null) !!}
                                    </td>
                                    <td>
                                        <small class="text-bold">{{ show_date($dailyUpdate->created_at) }}</small>
                                        <br>
                                        <span>
                                            at
                                            <small class="text-bold">{{ show_time($dailyUpdate->created_at) }}</small>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @can ('Daily Work Update Delete')
                                            <a href="{{ route('administration.daily_work_update.destroy', ['daily_work_update' => $dailyUpdate]) }}" class="btn btn-sm btn-icon btn-danger confirm-danger" data-bs-toggle="tooltip" title="Delete Daily Work Update?">
                                                <i class="text-white ti ti-trash"></i>
                                            </a>
                                        @endcan
                                        @can ('Daily Work Update Read')
                                            <a href="{{ route('administration.daily_work_update.show', ['daily_work_update' => $dailyUpdate]) }}" target="_blank" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="Show Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        @endcan

                                        @canany (['Daily Work Update Everything', 'Daily Work Update Update'])
                                            <div class="dropdown mt-1">
                                                @if (!is_null($dailyUpdate->rating))
                                                    <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" id="dropdownMenuButton{{ $dailyUpdate->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="ti ti-star-filled me-1"></i>{{ $dailyUpdate->rating }}
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-dark dropdown-toggle" type="button" id="dropdownMenuButton{{ $dailyUpdate->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="text-white ti ti-check"></i>
                                                    </button>
                                                @endif
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton{{ $dailyUpdate->id }}">
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li>
                                                            <a class="dropdown-item rating-update" href="#"
                                                            data-url="{{ route('administration.daily_work_update.update', ['daily_work_update' => $dailyUpdate]) }}"
                                                            data-rating="{{ $i }}">
                                                                <i class="ti ti-star me-2"></i>{{ $i }}
                                                            </a>
                                                        </li>
                                                    @endfor
                                                </ul>
                                            </div>
                                        @endcanany
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
    <!-- Datatable js -->
    <script src="{{ asset('assets/js/custom_js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom_js/datatables/datatable.js') }}"></script>

    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        // Custom Script Here
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) { // Check if it's already initialized
                    $(this).selectpicker();
                }
            });

            $('.month-year-picker').datepicker({
                format: 'MM yyyy',         // Display format to show full month name and year
                minViewMode: 'months',     // Only allow month selection
                todayHighlight: true,
                autoclose: true,
                orientation: 'auto right'
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('.rating-update').click(function(e) {
                e.preventDefault();

                var $this = $(this);
                var url = $this.data('url');
                var rating = $this.data('rating');
                var $dropdown = $this.closest('.dropdown');
                var $button = $dropdown.find('.dropdown-toggle');

                // Debug logging
                console.log('Rating Update Request:', {
                    url: url,
                    rating: rating
                });

                // Show loading state
                var originalButtonHtml = $button.html();
                $button.html('<i class="text-white ti ti-loader-2 ti-spin"></i>').prop('disabled', true);

                // Close dropdown
                $dropdown.find('.dropdown-menu').removeClass('show');
                $button.removeClass('show').attr('aria-expanded', 'false');

                $.ajax({
                    url: url,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    data: {
                        _token: '{{ csrf_token() }}',
                        rating: rating
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update button to show rating with new styling
                            $button.html('<i class="ti ti-star-filled me-1"></i>' + rating)
                                   .removeClass('btn-dark btn-primary')
                                   .addClass('btn-outline-dark')
                                   .prop('disabled', false);

                            // Show toast success message
                            @if(function_exists('toast'))
                                // Use Laravel SweetAlert toast
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Rating Updated!',
                                    text: response.message || 'Daily Work Update has been rated successfully.',
                                    timer: 3000,
                                    showConfirmButton: false,
                                    toast: true,
                                    position: 'top-end',
                                    timerProgressBar: true
                                });
                            @else
                                // Fallback to toastr if available
                                if (typeof toastr !== 'undefined') {
                                    toastr.success(response.message || 'Rating updated successfully');
                                } else {
                                    alert(response.message || 'Rating updated successfully');
                                }
                            @endif

                            // Update the rating display in the row if exists
                            var $ratingCell = $this.closest('tr').find('.rating-display');
                            if ($ratingCell.length) {
                                // Determine badge color based on rating
                                var badgeColor = 'success';
                                switch(rating) {
                                    case '1': badgeColor = 'danger'; break;
                                    case '2': badgeColor = 'warning'; break;
                                    case '3': badgeColor = 'dark'; break;
                                    case '4': badgeColor = 'primary'; break;
                                    default: badgeColor = 'success'; break;
                                }
                                $ratingCell.removeClass('bg-danger bg-warning bg-dark bg-primary bg-success')
                                          .addClass('bg-' + badgeColor)
                                          .text(rating + ' out of 5');
                            }

                            // Remove the danger background from the row if it was unrated
                            $this.closest('tr').removeClass('bg-label-danger');
                        } else {
                            throw new Error(response.message || 'Unknown error occurred');
                        }
                    },
                    error: function(xhr) {
                        // Restore original button state
                        $button.html(originalButtonHtml).prop('disabled', false);

                        // Log error details for debugging
                        console.log('AJAX Error Details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            url: url,
                            rating: rating
                        });

                        var errorMessage = 'Failed to update rating';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Daily Work Update not found. Please refresh the page and try again.';
                        } else if (xhr.status === 422) {
                            errorMessage = 'Validation error occurred';
                        } else if (xhr.status === 403) {
                            errorMessage = 'You do not have permission to perform this action';
                        } else if (xhr.status === 500) {
                            errorMessage = 'Server error occurred. Please try again.';
                        }

                        // Show toast error message
                        @if(function_exists('toast'))
                            // Use Laravel SweetAlert toast
                            Swal.fire({
                                icon: 'error',
                                title: 'Rating Failed!',
                                text: errorMessage,
                                timer: 4000,
                                showConfirmButton: false,
                                toast: true,
                                position: 'top-end',
                                timerProgressBar: true
                            });
                        @else
                            // Fallback to toastr if available
                            if (typeof toastr !== 'undefined') {
                                toastr.error(errorMessage);
                            } else {
                                alert('Error: ' + errorMessage);
                            }
                        @endif
                    }
                });
            });
        });
    </script>
@endsection
