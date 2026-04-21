@extends('layouts.auth')

@section('title', 'Sign In')

@section('left-features')
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
        <div class="feature-text">
            <strong data-en="Enterprise Security" data-ar="أمان المؤسسات">Enterprise Security</strong>
            <span data-en="256-bit encryption keeps your data safe at every step."
                  data-ar="تشفير 256 بت يحافظ على بياناتك آمنة في كل خطوة.">
                256-bit encryption keeps your data safe at every step.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
        <div class="feature-text">
            <strong data-en="Blazing Fast" data-ar="سرعة فائقة">Blazing Fast</strong>
            <span data-en="Sub-second responses powered by optimised infrastructure."
                  data-ar="استجابات فورية مدعومة ببنية تحتية متطورة.">
                Sub-second responses powered by optimised infrastructure.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-people"></i></div>
        <div class="feature-text">
            <strong data-en="Team Collaboration" data-ar="تعاون الفريق">Team Collaboration</strong>
            <span data-en="Invite members, assign roles, and work together seamlessly."
                  data-ar="ادعُ الأعضاء، خصّص الأدوار، واعمل معاً بسلاسة.">
                Invite members, assign roles, and work together seamlessly.
            </span>
        </div>
    </div>
@endsection

@section('form-content')

    <!-- Header -->
    <div class="card-header-custom stagger-child">
        <div class="page-icon"><i class="bi bi-person-check"></i></div>
        <h1 data-en="Welcome back" data-ar="مرحباً بعودتك">Welcome back</h1>
        <p data-en="Sign in to continue to your account"
           data-ar="سجّل دخولك للمتابعة إلى حسابك">
            Sign in to continue to your account
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

    <form method="POST" action="{{ route('login.post') }}" novalidate>
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
                    autofocus 
                    value="{{ old('email') }}"
                    autocomplete="email"
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

        <!-- Password -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="password"
                   data-en="Password" data-ar="كلمة المرور">
                Password
            </label>
            <div class="input-wrapper">
                <i class="bi bi-lock input-icon"></i>
                <input
                    id="password"
                    type="password"
                    name="password"
                    autocomplete="current-password"
                    data-placeholder-en="••••••••"
                    data-placeholder-ar="••••••••"
                    placeholder="••••••••"
                    class="form-control-custom @error('password') is-invalid @enderror"
                    required
                >
                <button type="button" class="toggle-pw" id="togglePw"
                        onclick="togglePassword('togglePw','password')" 
                        aria-label="Toggle password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Remember + Forgot -->
        <div class="d-flex align-items-center justify-content-between mb-4 stagger-child">
            <label class="custom-check">
                <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                <span data-en="Remember me" data-ar="تذكّرني">Remember me</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link"
                   data-en="Forgot password?" data-ar="نسيت كلمة المرور؟">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Submit -->
        <div class="stagger-child">
            <button type="submit" class="btn-auth"
                    data-en="Sign In" data-ar="تسجيل الدخول">
                <i class="bi bi-arrow-right-circle me-2"></i> Sign In
            </button>
        </div>

    </form>

    <!-- Register link -->
    @if (Route::has('register'))
        <div class="card-footer-note stagger-child">
            <span data-en="Don't have an account?"
                  data-ar="ليس لديك حساب؟">
                Don't have an account?
            </span>
            <a href="{{ route('register') }}" class="auth-link-gold ms-1"
               data-en="Create one →" data-ar="أنشئ حساباً →">
                Create one →
            </a>
        </div>
    @endif

@endsection
