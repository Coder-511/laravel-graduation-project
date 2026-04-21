@extends('layouts.auth')

@section('title', 'Create Account')

@section('left-features')
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-rocket-takeoff"></i></div>
        <div class="feature-text">
            <strong data-en="Get Started Free" data-ar="ابدأ مجاناً">Get Started Free</strong>
            <span data-en="No credit card required. Your first 14 days are on us."
                  data-ar="لا حاجة لبطاقة ائتمان. أول 14 يوماً على حسابنا.">
                No credit card required. Your first 14 days are on us.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-bar-chart-line"></i></div>
        <div class="feature-text">
            <strong data-en="Powerful Analytics" data-ar="تحليلات قوية">Powerful Analytics</strong>
            <span data-en="Real-time dashboards and reports at your fingertips."
                  data-ar="لوحات تحكم وتقارير فورية في متناول يدك.">
                Real-time dashboards and reports at your fingertips.
            </span>
        </div>
    </div>
    <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-patch-check"></i></div>
        <div class="feature-text">
            <strong data-en="Trusted by Thousands" data-ar="موثوق من الآلاف">Trusted by Thousands</strong>
            <span data-en="Join 50 000+ teams who rely on our platform daily."
                  data-ar="انضم إلى أكثر من 50,000 فريق يعتمد منصتنا يومياً.">
                Join 50 000+ teams who rely on our platform daily.
            </span>
        </div>
    </div>
@endsection

@section('form-content')

    <!-- Header -->
    <div class="card-header-custom stagger-child">
        <div class="page-icon"><i class="bi bi-person-plus"></i></div>
        <h1 data-en="Create account" data-ar="إنشاء حساب">Create account</h1>
        <p data-en="Fill in your details to get started today"
           data-ar="أدخل بياناتك لتبدأ اليوم">
            Fill in your details to get started today
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

    <form method="POST" action="{{ route('register.post') }}" novalidate>
        @csrf

        <!-- Account Type -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom"
                   data-en="Account type" data-ar="نوع الحساب">
                Account type
            </label>
            <div class="input-wrapper">
                <i class="bi bi-person-badge input-icon"></i>
                <select name="user_type" class="form-control-custom" required style="appearance:auto;">
                    <option value="JobSeeker" {{ old('user_type','JobSeeker') == 'JobSeeker' ? 'selected' : '' }}
                            data-en="Job Seeker" data-ar="باحث عن عمل">
                        Job Seeker
                    </option>
                    <option value="JobOwner" {{ old('user_type') == 'JobOwner' ? 'selected' : '' }}
                            data-en="Job Owner" data-ar="صاحب عمل">
                        Job Owner
                    </option>
                </select>
            </div>
            @error('user_type')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Name -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="name"
                   data-en="Full name" data-ar="الاسم الكامل">
                Full name
            </label>
            <div class="input-wrapper">
                <i class="bi bi-person input-icon"></i>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    data-placeholder-en="Ahmad Mohammed"
                    data-placeholder-ar="أحمد محمد"
                    placeholder="Ahmad Mohammed"
                    class="form-control-custom @error('name') is-invalid @enderror"
                    required
                >
            </div>
            @error('name')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

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

        <!-- Phone -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="phone"
                data-en="Phone number" data-ar="رقم الهاتف">
                Phone number
            </label>
            <div class="input-wrapper">
                <i class="bi bi-telephone input-icon"></i>
                <input
                    id="phone"
                    type="tel"
                    name="phone"
                    dir="ltr"
                    value="{{ old('phone') }}"
                    autocomplete="tel"
                    data-placeholder-en="e.g. +962791234567"
                    data-placeholder-ar="مثال: 962791234567+"
                    placeholder="e.g. +962791234567"
                    class="form-control-custom @error('phone') is-invalid @enderror"
                >
            </div>
            @error('phone')
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
                    autocomplete="new-password" 
                    minlength="8" 
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
            <div id="strengthLabel" aria-live="polite" role="status" style="font-size:.75rem; margin-top:.3rem; color:#8a95b0;"></div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group-custom stagger-child">
            <label class="form-label-custom" for="password_confirmation"
                   data-en="Confirm password" data-ar="تأكيد كلمة المرور">
                Confirm password
            </label>
            <div class="input-wrapper">
                <i class="bi bi-lock-fill input-icon"></i>
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    autocomplete="new-password" 
                    minlength="8" 
                    data-placeholder-en="Repeat your password"
                    data-placeholder-ar="أعد إدخال كلمة المرور"
                    placeholder="Repeat your password"
                    class="form-control-custom @error('password_confirmation') is-invalid @enderror"
                    required
                >
                <button type="button" class="toggle-pw" id="togglePw2"
                        onclick="togglePassword('togglePw2','password_confirmation')"
                        tabindex="-1" aria-label="Toggle password">
                    <i class="bi bi-eye"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Terms -->
        <div class="mb-4 stagger-child">
            <label class="custom-check">
                <input type="checkbox" name="terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                <span data-en="I agree to the" data-ar="أوافق على">I agree to the</span>
                <a href="#" class="auth-link-gold ms-1"
                   data-en="Terms of Service" data-ar="شروط الخدمة">
                    Terms of Service
                </a>
                &amp;
                <a href="#" class="auth-link-gold ms-1"
                   data-en="Privacy Policy" data-ar="سياسة الخصوصية">
                    Privacy Policy
                </a>
            </label>
            @error('terms')
                <div class="invalid-feedback-custom">
                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                </div>
            @enderror
        </div>

        <!-- Submit -->
        <div class="stagger-child">
            <button type="submit" class="btn-auth"
                    data-en="Create Account" data-ar="إنشاء الحساب">
                <i class="bi bi-person-check me-2"></i> Create Account
            </button>
        </div>

    </form>

    <!-- Login link -->
    <div class="card-footer-note stagger-child">
        <span data-en="Already have an account?"
              data-ar="لديك حساب بالفعل؟">
            Already have an account?
        </span>
        <a href="{{ route('login') }}" class="auth-link-gold ms-1"
           data-en="Sign in →" data-ar="سجّل دخولك →">
            Sign in →
        </a>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('js/register.js') }}" defer></script>
@endpush