@php
    $correctOption = $question->correct_option;

    function getOptionIcon($optionKey, $correctOption)
    {
        return $optionKey === $correctOption ? 'circle-check text-success' : 'xbox-x text-danger';
    }

    function getOptionColor($optionKey, $correctOption)
    {
        return $optionKey === $correctOption ? 'text-success' : 'text-danger';
    }
@endphp

<div class="card mb-4">
    <div class="card-body">
        <small class="card-text text-uppercase">Question Details</small>
        <dl class="row mt-3 mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-help-hexagon"></i>
                <span class="fw-medium mx-2 text-heading">Question:</span>
            </dt>
            <dd class="col-sm-8 text-bold">
                {!! $question->question !!}
            </dd>
        </dl>
        @foreach (['A', 'B', 'C', 'D'] as $option)
            @php
                $optionValue = 'option_' . strtolower($option);
                $icon = getOptionIcon($option, $correctOption);
                $color = getOptionColor($option, $correctOption);
            @endphp
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-{{ $icon }}"></i>
                    <span class="fw-medium mx-2 text-bold {{ $color }}">Option {{ $option }}:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! $question->$optionValue !!}
                </dd>
            </dl>
        @endforeach

        <dl class="row mb-1">
            <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                <i class="ti ti-clock-check"></i>
                <span class="fw-medium mx-2 text-heading">Created At:</span>
            </dt>
            <dd class="col-sm-8">
                <span class="text-dark">{{ show_date_time($question->created_at) }}</span>
            </dd>
        </dl>
        @isset ($question->creator)
            <dl class="row mb-1">
                <dt class="col-sm-4 mb-2 fw-medium text-nowrap">
                    <i class="ti ti-user-cog"></i>
                    <span class="fw-medium mx-2 text-heading">Created By:</span>
                </dt>
                <dd class="col-sm-8">
                    {!! show_user_name_and_avatar($question->creator, name: null) !!}
                </dd>
            </dl>
        @endisset
    </div>
</div>
