<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.recognition.index') }}" method="get" autocomplete="off">
            <div class="card card-border-shadow-primary mb-4 border-0 filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Filter Recognitions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="user_id" class="form-label">Select Employee</label>
                            <select name="user_id" id="user_id" class="select2 form-select @error('user_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->user_id) ? 'selected' : '' }}>Select Employee</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ $user->id == request()->user_id ? 'selected' : '' }}>
                                        {{ $user->alias_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="recognizer_id" class="form-label">Select Recognizer</label>
                            <select name="recognizer_id" id="recognizer_id" class="select2 form-select @error('recognizer_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->recognizer_id) ? 'selected' : '' }}>Select Recognizer</option>
                                @foreach ($users as $user)
                                    @if($user->tl_employees->isNotEmpty())
                                        <option value="{{ $user->id }}" {{ $user->id == request()->recognizer_id ? 'selected' : '' }}>
                                            {{ $user->alias_name }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                            @error('recognizer_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="category" class="form-label">Category</label>
                            <select name="category" id="category" class="form-select select2 @error('category') is-invalid @enderror">
                                <option value="" {{ is_null(request()->category) ? 'selected' : '' }}>All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category }}" {{ $category == request()->category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="min_score" class="form-label">Min Score</label>
                            <input type="number" name="min_score" id="min_score" value="{{ request()->min_score }}" min="{{ config('recognition.marks.min') }}" max="{{ config('recognition.marks.max') }}" class="form-control @error('min_score') is-invalid @enderror" placeholder="Min Score" />
                            <small class="text-muted">Min Score: {{ config('recognition.marks.min') }}</small>
                            @error('min_score')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="max_score" class="form-label">Max Score</label>
                            <input type="number" name="max_score" id="max_score" value="{{ request()->max_score }}" min="{{ config('recognition.marks.min') }}" max="{{ config('recognition.marks.max') }}" class="form-control @error('max_score') is-invalid @enderror" placeholder="Max Score" />
                            <small class="text-muted">Max Score: {{ config('recognition.marks.max') }}</small>
                            @error('max_score')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="date_from" class="form-label">Date From</label>
                            <input type="text" name="date_from" id="date_from" value="{{ request()->date_from }}" class="form-control date-picker @error('date_from') is-invalid @enderror" placeholder="Y-m-d" />
                            @error('date_from')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-3">
                            <label for="date_to" class="form-label">Date To</label>
                            <input type="text" name="date_to" id="date_to" value="{{ request()->date_to }}" class="form-control date-picker @error('date_to') is-invalid @enderror" placeholder="Y-m-d" />
                            @error('date_to')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-12 text-end">
                            @if (request()->user_id || request()->recognizer_id || request()->category || request()->min_score || request()->max_score || request()->date_from || request()->date_to)
                                <a href="{{ route('administration.recognition.index') }}" class="btn btn-danger confirm-warning">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    {{ __('Reset Filters') }}
                                </a>
                            @endif
                            <button type="submit" name="filter_recognition" value="true" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                {{ __('Filter Recognitions') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
