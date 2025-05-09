<!-- Category Create Modal -->
<div class="modal fade" data-bs-backdrop="static" id="assignNewCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content p-3 p-md-5">
            <button type="button" class="btn-close btn-pinned" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h3 class="role-title mb-2">Assign Category</h3>
                    <p class="text-muted">Assign A New Category</p>
                </div>
                <!-- Category Create form -->
                <form method="post" action="{{ route('administration.accounts.income_expense.category.store') }}" class="row g-3" autocomplete="off">
                    @csrf
                    <div class="col-md-8">
                        <label class="form-label">Category Name <strong class="text-danger">*</strong></label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Ex: Electronics" tabindex="-1" required/>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="is_active" class="form-label">{{ __('Select Status') }}</label>
                        <select name="is_active" id="is_active" class="form-select bootstrap-select w-100 @error('is_active') is-invalid @enderror"  data-style="btn-default">
                            <option value="">{{ __('Select Type') }}</option>
                            <option value="{{ TRUE }}" selected>{{ __('Active') }}</option>
                            <option value="{{ FALSE }}">{{ __('Inactive') }}</option>
                        </select>
                        @error('is_active')
                            <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                        @enderror
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" placeholder="Ex: Electronical Products.">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
                        <button type="submit" class="btn btn-primary me-sm-3 me-1">
                            <i class="ti ti-check"></i>
                            Create Category
                        </button>
                    </div>
                </form>
                <!--/ Category Create form -->
            </div>
        </div>
    </div>
</div>
<!--/ Category Create Modal -->