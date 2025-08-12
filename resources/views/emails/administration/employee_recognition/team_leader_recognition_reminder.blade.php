@component('mail::message')
# {{ __('Monthly Recognitions Due') }}

{{ __('You have pending recognitions for :month.', ['month' => $data['month_label']]) }}

@if(!empty($data['missing']))
**{{ __('Pending team members') }}:**
@foreach($data['missing'] as $name)
- {{ $name }}
@endforeach
@endif

@component('mail::button', ['url' => route('administration.employee_recognition.index', ['month' => now()->startOfMonth()->format('Y-m-d')])])
{{ __('Recognize Now') }}
@endcomponent

{{ __('Thank you,') }}<br>
{{ config('app.name') }}
@endcomponent
