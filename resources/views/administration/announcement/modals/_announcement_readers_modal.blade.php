{{-- Read By At Modal --}}
<div class="modal fade" data-bs-backdrop="static" id="showAnnouncementReadersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ti ti-eye me-2"></i>
                    Announcement Readers
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($announcementStats['readCount'] > 0)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">{{ $announcementStats['readCount'] }} {{ $announcementStats['readCount'] == 1 ? 'person has' : 'people have' }} read this announcement</span>
                            <span class="badge bg-label-success">{{ round(($announcementStats['readCount'] / $announcementStats['totalRecipients']) * 100, 1) }}%</span>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Reader</th>
                                    <th>Read At</th>
                                    <th>Time Ago</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($announcementStats['readByData'] as $readByAt)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <div class="avatar-initial bg-label-primary rounded-circle">
                                                        {{ substr(show_employee_data($readByAt['read_by'], 'alias_name') ?? show_user_data($readByAt['read_by'], 'name'), 0, 1) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">{{ show_employee_data($readByAt['read_by'], 'alias_name') ?? show_user_data($readByAt['read_by'], 'name') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ show_date_time($readByAt['read_at']) }}</td>
                                        <td>
                                            <small class="text-muted">{{ date_time_ago($readByAt['read_at']) }}</small>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="ti ti-eye-off fs-1 text-muted mb-3"></i>
                        <h5 class="text-muted">No readers yet</h5>
                        <p class="text-muted">This announcement hasn't been read by anyone yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- Read By At Modal --}}
