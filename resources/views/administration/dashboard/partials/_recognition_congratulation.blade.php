@if ($latestRecognition)
    <div class="row mb-4 justify-content-center">
        <div class="col-md-4">
            <div class="recognition-card bg-white p-3 pb-4 rounded-3 shadow-sm text-center">
                <!-- Header -->
                <div class="d-block align-items-center mb-1">
                    @if (auth()->user()->hasMedia('avatar'))
                        <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ auth()->user()->name }} Avatar" class="rounded-circle me-2" width="80px">
                    @else
                        <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold rounded-circle me-2">
                            {{ profile_name_pic(auth()->user()) }}
                        </span>
                    @endif
                    <br>
                    <div>
                        <strong class="text-dark"><span id="employee-name">You</span></strong>
                        <span class="text-muted"> received </span>
                        <strong class="text-primary" id="recognition-category">{{ $latestRecognition->category }}</strong>
                        <span class="text-muted"> from </span>
                        <strong id="leader-name">{{ $latestRecognition->recognizer->alias_name }}</strong><br>
                        <small class="text-muted">{{ show_date($latestRecognition->created_at) }}</small>
                    </div>
                </div>

                <!-- Comment -->
                <p class="mb-2 text-muted" id="recognition-comment">
                    {{ $latestRecognition->comment }}
                </p>

                <!-- Congrats Card -->
                <div class="congrats-card p-2 rounded-3 text-center">
                    <div class="position-relative">
                        <img src="{{ asset('assets/img/custom_images/recognition_congrats.svg') }}" alt="Illustration" class="img-fluid rounded mb-3 pb-3" width="60%">
                        <div class="ribbon bg-primary mt-2">
                            <span id="ribbon-text">{{ $latestRecognition->category }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
