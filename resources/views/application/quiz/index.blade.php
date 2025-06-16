@extends('layouts.public.app')

@section('page_title', __('QUIZ REGISTRATION'))

@section('custom_css')
    <style>
        .quiz-info-card {
            background: #685dd8;
            color: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .quiz-info-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .quiz-info-item:last-child {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('content')
    <h3 class="mb-3 text-left text-center"><b>Quiz Registration</b> for {{ config('app.name') }}</h3>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    <form id="formQuizRegistration" class="mb-3" method="POST" action="#" autocomplete="off">
        @csrf

        <div class="mb-3">
            <div class="quiz-info-card">
                <h4 class="mb-3 text-white text-center">
                    <b>Quiz Information</b>
                </h4>
                <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                <div class="quiz-info-item">
                    <span><i class="ti ti-list-numbers me-1"></i>Total Questions:</span>
                    <strong>10</strong>
                </div>
                <div class="quiz-info-item">
                    <span><i class="ti ti-clock me-1"></i>Time Limit:</span>
                    <strong>10 minutes</strong>
                </div>
                <div class="quiz-info-item">
                    <span><i class="ti ti-target me-1"></i>Passing Score:</span>
                    <strong>6 out of 10</strong>
                </div>
                <div class="quiz-info-item">
                    <span><i class="ti ti-shuffle me-1"></i>Question Selection:</span>
                    <strong>Random</strong>
                </div>
                <hr class="my-3" style="border-color: rgba(255,255,255,0.3);">
                <div class="text-center">
                    <small><i class="ti ti-alert-triangle me-1"></i><strong>Important:</strong> You can only take one quiz. Once started, you must complete it.</small>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="candidate_name" class="form-label">Full Name <sup class="text-danger">*</sup></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-user"></i></span>
                <input type="text" id="candidate_name" name="candidate_name" class="form-control @error('candidate_name') is-invalid @enderror" value="{{ old('candidate_name') }}" placeholder="Enter your full name" autocomplete="off" required autofocus />
            </div>
            @error('candidate_name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="candidate_email" class="form-label">Email Address <sup class="text-danger">*</sup></label>
            <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="ti ti-mail"></i></span>
                <input type="email" id="candidate_email" name="candidate_email" class="form-control @error('candidate_email') is-invalid @enderror" value="{{ old('candidate_email') }}" placeholder="Enter your email address" autocomplete="off" required />
            </div>
            @error('candidate_email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary text-uppercase text-bold d-grid w-100">
            <span class="fw-bold">
                {{ __('Start Quiz') }}
                <i class="ti ti-brain ms-1"></i>
            </span>
        </button>
    </form>
@endsection
