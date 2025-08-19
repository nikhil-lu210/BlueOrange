@canany (['User Everything', 'User Create', 'User Delete'])
    @if (isset($upcomingBirthdays) && $upcomingBirthdays->count())
        <div class="row">
            <div class="col-md-12">
                <div class="card card-action card-border-shadow-primary mb-4 border-0">
                    <div class="card-header collapsed">
                        <h5 class="card-action-title mb-0">{{ __('Upcoming Birthdays') }}</h5>
                        <div class="card-action-element">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible">
                                        <i class="tf-icons ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="collapse card-body">
                        {{-- Birthdays in next 15 days --}}
                        @if(isset($upcomingBirthdays) && $upcomingBirthdays->count())
                            <div class="row g-3">
                                @foreach ($upcomingBirthdays as $birthdayUser)
                                    <div class="col-md-2 col-sm-4 col-6">
                                        <div class="card h-100 shadow-sm border-0 bg-label-primary pt-2">
                                            <div class="card-body text-center p-2 d-flex flex-column align-items-center justify-content-between">
                                                <div class="mb-2">
                                                    @if ($birthdayUser->hasMedia('avatar'))
                                                        <img src="{{ $birthdayUser->getFirstMediaUrl('avatar', 'profile_color') }}" alt="{{ $birthdayUser->name }} Avatar" class="rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
                                                    @else
                                                        <span class="avatar-initial rounded-circle bg-label-hover-dark text-bold d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 24px;">{{ profile_name_pic($birthdayUser) }}</span>
                                                    @endif
                                                </div>
                                                <div class="w-100">
                                                    <div class="text-truncate fw-bold">{{ $birthdayUser->alias_name }}</div>
                                                    @if(optional($birthdayUser->employee)->alias_name)
                                                        <small class="text-muted d-block text-truncate">{{ $birthdayUser->name }}</small>
                                                    @endif
                                                    <div class="mt-1">
                                                        @php
                                                            $days = (int) ($birthdayUser->days_until_birthday ?? 0);
                                                        @endphp
                                                        @if($days === 0)
                                                            <span class="badge bg-success">Today</span>
                                                        @elseif($days === 1)
                                                            <span class="badge bg-warning">In 1 day</span>
                                                        @else
                                                            <span class="badge bg-primary">In {{ $days }} days</span>
                                                        @endif
                                                    </div>
                                                    <small class="text-muted d-block mt-1">{{ show_date_month_day($birthdayUser->next_birthday_date) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <small class="text-muted">No upcoming birthdays in the next 15 days.</small>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endcanany
