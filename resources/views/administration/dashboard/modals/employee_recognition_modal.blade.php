<!-- Employee Recognition Modal -->
<div class="modal fade" data-bs-backdrop="static" id="recognitionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title text-capitalize mb-2">{{ ___('Submit Recognition') }}</h3>
                    <p class="text-muted text-capitalize">{{ ___('Please Provide Recognition to your team\'s employee.') }}</p>
                </div>
                <!-- Employee Recognition form -->
                <form method="post" action="{{ route('administration.recognition.store') }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="mb-3 col-md-12">
                        <label for="user_id" class="form-label">{{ ___('Employee') }} <strong class="text-danger">*</strong></label>
                        <select name="user_id" id="user_id" class="form-select select2 w-100 @error('user_id') is-invalid @enderror" data-style="btn-default" required>
                            <option value="" disabled selected>{{ ___('Select Employee') }}</option>
                            @foreach (auth()->user()->tl_employees as $user)
                                <option value="{{ $user->id }}">{{ $user->alias_name }}</option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="category" class="form-label">{{ ___('Recognition Category') }} <strong class="text-danger">*</strong></label>
                        <select name="category" id="category" class="form-select select2 w-100 @error('category') is-invalid @enderror" data-style="btn-default" required>
                            <option value="" disabled selected>{{ ___('Select Recognition Category') }}</option>
                            @foreach(config('recognition.categories') as $category)
                                <option value="{{ $category }}">{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="total_mark" class="form-label">
                            {{ ___('Total Marks') }}
                            <strong class="text-danger">*</strong>
                            <sup>{{ ___('(Between ' . config('recognition.marks.min') . ' to ' . config('recognition.marks.max') . ')') }}</sup>
                        </label>
                        <input type="number" id="total_mark" name="total_mark" value="{{ old('total_mark') }}" placeholder="Ex: 20" min="{{ config('recognition.marks.min') }}" max="{{ config('recognition.marks.max') }}" step="{{ config('recognition.marks.step') }}" class="form-control @error('total_mark') is-invalid @enderror" required/>
                        @error('total_mark')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="mb-3 col-md-12">
                        <label for="comment" class="form-label">{{ ___('Comment') }}<strong class="text-danger">*</strong></label>
                        <textarea class="form-control" name="comment" rows="2" placeholder="Ex: He is a good employee.">{{ old('comment') }}</textarea>
                        @error('comment')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>

                    <div class="col-12 text-center">
                        <button type="reset" class="btn btn-label-dark" data-bs-dismiss="modal" aria-label="Close">{{ ___('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">{{ ___('Submit Recognition') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
