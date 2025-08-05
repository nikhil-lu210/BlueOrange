@if(isset($fullMarkCategories) && $fullMarkCategories->count())
    <div class="row justify-content-center mb-4">
        <div class="col-xl-5 col-lg-6 col-12">
            <div class="card">
                <div class="d-flex justify-content-between">
                    <div class="content">
                        <div class="card-body text-nowrap pt-5 pb-4">
                            <h4 class="card-title mb-2">
                                Congratulations {{ auth()->user()->employee->alias_name }}!
                                <span style="font-size: 50px; position: absolute; top: -40px; left: -10px;">🎉</span>
                            </h4>
                            <p class="mb-1">You Got Full Marks On:</p>
                            <ul class="d-block list-unstyled">
                                @foreach($fullMarkCategories as $cat)
                                    <li>
                                        <span class="text-success">{{ __($cat) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="image position-relative">
                        <div class="card-body pb-0 px-0 px-md-4">
                            @php
                                $imagePath = auth()->user()->employee->gender == 'Male' ? 'assets/img/illustrations/card-advance-sale.png' : 'assets/img/illustrations/wizard-create-deal-confirm.png';
                            @endphp
                            <img class="position-absolute" style="bottom: 0; right: -20px;" src="{{ asset($imagePath) }}" height="140" alt="view sales">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif