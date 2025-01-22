@extends('layouts.administration.app')

@section('meta_tags')
    {{--  External META's  --}}
@endsection

@section('page_title', __('Expense Details'))

@section('css_links')
    {{--  External CSS  --}}
@endsection

@section('custom_css')
    {{--  External CSS  --}}
    <style>
    /* Custom CSS Here */
    </style>
@endsection


@section('page_name')
    <b class="text-uppercase">{{ __('Expense Details') }}</b>
@endsection


@section('breadcrumb')
    <li class="breadcrumb-item">{{ __('Income & Expense') }}</li>
    <li class="breadcrumb-item">{{ __('Expense') }}</li>
    <li class="breadcrumb-item">
        <a href="{{ route('administration.accounts.income_expense.expense.index') }}">{{ __('All Expenses') }}</a>
    </li>
    <li class="breadcrumb-item active">{{ __('Expense Details') }}</li>
@endsection


@section('content')

<!-- Start row -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0"> 
                    Expense's Details
                </h5>
        
                @canany(['Expense Update', 'Expense Delete'])
                    <div class="card-header-elements ms-auto">
                        <a href="{{ route('administration.accounts.income_expense.expense.edit', ['expense' => $expense]) }}" class="btn btn-sm btn-primary confirm-warning me-1">
                            <span class="tf-icon ti ti-edit ti-xs me-1"></span>
                            Edit
                        </a>
                        <a href="{{ route('administration.accounts.income_expense.expense.index') }}" class="btn btn-sm btn-dark">
                            <span class="tf-icon ti ti-arrow-left ti-xs me-1"></span>
                            Back
                        </a>
                    </div>
                @endcanany
            </div>
            <div class="card-body">
                <div class="row justify-content-left">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <small class="card-text text-uppercase">Expense Info</small>
                                <dl class="row mt-3 mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-hash"></i>
                                        <span class="fw-medium mx-2 text-heading">Source:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{{ $expense->title }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-sitemap"></i>
                                        <span class="fw-medium mx-2 text-heading">Category:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark">{{ $expense->category->name }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-currency-taka" style="margin-top: -5px;"></i>
                                        <span class="fw-medium mx-2 text-heading">Unit Price:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{{ format_currency($expense->price, 'BDT') }}</span>
                                        <br>
                                        <small class="text-muted text-capitalize"><b>Quantity: </b>{{ $expense->quantity }}</small>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-currency-taka" style="margin-top: -5px;"></i>
                                        <span class="fw-medium mx-2 text-heading">Total Expense:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark text-bold">{{ format_currency($expense->total, 'BDT') }}</span>
                                        <br>
                                        <small class="text-muted text-capitalize">{{ spell_number($expense->total) }}</small>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-calendar-pause"></i>
                                        <span class="fw-medium mx-2 text-heading">Expense Date:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark">{{ show_date($expense->date) }}</span>
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-user-cog"></i>
                                        <span class="fw-medium mx-2 text-heading">Created By:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        {!! show_user_name_and_avatar($expense->creator, name: null) !!}
                                    </dd>
                                </dl>
                                <dl class="row mb-1">
                                    <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                        <i class="ti ti-clock-edit"></i>
                                        <span class="fw-medium mx-2 text-heading">Stored At:</span>
                                    </dt>
                                    <dd class="col-sm-8">
                                        <span class="text-dark">{{ show_date_time($expense->created_at) }}</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>

                        @if ($expense->files->count() > 0)
                            <div class="card mb-4">
                                <div class="card-header header-elements pt-3 pb-3">
                                    <h5 class="mb-0">Expense Proof File(s)</h5>
                                </div>
                                
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th class="text-center">Size</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($expense->files as $file) 
                                                    <tr>
                                                        <td>
                                                            <b class="text-dark" title="{{ $file->original_name }}">
                                                                {{ show_content($file->original_name, 20) }}
                                                            </b>
                                                        </td>
                                                        <td class="text-center">{{ get_file_media_size($file) }}</td>
                                                        <td class="text-center">
                                                            @if ($expense->creator_id == auth()->user()->id) 
                                                                <a href="{{ file_media_destroy($file) }}" class="btn btn-icon btn-label-danger btn-sm waves-effect confirm-danger" title="Delete {{ $file->original_name }}">
                                                                    <i class="ti ti-trash"></i>
                                                                </a>
                                                            @endif
                                                            <a href="{{ file_media_download($file) }}" target="_blank" class="btn btn-icon btn-primary btn-sm waves-effect" title="Download {{ $file->original_name }}">
                                                                <i class="ti ti-download"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <div class="card card-action mb-4">
                            <div class="card-header align-items-center pb-3 pt-3">
                                <h5 class="card-action-title mb-0">Expense Description</h5>
                            </div>
                            <div class="card-body">
                                <div class="description">
                                    {!! $expense->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
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
        // 
    </script>    
@endsection
