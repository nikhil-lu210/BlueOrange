@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Create Income'))

@section('css_links')
    {{--  External CSS  --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

    {{-- Bootstrap Datepicker --}}
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/flatpickr/flatpickr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
        /* Custom CSS Here */
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            margin: 0;
        }
    </style>
@endsection

@section('page_name')
    <b class="text-uppercase">{{ __('Create Income') }}</b>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Income & Expense') }}</li>
    <li class="breadcrumb-item">{{ __('Income') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.accounts.income_expense.income.index') }}">{{ __('All Incomes') }}</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.accounts.income_expense.income.show', ['income' => $income]) }}">{{ __('Income Details') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Edit Income') }}</li>
@endsection

@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('Submit Daily Work Update') }}</h5>

                <div class="card-header-elements ms-auto">
                    <a href="{{ route('administration.accounts.income_expense.income.index') }}" class="btn btn-sm btn-primary">
                        <span class="tf-icon ti ti-circle ti-xs me-1"></span>
                        All Incomes
                    </a>
                </div>
            </div>
            <!-- Account -->
            <div class="card-body">
                <form id="workUpdateForm" action="{{ route('administration.accounts.income_expense.income.update', ['income' => $income]) }}" method="post" enctype="multipart/form-data" autocomplete="off">
                    @csrf
                    @method('PUT')
                    <div class="row justify-content-center">
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Income Date <strong class="text-danger">*</strong></label>
                            <input type="text" name="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" class="form-control date-picker" placeholder="YYYY-MM-DD" required/>
                            @error('date')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-9">
                            <label class="form-label">Income Source <strong class="text-danger">*</strong></label>
                            <input type="text" name="source" value="{{ old('source', $income->source) }}" class="form-control" placeholder="Ex: Sold Old Gadgets" required/>
                            @error('source')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-4">
                            <label for="category_id" class="form-label">Select Category <strong class="text-danger">*</strong></label>
                            <select name="category_id" class="select2 form-select @error('category_id') is-invalid @enderror" data-allow-clear="true" required autofocus>
                                <option value="" selected disabled>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        @selected(old('category_id', $income->category_id) == $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label class="form-label">Total Income <strong class="text-danger">*</strong></label>
                            <div class="input-group input-group-merge">
                                <input type="number" min="0" name="total" value="{{ old('total', $income->total) }}" placeholder="Ex: 50000" class="form-control" required>
                                <span class="input-group-text"><i class="ti ti-currency-taka"></i></span>
                            </div>
                            @error('total')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-5">
                            <label for="files[]" class="form-label">{{ __('Files') }}</label>
                            <input type="file" id="files[]" name="files[]" class="form-control @error('files') is-invalid @enderror" multiple accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.xls,.xlsx,.doc,.docx,.txt,.csv,.zip,.rar"/>
                            @error('files.*')
                                <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-12">
                            <label class="form-label">Income Description <strong class="text-danger">*</strong></label>
                            <div name="description" id="incomeDescriptionEditor">{!! old('description', $income->description) !!}</div>
                            <textarea class="d-none" name="description" id="incomeDescriptionInput">{{ old('description', $income->description) }}</textarea>
                            @error('description')
                                <b class="text-danger">{{ $message }}</b>
                            @enderror
                        </div>
                    </div>
                    <div class="mt-2 float-end">
                        <a href="{{ route('administration.accounts.income_expense.income.create') }}" class="btn btn-outline-danger me-2 confirm-danger">Reset Form</a>
                        <button type="submit" class="btn btn-primary">Submit Work Update</button>
                    </div>
                </form>
            </div>
            <!-- /Account -->
        </div>
    </div>
</div>
<!-- End row -->

@endsection

@section('script_links')
    {{--  External Javascript Links --}}
    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-timepicker/jquery-timepicker.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/form-layouts.js') }}"></script>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
@endsection

@section('custom_script')
    {{--  External Custom Javascript  --}}
    <script>
        $('.date-picker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            orientation: 'auto right'
        });
    </script>
    <script>
        $(document).ready(function () {
            var fullToolbar = [
                [{ font: [] }, { size: [] }],
                ["bold", "italic", "underline", "strike"],
                [{ color: [] }, { background: [] }],
                ["link"],
                [{ header: "1" }, { header: "2" }, "blockquote", "code-block"],
                [{ list: "ordered" }, { list: "bullet" }, { indent: "-1" }, { indent: "+1" }],
            ];

            var incomeDescriptionEditor = new Quill("#incomeDescriptionEditor", {
                bounds: "#incomeDescriptionEditor",
                placeholder: "Your Income Description Here...",
                modules: {
                    formula: true,
                    toolbar: fullToolbar,
                },
                theme: "snow",
            });

            // Set the editor content to the old description if validation fails
            @if(old('description'))
                incomeDescriptionEditor.root.innerHTML = {!! json_encode(old('description')) !!};
            @endif

            $('#workUpdateForm').on('submit', function() {
                $('#incomeDescriptionInput').val(incomeDescriptionEditor.root.innerHTML);
            });
        });
    </script>
@endsection
