<div class="row">
    <div class="col-md-12">
        <form action="{{ route('administration.inventory.index') }}" method="get">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-3">
                            <label for="creator_id" class="form-label">Select Creator</label>
                            <select name="creator_id" id="creator_id" class="select2 form-select @error('creator_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->creator_id) ? 'selected' : '' }}>Select Creator</option>
                                @foreach ($roles as $role)
                                    <optgroup label="{{ $role->name }}">
                                        @foreach ($role->users as $creator)
                                            <option value="{{ $creator->id }}" {{ $creator->id == request()->creator_id ? 'selected' : '' }}>
                                                {{ get_employee_name($creator) }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('creator_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="category_id" class="form-label">Select Category</label>
                            <select name="category_id" id="category_id" class="select2 form-select @error('category_id') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->category_id) ? 'selected' : '' }}>Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $category->id == request()->category_id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="usage_for" class="form-label">Select Usage Purpose</label>
                            <select name="usage_for" id="usage_for" class="select2 form-select @error('usage_for') is-invalid @enderror" data-allow-clear="true">
                                <option value="" {{ is_null(request()->usage_for) ? 'selected' : '' }}>Select Purpose</option>
                                @foreach ($purposes as $purpose)
                                    <option value="{{ $purpose }}" {{ $purpose == request()->usage_for ? 'selected' : '' }}>
                                        {{ $purpose }}
                                    </option>
                                @endforeach
                            </select>
                            @error('usage_for')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-3">
                            <label for="status" class="form-label">Select Status</label>
                            <select name="status" id="status" class="form-select bootstrap-select w-100 @error('status') is-invalid @enderror" data-style="btn-default">
                                <option value="" {{ is_null(request()->status) ? 'selected' : '' }}>Select Status</option>
                                <option value="Available" {{ request()->status == 'Available' ? 'selected' : '' }}>Available</option>
                                <option value="In Use" {{ request()->status == 'In Use' ? 'selected' : '' }}>In Use</option>
                                <option value="Out of Service" {{ request()->status == 'Out of Service' ? 'selected' : '' }}>Out of Service</option>
                                <option value="Damaged" {{ request()->status == 'Damaged' ? 'selected' : '' }}>Damaged</option>
                            </select>
                            @error('status')
                                <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-12 text-end">
                        @if (request()->category_id || request()->usage_for || request()->status || request()->creator_id)
                            <a href="{{ route('administration.inventory.index') }}" class="btn btn-danger confirm-warning">
                                <span class="tf-icon ti ti-refresh ti-xs me-1"></span>
                                Reset Filters
                            </a>
                        @endif
                        <button type="submit" class="btn btn-primary">
                            <span class="tf-icon ti ti-filter ti-xs me-1"></span>
                            Filter Inventories
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
