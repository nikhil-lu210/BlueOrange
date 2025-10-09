{{-- Statistics Modal --}}
<div class="modal fade" data-bs-backdrop="static" id="announcementStatsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-chart-bar me-2"></i>
                    {{ __('Announcement Statistics') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-0 bg-label-primary shadow-none">
                            <div class="card-body text-center">
                                <div class="fs-1 text-primary mb-2">{{ $announcementStats['readCount'] }}</div>
                                <div class="text-muted">{{ __('Read') }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-0 bg-label-warning shadow-none">
                            <div class="card-body text-center">
                                <div class="fs-1 text-warning mb-2">{{ $announcementStats['unreadCount'] }}</div>
                                <div class="text-muted">{{ __('Unread') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($announcementStats['readCount'] > 0)
                    <div class="mb-4">
                        <h6>{{ __('Read Progress') }}</h6>
                        <div class="progress" style="height: 20px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $announcementStats['totalRecipients'] > 0 ? ($announcementStats['readCount'] / $announcementStats['totalRecipients']) * 100 : 0 }}%">
                                {{ $announcementStats['totalRecipients'] > 0 ? round(($announcementStats['readCount'] / $announcementStats['totalRecipients']) * 100, 1) : 0 }}%
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-initial bg-label-primary rounded">
                                    <i class="ti ti-users"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ __('Total Recipients') }}</h6>
                                <small class="text-muted">{{ $announcementStats['totalRecipients'] }} {{ $announcementStats['totalRecipients'] == 1 ? 'person' : 'people' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-initial bg-label-info rounded">
                                    <i class="ti ti-message-circle"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ __('Comments') }}</h6>
                                <small class="text-muted">{{ $announcement->comments->count() }} {{ $announcement->comments->count() == 1 ? 'comment' : 'comments' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-initial bg-label-secondary rounded">
                                    <i class="ti ti-paperclip"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ __('Attached Files') }}</h6>
                                <small class="text-muted">{{ $announcement->files->count() }} {{ $announcement->files->count() == 1 ? 'file' : 'files' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar avatar-sm me-3">
                                <div class="avatar-initial bg-label-dark rounded">
                                    <i class="ti ti-calendar"></i>
                                </div>
                            </div>
                            <div>
                                <h6 class="mb-0">{{ __('Created') }}</h6>
                                <small class="text-muted">{{ date_time_ago($announcement->created_at) }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Statistics Modal --}}
