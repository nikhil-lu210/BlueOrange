<div class="modal fade" data-bs-backdrop="static" id="showCredentialModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="modal-title mb-2">Credential Details</h3>
                    <p class="text-muted modal-title-info">Details of Vault Credential</p>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-hash text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Name:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="vault-name"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-world-www text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">URL:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="vault-url"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-user text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Creator:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="vault-creator"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-calendar text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Created At:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="vault-created_at"></span>
                            </dd>
                        </dl>
                        <dl class="row mt-3 mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-note text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Note:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="vault-note"></span>
                            </dd>
                        </dl>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="width: 15%;">Username</th>
                                    <td style="width: 70%;"><code class="vault-username"></code></td>
                                    <td style="width: 15%;">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy">
                                            <i class="ti ti-copy"></i> Copy
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="width: 15%;">Password</th>
                                    <td style="width: 70%;"><code class="vault-password"></code></td>
                                    <td style="width: 15%;">
                                        <button type="button" class="btn btn-outline-dark btn-xs copy-btn" title="Click to Copy">
                                            <i class="ti ti-copy"></i> Copy
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
