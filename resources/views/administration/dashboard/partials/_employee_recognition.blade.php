{{-- Employee Recognition System Section --}}
@if($canGiveRecognition)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Employee Recognition') }}</h5>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#giveRecognitionModal">
                        <i class="fas fa-award me-1"></i> {{ __('Give Recognition') }}
                    </button>
                </div>
                <div class="card-body">
                    {{-- Reminder Banner --}}
                    @if($showRecognitionReminder)
                        <div class="alert alert-warning mb-3">
                            <i class="fas fa-bell"></i> {{ __('You have not given any recognition in the last 15 days. Show your appreciation!') }}
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            {{-- Recent Recognitions List --}}
                            <h6>{{ __('Recent Recognitions') }}</h6>
                            <ul class="list-group mb-3">
                                @forelse($recentRecognitions as $recognition)
                                    <li class="list-group-item">
                                        <strong>
                                            <a href="{{ route('administration.settings.user.user_recognition.index', ['user' => $recognition->employee]) }}" target="_blank" class="text-primary">
                                                {{ $recognition->employee->alias_name }}
                                            </a>
                                        </strong> -
                                        <span class="badge bg-info">{{ $recognition->category }}</span>
                                        <span class="badge bg-success">{{ $recognition->points }} pts</span>
                                        <span class="text-muted">{{ $recognition->created_at->diffForHumans() }}</span>
                                        @if($recognition->comment)
                                            <br><small>{{ $recognition->comment }}</small>
                                        @endif
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">{{ __('No recognitions yet.') }}</li>
                                @endforelse
                            </ul>
                        </div>
                        <div class="col-md-6">
                            {{-- Announcements --}}
                            <h6>{{ __('Recognition Announcements') }}</h6>
                            <ul class="list-group">
                                @forelse($recognitionAnnouncements as $announcement)
                                    <li class="list-group-item">
                                        🎉 <strong>{{ $announcement->employee->alias_name }}</strong> was recognized for <strong>{{ $announcement->category }}</strong> by <strong>{{ $announcement->recognizer->alias_name }}</strong>!
                                        <span class="badge bg-success">{{ $announcement->points }} pts</span>
                                        <span class="text-muted">{{ $announcement->created_at->diffForHumans() }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">{{ __('No announcements yet.') }}</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Give Recognition Modal --}}
    <div class="modal fade" data-bs-backdrop="static" id="giveRecognitionModal" tabindex="-1" aria-labelledby="giveRecognitionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <form method="POST" action="{{ route('administration.dashboard.recognition.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="giveRecognitionModalLabel">{{ __('Give Recognition') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="row modal-body">
                        <div class="col-md-12 mb-3">
                            <label for="employee_id" class="form-label">{{ __('Staff Member') }}</label>
                            <select class="form-select select2" id="employee_id" name="employee_id" required>
                                <option value="">{{ __('Select Staff') }}</option>
                                @foreach($user->tl_employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->alias_name }} ({{ $employee->name }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label">{{ __('Rate by Category') }}</label>
                            <small class="text-muted d-block mb-2">{{ __('Click the stars to rate each category (1 = lowest, 5 = highest)') }}</small>
                            <div class="row justify-content-center">
                                @foreach(['Behavior', 'Appreciation', 'Leadership', 'Loyalty', 'Dedication'] as $cat)
                                    <div class="col-md-5 bg-label-primary p-3 m-1 rounded">
                                        <label class="form-label mb-1 d-flex justify-content-between">
                                            <span>{{ __($cat) }} <b class="text-danger">*</b></span>
                                            <span class="ms-2 text-muted rating-value" id="rating_value_{{ $cat }}"></span>
                                        </label>

                                        {{-- Rating Input --}}
                                        <input type="hidden" name="category_ratings[{{ $cat }}]" id="rating_{{ $cat }}" value="0">
                                        <div class="star-rating" tabindex="0" data-category="{{ $cat }}">
                                            <div class="full-star-ratings" id="full-star_{{ $cat }}" data-rateyo-full-star="true"></div>
                                        </div>

                                        {{-- Individual Comment --}}
                                        <label for="comment_{{ $cat }}" class="form-label mt-2">{{ __('Comment for') }} {{ __($cat) }}</label>
                                        <textarea class="form-control" id="comment_{{ $cat }}" name="category_comments[{{ $cat }}]" rows="2" placeholder="{{ __('Write comment...') }}"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit Recognition') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endif
