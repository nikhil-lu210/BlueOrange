<div class="row">
    <div class="col-md-12">
        <div class="card mb-4 border-0">
            <div class="card-body">
                <div class="d-flex justify-content-between flex-wrap gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <span class="bg-label-danger p-2 rounded">
                            <i class="ti ti-hourglass-high ti-xl"></i>
                        </span>
                        <div class="content-right">
                            <h5 class="text-danger mb-0">{{ Number::currency( $total['overall_expense'], 'BDT') }}</h5>
                            <small class="mb-0 text-muted">Total Expense (Overall)</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="bg-label-warning p-2 rounded">
                            <i class="ti ti-hourglass-low ti-xl"></i>
                        </span>
                        <div class="content-right">
                            <h5 class="text-warning mb-0">{{ Number::currency( $total['last_month_expense'], 'BDT') }}</h5>
                            <small class="mb-0 text-muted">Total Expense (Last Month)</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="bg-label-primary p-2 rounded">
                            <i class="ti ti-hourglass-high ti-xl"></i>
                        </span>
                        <div class="content-right">
                            <h5 class="text-primary mb-0">{{ Number::currency( $total['current_month_expense'], 'BDT') }}</h5>
                            <small class="mb-0 text-muted">Total Expense (Current Month)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>