@extends('administration.settings.user.show')

@section('css_links_user_show')
    <style>
        .marks {
            width: 60px;
            height: 60px;
            display: grid;
            grid-template-areas:
                "got divider"
                "divider total";
            place-items: center;
            position: relative;

            background: #f5f4ff;
            border: 1px solid #b7b4f2;
            border-radius: 6px;

            font-family: system-ui, sans-serif;
        }

        /* top-left */
        .marks .mark-got {
            grid-area: got;
            font-size: 18px;
            font-weight: 700;
            color: #2c2c54;
            justify-self: start;
            align-self: start;
            margin: -10px;
        }

        /* bottom-right */
        .marks .total-mark {
            grid-area: total;
            font-size: 16px;
            font-weight: 500;
            color: #555;
            justify-self: end;
            align-self: end;
            margin: 6px;
        }

        /* diagonal divider */
        .marks::before {
            content: "";
            position: absolute;
            width: 120%;
            height: 1px;
            background: #b7b4f2;
            transform: rotate(-45deg);
        }
    </style>
@endsection

@section('profile_content')

<!-- User recognitions -->
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header header-elements">
                <h5 class="mb-0">{{ __('All Recognitions') }}</h5>
            </div>
            <div class="card-body">
                @if ($user->received_recognitions->count() > 0)
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="max-width: 55px;">Marks</th>
                                        <th>Recognition For</th>
                                        <th>Recognizer</th>
                                        <th>Recognized At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalMarks = config('recognition.marks.max');
                                    @endphp
                                    @foreach ($user->received_recognitions as $sl => $recognition)
                                        <tr>
                                            <td class="text-center" style="max-width: 55px;">
                                                <div class="marks">
                                                    <span class="mark-got">{{ $recognition->total_mark }}</span>
                                                    <span class="total-mark">{{ $totalMarks }}</span>
                                                </div>
                                            </td>

                                            <td>
                                                <b class="text-dark">{{ $recognition->category }}</b>
                                                <br>
                                                <small class="text-muted">{!! $recognition->comment !!}</small>
                                            </td>
                                            <td>{!! show_user_name_and_avatar($recognition->recognizer, name: null) !!}</td>
                                            <td>{{ show_date($recognition->created_at) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--/ User recognitions -->

@endsection


@section('script_links_user_show')
    {{-- Lightbox JS --}}
@endsection
