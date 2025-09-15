<div class="row justify-content-center">
    <div class="col-md-10">
        <form action="{{ route('administration.recognition.leaderboard') }}" method="GET" autocomplete="off">
            <div class="card card-border-shadow-primary mb-4 border-0" style="z-index: 999;">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label for="category" class="form-label">Filter by Category</label>
                            <select name="category" id="category" class="form-select select2 @error('category') is-invalid @enderror">
                                <option value="" {{ is_null($category) ? 'selected' : '' }}>All Categories</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat }}" {{ $cat == $category ? 'selected' : '' }}>
                                        {{ $cat }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category')
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

                        <div class="mb-3 col-md-2">
                            <label for="limit" class="form-label">Number of Results</label>
                            <select name="limit" id="limit" class="form-select select2 @error('limit') is-invalid @enderror">
                                <option value="10" {{ request('limit', 10) == 10 ? 'selected' : '' }}>Top 10</option>
                                <option value="20" {{ request('limit', 10) == 20 ? 'selected' : '' }}>Top 20</option>
                                <option value="50" {{ request('limit', 10) == 50 ? 'selected' : '' }}>Top 50</option>
                            </select>
                            @error('limit')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>

                        <div class="col-md-12 text-end">
                            @if ($category || request()->date_from || request()->date_to || request()->limit)
                                <a href="{{ route('administration.recognition.leaderboard') }}" class="btn btn-danger me-2">
                                    <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                    {{ __('Reset Filters') }}
                                </a>
                            @endif
                            <button type="submit" class="btn btn-primary">
                                <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                                {{ __('Filter Leaderboard') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>