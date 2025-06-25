<!-- question Details Modal -->
<div class="modal fade" data-bs-backdrop="static" id="showQuestionAnswerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Question And Answers Details</h3>
                    <p class="text-muted">Details of <span class="question-title"></span></p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-dark text-bold">Question Details</h5>
                        <dl class="row mb-1">
                            <dt class="col-sm-3 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-question-mark text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Question:</span>
                            </dt>
                            <dd class="col-sm-9">
                                <span class="question-title"></span>
                            </dd>
                        </dl>
                        <dl class="row mb-1">
                            <dt class="col-sm-3 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-list text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Options:</span>
                            </dt>
                            <dd class="col-sm-9">
                                <ol type="A">
                                    <li class="option-a"></li>
                                    <li class="option-b"></li>
                                    <li class="option-c"></li>
                                    <li class="option-d"></li>
                                </ol>
                            </dd>
                        </dl>
                        <dl class="row mb-1">
                            <dt class="col-sm-3 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-user-cog text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Creator:</span>
                            </dt>
                            <dd class="col-sm-9">
                                <span class="question-creator"></span>
                            </dd>
                        </dl>
                    </div>

                    <div class="col-md-6">
                        <h5 class="text-dark text-bold">Answer Details</h5>
                        <dl class="row mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-info-circle text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Selected Answer:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="selected-answer"></span>
                            </dd>
                        </dl>
                        <dl class="row mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-check text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Correct Answer:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="correct-answer text-success"></span>
                            </dd>
                        </dl>
                        <dl class="row mb-1">
                            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                                <i class="ti ti-clock text-heading"></i>
                                <span class="fw-medium mx-2 text-heading">Answered At:</span>
                            </dt>
                            <dd class="col-sm-8">
                                <span class="answered-at"></span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ question Details Modal -->
