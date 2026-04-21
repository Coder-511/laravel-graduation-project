@extends('layouts.auth')

@section('title', 'Reset Password')

@section('left-features')
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
        <div class="feature-text">
            <strong data-en="Secure Reset" data-ar="إعادة تعيين آمنة">Secure Reset</strong>
            <span data-en="Your new password is encrypted before it ever reaches our servers."
                  data-ar="يتم تشفير كلمة مرورك الجديدة قبل أن تصل إلى خوادمنا.">
                Your new password is encrypted before it ever reaches our servers.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-arrow-repeat"></i></div>
        <div class="feature-text">
            <strong data-en="One-Time Link" data-ar="رابط لمرة واحدة">One-Time Link</strong>
            <span data-en="This reset link can only be used once for your security."
                  data-ar="لا يمكن استخدام رابط إعادة التعيين هذا إلا مرة واحدة لحمايتك.">
                This reset link can only be used once for your security.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-person-lock"></i></div>
        <div class="feature-text">
            <strong data-en="Account Protection" data-ar="حماية الحساب">Account Protection</strong>
            <span data-en="We'll notify you after your password has been changed."
                  data-ar="سنرسل لك إشعاراً بعد تغيير كلمة المرور.">
                We'll notify you after your password has been changed.
            </span>
        </div>
    </div>
@endsection

@section('form-content')

    <!-- Header -->
    <div class="card-header-custom stagger-child">
        <div class="page-icon"><i class="bi bi-lock-fill"></i></div>
        <h1 data-en="Reset password" data-ar="إعادة تعيين كلمة المرور">Reset password</h1>
        <p data-en="Choose a strong new password for your account"
           data-ar="اختر كلمة مرور جديدة قوية لحسابك">
            Choose a strong new password for your account
        </p>
    </div>

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert-auth alert-auth-error stagger-child">
            <i class="bi bi-exclamation-circle-fill"></i>
            <div>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        <!-- Hidden token -->
        <input type="hidden" name="token" value="{{ $token }}">

        <!-- Email (read-only) -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="email"
                   data-en="Email address" data-ar="البريد الإلكتروني">
                Email address
            </label>
            <div class="input-wrapper">
                <i class="bi bi-envelope input-icon"></i>
                <input
                    id="email"
                    type="email"
                    name="email" 
                    dir="ltr" 
                    value="{{ old('email', $email ?? '') }}"
                    autocomplete="email"
                    readonly
                    style="background:#f0f2f8; cursor:not-allowed;"
                    class="form-control-custom @error('email') is-invalid @enderror"
                    required
                >
            </div>
            @error('email')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="password"
                   data-en="New password" data-ar="كلمة المرور الجديدة">
                New password
            </label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="new-password"
                    autofocus
                    data-placeholder-en="Min. 8 characters"
                    data-placeholder-ar="8 أحرف على الأقل"
                    placeholder="Min. 8 characters"
                    class="form-control-custom @error('password') is-invalid @enderror"
                    required
                >
                <button type="button" class="toggle-pw" id="togglePw1"
                        onclick="togglePassword('togglePw1','password')"
                        tabindex="-1" aria-label="Toggle password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Password Strength Bar -->
        <div class="mb-3 stagger-child" id="strengthWrap" style="display:none;">
            <div style="height:4px; background:#e2e6f0; border-radius:4px; overflow:hidden;">
                <div id="strengthBar" style="height:100%; width:0%; border-radius:4px; transition:width .4s, background .4s;"></div>
            </div>
            <div id="strengthLabel" style="font-size:.75rem; margin-top:.3rem; color:#8a95b0;"></div>
        </div>

        <!-- Confirm New Password -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="password_confirmation"
                   data-en="Confirm new password" data-ar="تأكيد كلمة المرور الجديدة">
                Confirm new password
            </label>
            <div class="input-wrapper">
                <i class="bi bi-lock-fill input-icon"></i>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password"
                    data-placeholder-en="Repeat your new password"
                    data-placeholder-ar="أعد إدخال كلمة المرور الجديدة"
                    placeholder="Repeat your new password"
                    class="form-control-custom @error('password_confirmation') is-invalid @enderror"
                    required
                >
                <button type="button" class="toggle-pw" id="togglePw2"
                        onclick="togglePassword('togglePw2','password_confirmation')"
                        tabindex="-1" aria-label="Toggle confirm password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit -->
        <div class="stagger-child">
            <button type="submit" class="btn-auth"
                    data-en="Reset Password" data-ar="إعادة تعيين كلمة المرور">
                <i class="bi bi-check2-circle me-2"></i> Reset Password
            </button>
        </div>

    </form>

    <!-- Back to login -->
    <div class="text-center mt-4 stagger-child">
        <a href="{{ route('login') }}" class="auth-link d-inline-flex align-items-center gap-2"
           data-en="Back to Sign In" data-ar="العودة لتسجيل الدخول">
            <i class="bi bi-arrow-left"></i> Back to Sign In
        </a>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/resetpassword.js') }}"></script>
@endpush