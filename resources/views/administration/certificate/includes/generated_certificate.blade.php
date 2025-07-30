<form action="{{ route('administration.certificate.store') }}" method="post">
    @csrf
    <div class="card mb-4">
        <div class="card-header header-elements">
            <h5 class="mb-0">Generated Certificate Preview</h5>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolor atque aliquid laudantium perspiciatis ad, eum fugiat! Voluptatum omnis, dignissimos ducimus quod perferendis, quia mollitia accusamus magnam sit nemo totam laboriosam.
                </div>

                <div class="col-md-12 text-end mt-3">
                    <button type="submit" class="btn btn-success">
                        <span class="tf-icon ti ti-check ti-xs me-1"></span>
                        {{ __('Create & Issue Certificate') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
