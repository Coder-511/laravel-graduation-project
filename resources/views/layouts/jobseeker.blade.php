<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <title>@yield('title', 'Dashboard') — {{ config('app.name', 'AJEER') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jobseeker.css') }}">
    @stack('styles')
</head>
<body>

{{-- ─── Navbar ──────────────────────────────────────────────── --}}
<nav class="sn-navbar">

    <a href="{{ route('dashboard.jobseeker') }}" class="sn-brand">
        <div class="sn-brand-icon">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <span class="sn-brand-name">{{ config('app.name', 'AJEER') }}</span>
    </a>

    {{-- Desktop nav --}}
    <div class="sn-nav">
        <a href="{{ route('dashboard.jobseeker') }}"
           class="sn-nav-link {{ request()->routeIs('dashboard.jobseeker') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i> Home
        </a>
        <a href="{{ route('jobseeker.jobs') }}"
           class="sn-nav-link {{ request()->routeIs('jobseeker.jobs') ? 'active' : '' }}">
            <i class="bi bi-search"></i> Browse Jobs
        </a>
        <a href="{{ route('jobseeker.applications') }}"
           class="sn-nav-link {{ request()->routeIs('jobseeker.applications') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i> My Applications
        </a>
        <a href="{{ route('jobseeker.profile') }}"
           class="sn-nav-link {{ request()->routeIs('jobseeker.profile') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> Profile
        </a>
    </div>

    <div class="sn-nav-actions">

        {{-- Notifications --}}
        <div style="position:relative;">
            <button class="sn-notif-btn" id="notifBtn"
                    onclick="toggleNotif()" aria-label="Notifications">
                <i class="bi bi-bell-fill"></i>
                <span class="sn-notif-badge" id="notifBadge"></span>
            </button>

            <div class="sn-notif-dropdown" id="notifDropdown">
                <div style="padding:.9rem 1.1rem;border-bottom:1px solid var(--border);
                            display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:.86rem;font-weight:700;color:var(--dark);">
                        Notifications
                    </span>
                    <button onclick="markAllRead()"
                            style="font-size:.73rem;color:var(--primary);cursor:pointer;
                                   background:none;border:none;font-weight:600;
                                   font-family:inherit;padding:0;">
                        Mark all read
                    </button>
                </div>
                <div id="notifList" style="max-height:320px;overflow-y:auto;">
                    <div style="padding:2rem;text-align:center;color:var(--muted);font-size:.83rem;">
                        <i class="bi bi-arrow-clockwise"
                           style="font-size:1.3rem;display:block;margin-bottom:.4rem;
                                  animation:spin 1s linear infinite;"></i>
                        Loading...
                    </div>
                </div>
                <div style="padding:.65rem 1.1rem;border-top:1px solid var(--border);
                            text-align:center;">
                    <a href="{{ route('notifications.index') }}"
                       style="font-size:.78rem;color:var(--primary);
                              text-decoration:none;font-weight:600;">
                        See all notifications →
                    </a>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <a href="{{ route('jobseeker.profile') }}" class="sn-profile-btn">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                     alt="{{ Auth::user()->name }}" class="sn-profile-avatar">
            @else
                <div class="sn-profile-placeholder">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <span class="sn-profile-name">{{ Auth::user()->name }}</span>
            <i class="bi bi-chevron-down"
               style="font-size:.62rem;color:var(--muted);"></i>
        </a>

        {{-- Mobile menu --}}
        <button class="sn-menu-btn" onclick="toggleMobileNav()"
                aria-label="Menu">
            <i class="bi bi-list" id="menuIcon"></i>
        </button>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" style="display:none;" id="logoutForm">
            @csrf
        </form>
    </div>

</nav>

{{-- Mobile nav --}}
<div class="sn-mobile-nav" id="mobileNav">
    <a href="{{ route('dashboard.jobseeker') }}"
       class="sn-nav-link {{ request()->routeIs('dashboard.jobseeker') ? 'active' : '' }}">
        <i class="bi bi-house-fill"></i> Home
    </a>
    <a href="{{ route('jobseeker.jobs') }}"
       class="sn-nav-link {{ request()->routeIs('jobseeker.jobs') ? 'active' : '' }}">
        <i class="bi bi-search"></i> Browse Jobs
    </a>
    <a href="{{ route('jobseeker.applications') }}"
       class="sn-nav-link {{ request()->routeIs('jobseeker.applications') ? 'active' : '' }}">
        <i class="bi bi-file-earmark-text-fill"></i> My Applications
    </a>
    <a href="{{ route('jobseeker.profile') }}"
       class="sn-nav-link {{ request()->routeIs('jobseeker.profile') ? 'active' : '' }}">
        <i class="bi bi-person-fill"></i> Profile
    </a>
    <a href="#" class="sn-nav-link"
       onclick="event.preventDefault();document.getElementById('logoutForm').submit()">
        <i class="bi bi-box-arrow-left"></i> Logout
    </a>
</div>

{{-- ─── Main ────────────────────────────────────────────────── --}}
<main class="sn-main">
    <div class="sn-container">

        @if(session('success'))
            <div class="sn-alert sn-alert-success sn-fade">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="sn-alert sn-alert-error sn-fade">
                <i class="bi bi-exclamation-circle-fill" style="flex-shrink:0;"></i>
                <div>
                    @foreach($errors->all() as $e)
                        <div>{{ $e }}</div>
                    @endforeach
                </div>
            </div>
        @endif

        @yield('content')

    </div>
</main>

<style>
@keyframes spin {
    from { transform: rotate(0deg); }
    to   { transform: rotate(360deg); }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── Mobile nav ───────────────────────────────────────────────
function toggleMobileNav() {
    const nav  = document.getElementById('mobileNav');
    const icon = document.getElementById('menuIcon');
    const open = nav.classList.toggle('open');
    icon.className = open ? 'bi bi-x-lg' : 'bi bi-list';
}

// ─── Notifications ────────────────────────────────────────────
let notifOpen   = false;
let notifLoaded = false;

function toggleNotif() {
    notifOpen = !notifOpen;
    document.getElementById('notifDropdown').classList.toggle('open', notifOpen);
    if (notifOpen && !notifLoaded) { loadNotifications(); notifLoaded = true; }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('#notifBtn') && !e.target.closest('#notifDropdown')) {
        notifOpen = false;
        document.getElementById('notifDropdown').classList.remove('open');
    }
});

async function loadNotifications() {
    try {
        const res  = await fetch('{{ route("notifications.unread") }}',
            { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        const data = await res.json();
        renderNotifs(data);
    } catch {
        document.getElementById('notifList').innerHTML =
            '<div style="padding:1.5rem;text-align:center;color:var(--muted);font-size:.82rem;">Could not load notifications.</div>';
    }
}

function renderNotifs(items) {
    const badge = document.getElementById('notifBadge');
    const list  = document.getElementById('notifList');

    if (!Array.isArray(items) || !items.length) {
        badge.classList.remove('visible');
        list.innerHTML = `<div style="padding:2rem;text-align:center;color:var(--muted);font-size:.82rem;">
            <i class="bi bi-bell-slash" style="font-size:1.6rem;display:block;margin-bottom:.4rem;opacity:.35;"></i>
            All caught up!
        </div>`;
        return;
    }

    badge.textContent = items.length > 99 ? '99+' : items.length;
    badge.classList.add('visible');

    list.innerHTML = items.slice(0, 10).map(n => `
        <div style="display:flex;gap:.7rem;padding:.8rem 1.1rem;
                    border-bottom:1px solid var(--border);cursor:pointer;
                    transition:background .2s;"
             onmouseenter="this.style.background='#f7fafd'"
             onmouseleave="this.style.background=''"
             onclick="readNotif(${n.notification_id}, this)">
            <div style="width:32px;height:32px;border-radius:8px;
                        background:rgba(52,152,219,.1);color:var(--primary);
                        display:flex;align-items:center;justify-content:center;
                        font-size:.78rem;flex-shrink:0;">
                <i class="bi bi-bell-fill"></i>
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:.8rem;font-weight:600;color:var(--dark);
                            margin-bottom:.15rem;white-space:nowrap;overflow:hidden;
                            text-overflow:ellipsis;">${esc(n.title)}</div>
                <div style="font-size:.74rem;color:var(--muted);line-height:1.4;
                            display:-webkit-box;-webkit-line-clamp:2;
                            -webkit-box-orient:vertical;overflow:hidden;">
                    ${esc(n.message)}
                </div>
                <div style="font-size:.68rem;color:#adb5bd;margin-top:.2rem;">
                    ${ago(n.created_at)}
                </div>
            </div>
        </div>
    `).join('');
}

async function readNotif(id, el) {
    await fetch(`/notifications/${id}/read`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const badge = document.getElementById('notifBadge');
    const count = parseInt(badge.textContent) || 0;
    if (count <= 1) badge.classList.remove('visible');
    else badge.textContent = count - 1;
}

async function markAllRead() {
    await fetch('{{ route("notifications.readAll") }}', {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    document.getElementById('notifBadge').classList.remove('visible');
    loadNotifications();
    notifLoaded = false;
}

function esc(s) {
    return String(s)
        .replace(/&/g,'&amp;').replace(/</g,'&lt;')
        .replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function ago(dateStr) {
    const diff = Math.floor((Date.now() - new Date(dateStr)) / 1000);
    if (diff < 60)    return 'Just now';
    if (diff < 3600)  return Math.floor(diff / 60) + 'm ago';
    if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
    return Math.floor(diff / 86400) + 'd ago';
}

// Load badge count on page load
(async function() {
    try {
        const res  = await fetch('{{ route("notifications.unread") }}',
            { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF } });
        const data = await res.json();
        if (Array.isArray(data) && data.length) {
            const b = document.getElementById('notifBadge');
            b.textContent = data.length > 99 ? '99+' : data.length;
            b.classList.add('visible');
        }
    } catch {}
})();
</script>

@stack('scripts')
</body>
</html>