@extends('layouts.auth')

@section('title', 'Forgot Password')

@section('left-features')
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-envelope-paper"></i></div>
        <div class="feature-text">
            <strong data-en="Check Your Inbox" data-ar="تحقق من بريدك">Check Your Inbox</strong>
            <span data-en="We'll send a secure reset link straight to your email."
                  data-ar="سنرسل رابط إعادة تعيين آمناً مباشرةً إلى بريدك الإلكتروني.">
                We'll send a secure reset link straight to your email.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
        <div class="feature-text">
            <strong data-en="Link Valid 60 Minutes" data-ar="الرابط صالح لمدة 60 دقيقة">Link Valid 60 Minutes</strong>
            <span data-en="Reset links expire after one hour for your protection."
                  data-ar="تنتهي صلاحية روابط إعادة التعيين بعد ساعة لحمايتك.">
                Reset links expire after one hour for your protection.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-headset"></i></div>
        <div class="feature-text">
            <strong data-en="Need More Help?" data-ar="تحتاج مزيداً من المساعدة؟">Need More Help?</strong>
            <span data-en="Our support team is available 24/7 to assist you."
                  data-ar="فريق الدعم لدينا متاح على مدار الساعة لمساعدتك.">
                Our support team is available 24/7 to assist you.
            </span>
        </div>
    </div>
@endsection

@section('form-content')

    <!-- Header -->
    <div class="card-header-custom stagger-child">
        <div class="page-icon"><i class="bi bi-key"></i></div>
        <h1 data-en="Forgot password?" data-ar="نسيت كلمة المرور؟">Forgot password?</h1>
        <p data-en="No worries — enter your email and we'll send you a reset link right away."
           data-ar="لا تقلق — أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة التعيين فوراً.">
            No worries — enter your email and we'll send you a reset link right away.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="alert-auth alert-auth-success stagger-child">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('status') }}</span>
        </div>
    @endif

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

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <!-- Email -->
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
                    value="{{ old('email') }}" 
                    autocomplete="email"
                    autofocus
                    data-placeholder-en="you@example.com"
                    data-placeholder-ar="you@example.com"
                    placeholder="you@example.com"
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

        <!-- Help text -->
        <p class="stagger-child"
           style="font-size:.82rem; color:#8a95b0; margin-bottom:1.5rem; line-height:1.55;"
           data-en="Make sure to check your spam folder if the email doesn't arrive within a minute."
           data-ar="تأكد من التحقق من مجلد البريد غير المرغوب إذا لم يصل الإيميل خلال دقيقة.">
            <i class="bi bi-info-circle me-1" style="color:var(--gold);"></i>
            Make sure to check your spam folder if the email doesn't arrive within a minute.
        </p>

        <!-- Submit -->
        <div class="stagger-child">
            <button type="submit" class="btn-auth"
                    data-en="Send Reset Link" data-ar="إرسال رابط إعادة التعيين">
                <i class="bi bi-send me-2"></i> Send Reset Link
            </button>
        </div>

    </form>

    <div class="text-divider stagger-child">
        <span data-en="or" data-ar="أو">or</span>
    </div>

    <!-- Back to login -->
    <div class="text-center stagger-child">
        <a href="{{ route('login') }}" class="auth-link d-inline-flex align-items-center gap-2"
           data-en="Back to Sign In" data-ar="العودة لتسجيل الدخول">
            <i class="bi bi-arrow-left"></i> Back to Sign In
        </a>
    </div>

@endsection