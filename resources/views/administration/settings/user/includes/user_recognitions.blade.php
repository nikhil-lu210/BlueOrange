@extends('administration.settings.user.show')

@section('css_links_user_show')
    {{-- Lightbox CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css" integrity="sha512-ZKX+BvQihRJPA8CROKBhDNvoc2aDMOdAlcm7TUQY+35XYtrd3yh95QOOhsPDQY9QnKE0Wqag9y38OIgEvb88cA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('profile_content')

<!-- User Recognitions -->
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card card-border-shadow-primary mb-4 border-0">
            <div class="card-body row">
                <div class="d-flex justify-content-between flex-wrap gap-3 me-3">
                    @foreach(['Behavior', 'Appreciation', 'Leadership', 'Loyalty', 'Dedication'] as $cat)
                        @php
                            $rating = $averageRatings->get($cat, 0);

                            if ($rating >= 4.5) {
                                $color = 'success';
                            } elseif ($rating >= 3.5) {
                                $color = 'primary';
                            } elseif ($rating >= 2.5) {
                                $color = 'warning';
                            } elseif ($rating > 0) {
                                $color = 'danger';
                            } else {
                                $color = 'dark';
                            }
                        @endphp

                        <div class="d-flex align-items-center gap-3">
                            <span class="bg-label-{{ $color }} p-2 rounded">
                                <i class="ti ti-star ti-xl"></i>
                            </span>
                            <div class="content-right">
                                <h5 class="text-{{ $color }} mb-0">{{ number_format($rating, 2) }}</h5>
                                <small class="mb-0 text-muted">{{ __($cat) }}</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <h5 class="card-header">Recognitions</h5>
            <div class="card-body pb-0">
                <ul class="timeline pt-3">
                    @foreach($recognitions as $date => $recognitionGroup)
                        <li class="timeline-item pb-4 timeline-item-primary border-left-dashed">
                            <span class="timeline-indicator-advanced timeline-indicator-primary">
                                <i class="ti ti-calendar-event rounded-circle scaleX-n1-rtl"></i>
                            </span>
                            <div class="timeline-event">
                                <div class="timeline-header border-bottom mb-3 pb-2">
                                    <h6 class="mb-0">{{ show_date($date) }}</h6>
                                </div>

                                @php
                                    // Group by recognizer to avoid multiple cards per recognizer
                                    $groupedByRecognizer = $recognitionGroup->groupBy('recognizer_id');
                                @endphp

                                @foreach($groupedByRecognizer as $recognizerId => $groupedRecognitions)
                                    <div class="card mb-3 shadow-none border">
                                        <div class="card-body pb-1">
                                            @foreach($groupedRecognitions as $recognition)
                                                <table class="table table-borderless table-sm">
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left" style="width: 50%;">
                                                                {{ __($recognition->category) }}
                                                                @if($recognition->comment)
                                                                    <br>
                                                                    <small class="text-muted">"{{ $recognition->comment }}"</small>
                                                                @endif
                                                            </td>
                                                            <td class="text-center" style="width: 10%;">
                                                                <i class="ti ti-arrow-right scaleX-n1-rtl"></i>
                                                            </td>
                                                            <td class="text-right" style="width: 40%;">
                                                                {{ $recognition->points }}/5
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            @endforeach

                                            <div class="d-flex align-items-center mt-3 border-top pt-3 pb-2">
                                                {!! show_user_name_and_avatar($groupedRecognitions->first()->recognizer, name: null, role: null) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
<!--/ User Recognitions -->


{{-- Add Users File Modal --}}
@include('administration.settings.user.includes.modals.add_file')

@endsection


@section('script_links_user_show')
    {{-- Lightbox JS --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js" integrity="sha512-Ixzuzfxv1EqafeQlTCufWfaC6ful6WFqIz4G+dWvK0beHw0NVJwvCKSgafpy5gwNqKmgUfIBraVwkKI+Cz0SEQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@endsection
