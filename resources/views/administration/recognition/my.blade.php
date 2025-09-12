@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('My Recognitions'))

@section('css_links')
    {{--  External CSS  --}}
    <!-- DataTables css -->
    <link href="{{ asset('assets/css/custom_css/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/custom_css/datatables/datatable.css') }}" rel="stylesheet" type="text/css" />

    {{-- Select 2 --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        .marks {
            width: 60px;
            height: 60px;
            display: grid;
            grid-template-areas:
                "got divider"
                "divider total";
            place-items: center;
            position: relative;
            background: #f5f4ff;
            border: 1px solid #b7b4f2;
            border-radius: 6px;
            font-family: system-ui, sans-serif;
        }
        .marks .mark-got {
            grid-area: got;
            font-size: 18px;
            font-weight: 700;
            color: #2c2c54;
            justify-self: start;
            align-self: start;
            margin: -10px;
        }
        .marks .total-mark {
            grid-area: total;
            font-size: 16px;
            font-weight: 500;
            color: #555;
            justify-self: end;
            align-self: end;
            margin: 6px;
        }
        .marks::before {
            content: "";
            position: absolute;
            width: 120%;
            height: 1px;
            background: #b7b4f2;
            transform: rotate(-45deg);
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('My Recognitions') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Recognition') }}</li>
    <li class="breadcrumb-item active">{{ __('My Recognitions') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.recognition.my') }}" method="get" autocomplete="off">
            <div class="card card-border-shadow-primary mb-4 border-0">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Filter My Recognitions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select @error('category') is-invalid @enderror">
                                <option value="" {{ is_null(request()->category) ? 'selected' : '' }}>All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ $category == request()->category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="min_score" class="form-label">Min Score</label>
                            <input type="number" name="min_score" id="min_score" value="{{ request()->min_score }}" 
                                   min="{{ config('recognition.marks.min') }}" max="{{ config('recognition.marks.max') }}"
                                   class="form-control @error('min_score') is-invalid @enderror" placeholder="Min Score" />
                            @error('min_score')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="max_score" class="form-label">Max Score</label>
                            <input type="number" name="max_score" id="max_score" value="{{ request()->max_score }}" 
                                   min="{{ config('recognition.marks.min') }}" max="{{ config('recognition.marks.max') }}"
                                   class="form-control @error('max_score') is-invalid @enderror" placeholder="Max Score" />
                            @error('max_score')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3 d-flex align-items-end">
                            @if (request()->category || request()->min_score || request()->max_score)
                                <a href="{{ route('administration.recognition.my') }}" class="btn btn-danger me-2">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    Reset Filters
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                Filter Recognitions
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-header header-elements">
                <h5 class="mb-0">My Recognitions</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.recognition.analytics') }}" class="btn btn-sm btn-info me-2">
                        <span class="tf-icon ti ti-chart-bar ti-xs me-1"></span>
                        Analytics
                    </a>
                    <a href="{{ route('administration.recognition.leaderboard') }}" class="btn btn-sm btn-success">
                        <span class="tf-icon ti ti-trophy ti-xs me-1"></span>
                        Leaderboard
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recognitions->count() > 0)
                    <div class="table-responsive text-nowrap">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sl.</th>
                                    <th>Score</th>
                                    <th>Category</th>
                                    <th>Comment</th>
                                    <th>Recognizer</th>
                                    <th>Date</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recognitions as $key => $recognition)
                                    <tr>
                                        <th>#{{ serial($recognitions, $key) }}</th>
                                        <td class="text-center">
                                            <div class="marks">
                                                <span class="mark-got">{{ $recognition->total_mark }}</span>
                                                <span class="total-mark">{{ config('recognition.marks.max') }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ $recognition->category }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ strip_tags($recognition->comment) }}">
                                                {!! show_content(strip_tags($recognition->comment), 50) !!}
                                            </div>
                                        </td>
                                        <td>
                                            {!! show_user_name_and_avatar($recognition->recognizer, name: null) !!}
                                        </td>
                                        <td>
                                            <b>{{ show_date($recognition->created_at) }}</b>
                                            <br>
                                            <small class="text-muted">{{ show_time($recognition->created_at) }}</small>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('administration.recognition.show', $recognition) }}" class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" title="View Details">
                                                <i class="text-white ti ti-info-hexagon"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="ti ti-award" style="font-size: 4rem; color: #ddd;"></i>
                        </div>
                        <h5 class="text-muted">No Recognitions Found</h5>
                        <p class="text-muted">You haven't received any recognitions yet.</p>
                    </div>
                @endif
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
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $(document).ready(function() {
            $('.bootstrap-select').each(function() {
                if (!$(this).data('bs.select')) {
                    $(this).selectpicker();
                }
            });
        });
    </script>
@endsection
