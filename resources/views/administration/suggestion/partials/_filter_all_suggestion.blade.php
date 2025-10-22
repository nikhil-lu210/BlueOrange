<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.suggestion.index') }}" method="get" autocomplete="off">
            <div class="card card-border-shadow-primary mb-4 border-0 filter-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ __('Filter Suggestion') }}</h5>
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
                            <label for="type" class="form-label">Select Type</label>
                            <select name="type" id="type" class="select2 form-select @error('type') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->type) ? 'selected' : '' }}>Select Recognizer</option>
                                @foreach ($types as $key => $label)
                                    
                                        <option value="{{ $key }}" {{ $key == request()->type ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                @endforeach
                            </select>
                            @error('type')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="mb-3 col-md-4">
                            <label for="module" class="form-label">Module</label>
                            <select name="module" id="module" class="form-select select2 @error('module') is-invalid @enderror">
                                <option value="" {{ is_null(request()->module) ? 'selected' : '' }}>All Categories</option>
                                @foreach ($modules as $key => $label)
                                    <option value="{{ $key }}" {{ $key == request()->module ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('module')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-12 text-end">
                            @if (request()->user_id || request()->type || request()->module)
                                <a href="{{ route('administration.suggestion.index') }}" class="btn btn-danger confirm-warning">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    {{ __('Reset Filters') }}
                                </a>
                            @endif
                            <button type="submit" name="filter_suggestion" value="true" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                {{ __('Filter Suggestion') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
