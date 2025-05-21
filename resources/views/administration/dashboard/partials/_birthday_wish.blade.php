@if (is_today_birthday(optional(auth()->user()->employee)->birth_date))
    <div class="row mb-4 birthday-wish-container justify-content-center">
        <div class="col-md-6">
            <div class="birthday-card">
                @if (auth()->user()->hasMedia('avatar'))
                    <img src="{{ auth()->user()->getFirstMediaUrl('avatar', 'profile_view') }}" alt="{{ auth()->user()->name }} Avatar" class="user-photo">
                @else
                    <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold user-photo">
                        {{ profile_name_pic(auth()->user()) }}
                    </span>
                @endif
                <img src="{{ asset('assets/img/custom_images/happy_birthday_wish.jpg') }}" alt="Happy Birthday" class="birthday-wish">
            </div>
        </div>
    </div>
@endif
