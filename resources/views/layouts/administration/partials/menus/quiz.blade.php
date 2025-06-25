<!-- Quiz Management -->
@canany(['Quiz Everything', 'Quiz Create', 'Quiz Read', 'Quiz Update', 'Quiz Delete'])
    <li class="menu-item {{ request()->is('quiz*') ? 'active open' : '' }}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
            <i class="menu-icon tf-icons ti ti-brain"></i>
            <div data-i18n="Quiz">{{ __('Quiz') }}</div>
        </a>
        <ul class="menu-sub">
            @canany (['Quiz Everything', 'Quiz Read'])
                <li class="menu-item {{ request()->is('quiz/question/all*') ? 'active' : '' }}">
                    <a href="{{ route('administration.quiz.question.index') }}" class="menu-link">{{ __('All Questions') }}</a>
                </li>
            @endcanany
            @can ('Quiz Create')
                <li class="menu-item {{ request()->is('quiz/question/create*') ? 'active' : '' }}">
                    <a href="{{ route('administration.quiz.question.create') }}" class="menu-link">{{ __('Create Question') }}</a>
                </li>
            @endcan
            @canany (['Quiz Everything', 'Quiz Create', 'Quiz Read', 'Quiz Update', 'Quiz Delete'])
                <li class="menu-item {{ request()->is('quiz/test*') ? 'active open' : '' }}">
                    <a href="javascript:void(0);" class="menu-link menu-toggle">
                        <div data-i18n="Quiz Tests">{{ __('Quiz Tests') }}</div>
                    </a>
                    <ul class="menu-sub">
                        @canany (['Quiz Everything', 'Quiz Read'])
                            <li class="menu-item {{ request()->is('quiz/test/all*') ? 'active' : '' }}">
                                <a href="{{ route('administration.quiz.test.index') }}" class="menu-link">
                                    <div data-i18n="All Tests">{{ __('All Tests') }}</div>
                                </a>
                            </li>
                        @endcanany
                        @canany (['Quiz Everything', 'Quiz Create'])
                            <li class="menu-item {{ request()->is('quiz/test/create*') ? 'active' : '' }}">
                                <a href="{{ route('administration.quiz.test.create') }}" class="menu-link">
                                    <div data-i18n="Assign Test">{{ __('Assign Test') }}</div>
                                </a>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany
        </ul>
    </li>
@endcanany
