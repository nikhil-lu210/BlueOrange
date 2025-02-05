<div class="col app-chat-sidebar-left app-sidebar overflow-hidden" id="app-chat-sidebar-left"  data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="chat-sidebar-left-user sidebar-header d-flex flex-column justify-content-center align-items-center flex-wrap px-4 pt-5">
        <h5 class="mt-2 mb-0">{{ __('Create Group') }}</h5>
        <span>{{ __('Create New Chatting Group') }}</span>
        <i class="ti ti-x ti-sm cursor-pointer close-sidebar" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left"></i>
    </div>
    <div class="sidebar-body px-4 pb-4">
        <form action="{{ route('administration.chatting.group.store') }}" method="post" autocomplete="off">
            @csrf
            <div class="row">
                <div class="my-5 mb-3 col-md-12">
                    <label for="name" class="form-label">{{ __('Group Name') }} <strong class="text-danger">*</strong></label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="{{ __('Group Name') }}" class="form-control @error('name') is-invalid @enderror" required/>
                    @error('name')
                        <b class="text-danger"><i class="ti ti-info-circle mr-1"></i>{{ $message }}</b>
                    @enderror
                </div>
                <div class="mb-3 col-md-12">
                    <label for="users" class="form-label">Select Users <strong class="text-danger">*</strong></label>
                    <select name="users[]" id="users" class="select2 form-select @error('users') is-invalid @enderror" data-allow-clear="true" multiple required>
                        <option value="selectAllValues">Select All</option>
                        @foreach ($roles as $role)
                            <optgroup label="{{ $role->name }}">
                                @foreach ($role->users as $user)
                                    <option value="{{ $user->id }}" {{ in_array($user->id, old('users', [])) ? 'selected' : '' }}>
                                        {{ show_content(get_employee_name($user), 22) }}
                                    </option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                    @error('users')
                        <b class="text-danger"><i class="feather icon-info mr-1"></i>{{ $message }}</b>
                    @enderror
                </div>
                <div class="mt-3 col-md-12">
                    <button class="btn btn-primary btn-block w-100" data-bs-toggle="sidebar" data-overlay data-target="#app-chat-sidebar-left">
                        Create Chatting Group
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>