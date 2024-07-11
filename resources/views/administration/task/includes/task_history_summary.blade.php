<div class="card card-action mb-4">
    <div class="card-header align-items-center">
        <h5 class="card-action-title mb-0">Task History Summary</h5>
        @if (auth()->user()->id == $task->creator->id) 
            <div class="card-action-element">
                <div class="dropdown">
                    <button type="button" class="btn dropdown-toggle hide-arrow p-0" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="ti ti-dots-vertical text-muted"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:void(0);">
                                <i class="ti ti-history me-1 fs-5" style="margin-top: -2px;"></i>
                                History Details
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
    <div class="card-body pb-0">
        <ul class="timeline ms-1 mb-0">
            <li class="timeline-item timeline-item-transparent">
                <span class="timeline-indicator-advanced timeline-indicator-primary">
                  <i class="ti ti-send rounded-circle scaleX-n1-rtl"></i>
                </span>
                <div class="timeline-event">
                    <div class="timeline-header">
                        <h6 class="mb-0">Project status updated</h6>
                        <small class="text-muted">10 Day Ago</small>
                    </div>
                    <p class="mb-0">Woocommerce iOS App Completed</p>
                </div>
            </li>
            <li class="timeline-item timeline-item-transparent border-transparent">
                <span class="timeline-indicator-advanced timeline-indicator-primary">
                  <i class="ti ti-send rounded-circle scaleX-n1-rtl"></i>
                </span>
                <div class="timeline-event">
                    <div class="timeline-header">
                        <h6 class="mb-0">Project status updated</h6>
                        <small class="text-muted">10 Day Ago</small>
                    </div>
                    <p class="mb-0">Woocommerce iOS App Completed</p>
                </div>
            </li>
        </ul>
    </div>    
</div>