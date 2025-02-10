<div class="modal fade" data-bs-backdrop="static" id="approveAttendanceIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Approve Issue</h3>
                    <p class="text-muted">Approve the attendance issue</p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Read By</th>
                                    <th>Read At</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- @foreach (json_decode($announcement->read_by_at, true) as $readByAt)
                                    <tr>
                                        <td>{{ show_user_data($readByAt['read_by'], 'name') }}</td>
                                        <td>{{ show_date_time($readByAt['read_at']) }}</td>
                                    </tr>
                                @endforeach --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>