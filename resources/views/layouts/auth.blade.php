<!DOCTYPE html>
<html lang="en" dir="ltr" id="htmlRoot">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <title>@yield('title', 'Auth') — {{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 (id required for RTL swap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet" id="bootstrapCSS">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!--
        Fonts: Inter (matches home page) + Tajawal (Arabic)
        Removed: Playfair Display, DM Sans — these caused the visual mismatch
    -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Animated background -->
    <div class="auth-bg" aria-hidden="true">
        <div class="geo-lines"></div>
        <div class="diamond"></div>
        <div class="diamond"></div>
        <div class="diamond"></div>
        <div class="diamond"></div>
        <div class="diamond"></div>
    </div>

    <!-- Language toggle — floats top-right on every auth page -->
    <button class="auth-lang-btn" onclick="toggleAuthLanguage()" id="authLangBtn" aria-label="Toggle language">
        <i class="bi bi-translate"></i>
        <span id="authLangBtnText">عربي</span>
    </button>

    <div class="auth-wrapper">

        <!-- Left decorative panel -->
        <div class="auth-left">
            <div class="brand-area">

                <!--
                    Logo: bi-briefcase-fill matches the home navbar logo exactly.
                    Old icon was bi-layers-half which looked unrelated to home.
                -->
                <div class="brand-logo">
                    <i class="bi bi-briefcase-fill"></i>
                </div>

                <div class="brand-name">{{ config('app.name', 'AJEER') }}</div>
                <div class="brand-tagline"
                     data-en="Your trusted platform"
                     data-ar="منصتك الموثوقة">
                    Your trusted platform
                </div>
            </div>

            <div class="left-feature">
                @yield('left-features')
            </div>
        </div>

        <div class="auth-divider"></div>

        <!-- Right form panel -->
        <div class="auth-right">
            <div class="auth-card">
                @yield('form-content')
            </div>
        </div>

    </div>

    <!-- Bootstrap JS — version bumped to match home (5.3.3) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/auth.js') }}" defer></script>

    @stack('scripts')
</body>
</html>