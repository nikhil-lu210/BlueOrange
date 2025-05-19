<div class="card card-border-shadow-primary mb-4">
    <div class="card-header header-elements">
        <h5 class="mb-0">IT Ticket Comments</h5>

        <div class="card-header-elements ms-auto">
            <button type="button" class="btn btn-sm btn-primary" title="Create Comment" data-bs-toggle="collapse" data-bs-target="#collapseComments" aria-expanded="false" aria-controls="collapseComments">
                <span class="tf-icon ti ti-message-circle ti-xs me-1"></span>
                Comment
            </button>
        </div>
    </div>
    <!-- Account -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('administration.ticket.it_ticket.store.comment', ['it_ticket' => $itTicket]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="collapse" id="collapseComments">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea class="form-control" name="comment" rows="2" placeholder="Ex: Your IT Ticket has been solved." required>{{ old('comment') }}</textarea>
                                @error('comment')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-sm btn-block mt-2 mb-3">
                                    <i class="ti ti-check"></i>
                                    Submit Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 comments">
                <table class="table">
                    <tbody>
                        @foreach ($itTicket->comments as $comment)
                            <tr class="border-0 border-bottom-0">
                                <td class="border-0 border-bottom-0">
                                    <div class="d-flex justify-content-between align-items-center user-name">
                                        {!! show_user_name_and_avatar($comment->commenter, name: null) !!}
                                        <small class="date-time text-muted">{{ date_time_ago($comment->created_at) }}</small>
                                    </div>
                                    <div class="d-flex mt-2">
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>