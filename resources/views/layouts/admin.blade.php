<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'AJEER') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Tajawal:wght@300;400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ─── Sidebar ─────────────────────────────────────────────── --}}
<aside class="admin-sidebar" id="adminSidebar">

    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-briefcase-fill"></i>
        </div>
        <div>
            <div class="sidebar-brand-name">{{ config('app.name', 'AJEER') }}</div>
            <div class="sidebar-brand-role">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">

        <div class="nav-section-label">Main</div>

        <a href="{{ route('dashboard.admin') }}"
           class="nav-item {{ request()->routeIs('dashboard.admin') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>

        <div class="nav-section-label">Management</div>

        <a href="{{ route('users.index') }}"
           class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Users
        </a>

        <a href="{{ route('jobs.index') }}"
           class="nav-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase-fill"></i> Jobs
            @php $pendingCount = \App\Models\Job::where('status','Pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('skills.index') }}"
           class="nav-item {{ request()->routeIs('skills.*') ? 'active' : '' }}">
            <i class="bi bi-stars"></i> Skills
        </a>

        <div class="nav-section-label">Account</div>

        <a href="{{ route('admin.profile') }}"
           class="nav-item {{ request()->routeIs('admin.profile') ? 'active' : '' }}">
            <i class="bi bi-person-fill"></i> My Profile
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-item">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>

    </nav>

    <div class="sidebar-bottom">
        <a href="{{ route('admin.profile') }}" class="sidebar-user-card">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                     alt="{{ Auth::user()->name }}" class="sidebar-avatar">
            @else
                <div class="sidebar-avatar-placeholder">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <div style="flex:1;min-width:0;">
                <div class="sidebar-user-name">{{ Auth::user()->name }}</div>
                <div class="sidebar-user-type">Administrator</div>
            </div>
            <i class="bi bi-chevron-right" style="color:rgba(255,255,255,.25);font-size:.7rem;"></i>
        </a>
    </div>

</aside>

{{-- ─── Topbar ──────────────────────────────────────────────── --}}
<header class="admin-topbar">

    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <i class="bi bi-list"></i>
    </button>

    <div class="topbar-title">
        @yield('page-title', 'Dashboard')
        <span>/ {{ config('app.name', 'AJEER') }}</span>
    </div>

    <div class="topbar-actions">

        {{-- Notifications --}}
        <div style="position:relative;">
            <button class="notif-btn" id="notifBtn" onclick="toggleNotif()" aria-label="Notifications">
                <i class="bi bi-bell-fill"></i>
                <span class="notif-badge" id="notifBadge"></span>
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-dropdown-header">
                    <h6>Notifications</h6>
                    <button class="notif-mark-all" onclick="markAllRead()">Mark all read</button>
                </div>
                <div class="notif-list" id="notifList">
                    <div class="notif-empty">
                        <i class="bi bi-arrow-clockwise" style="font-size:1.4rem;display:block;margin-bottom:.5rem;animation:spin 1s linear infinite;"></i>
                        Loading...
                    </div>
                </div>
                <div class="notif-dropdown-footer">
                    <a href="{{ route('notifications.index') }}">See all notifications →</a>
                </div>
            </div>
        </div>

        {{-- Profile --}}
        <a href="{{ route('admin.profile') }}" class="profile-btn">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                     alt="{{ Auth::user()->name }}" class="profile-btn-avatar">
            @else
                <div class="profile-btn-placeholder">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif
            <span class="profile-btn-name">{{ Auth::user()->name }}</span>
            <i class="bi bi-chevron-down" style="font-size:.65rem;color:var(--text-muted);"></i>
        </a>

    </div>
</header>

{{-- ─── Main ────────────────────────────────────────────────── --}}
<main class="admin-main">
    <div class="admin-content">

        @if(session('success'))
            <div class="alert-admin alert-admin-success fade-in-up">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert-admin alert-admin-error fade-in-up">
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
@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

// ─── Sidebar ─────────────────────────────────────────────────
function toggleSidebar() {
    document.getElementById('adminSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('adminSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}

// ─── Notifications ────────────────────────────────────────────
let notifOpen = false;
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
            '<div class="notif-empty">Could not load notifications.</div>';
    }
}

function renderNotifs(items) {
    const badge = document.getElementById('notifBadge');
    const list  = document.getElementById('notifList');

    if (!Array.isArray(items) || !items.length) {
        badge.classList.remove('visible');
        list.innerHTML = `<div class="notif-empty">
            <i class="bi bi-bell-slash" style="font-size:1.8rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
            All caught up!
        </div>`;
        return;
    }

    badge.textContent = items.length > 99 ? '99+' : items.length;
    badge.classList.add('visible');

    list.innerHTML = items.slice(0, 12).map(n => `
        <div class="notif-item unread" onclick="readNotif(${n.notification_id}, this)" data-id="${n.notification_id}">
            <div class="notif-icon"><i class="bi bi-bell-fill"></i></div>
            <div class="notif-content">
                <div class="notif-title">${esc(n.title)}</div>
                <div class="notif-text">${esc(n.message)}</div>
                <div class="notif-time">${ago(n.created_at)}</div>
            </div>
        </div>
    `).join('');
}

async function readNotif(id, el) {
    el.classList.remove('unread');
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
    document.querySelectorAll('.notif-item').forEach(el => el.classList.remove('unread'));
    document.getElementById('notifBadge').classList.remove('visible');
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

// Load badge count on page load (no dropdown open)
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