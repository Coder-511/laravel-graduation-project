@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.jobowner')

@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')

<div class="page-header fade-in-up">
    <h1>Notifications</h1>
    <p>All notifications sent to your account.</p>
</div>

<div class="page-card fade-in-up delay-1">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="bi bi-bell-fill"></i>
            All Notifications
            <span class="badge-pill badge-admin" style="margin-left:.4rem;">
                {{ $notifications->total() }}
            </span>
        </h6>
        @if($notifications->total() > 0)
            <button class="btn-ghost" onclick="markAllReadAndReload()">
                <i class="bi bi-check-all"></i> Mark all as read
            </button>
        @endif
    </div>

    @if($notifications->isEmpty())
        <div style="padding:3.5rem;text-align:center;color:var(--text-muted);">
            <i class="bi bi-bell-slash"
               style="font-size:2.8rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
            <div style="font-size:.9rem;font-weight:600;">You're all caught up!</div>
            <div style="font-size:.82rem;margin-top:.3rem;">No notifications yet.</div>
        </div>
    @else
        <div>
            @foreach($notifications as $notif)
            <div class="notif-row {{ !$notif->is_read ? 'unread' : '' }}"
                 id="notif-{{ $notif->notification_id }}"
                 style="display:flex;align-items:flex-start;gap:1rem;padding:1rem 1.4rem;border-bottom:1px solid var(--border-light);position:relative;transition:background .2s;">

                {{-- Unread indicator bar --}}
                @if(!$notif->is_read)
                <div style="position:absolute;left:0;top:0;bottom:0;width:3px;background:var(--primary);border-radius:0 3px 3px 0;"></div>
                @endif

                {{-- Icon --}}
                <div style="width:42px;height:42px;border-radius:10px;background:{{ $notif->is_read ? 'var(--light-bg)' : 'rgba(52,152,219,.1)' }};color:{{ $notif->is_read ? 'var(--text-muted)' : 'var(--primary)' }};display:flex;align-items:center;justify-content:center;font-size:.95rem;flex-shrink:0;">
                    <i class="bi bi-bell-fill"></i>
                </div>

                {{-- Content --}}
                <div style="flex:1;min-width:0;">
                    <div style="display:flex;align-items:center;gap:.6rem;flex-wrap:wrap;margin-bottom:.25rem;">
                        <span style="font-size:.88rem;font-weight:{{ $notif->is_read ? '500' : '700' }};color:var(--dark);">
                            {{ $notif->title }}
                        </span>
                        @if(!$notif->is_read)
                            <span class="badge-pill badge-admin"
                                  style="font-size:.6rem;padding:.12rem .5rem;">New</span>
                        @endif
                    </div>
                    <div style="font-size:.83rem;color:var(--text-muted);line-height:1.5;">
                        {{ $notif->message }}
                    </div>
                    <div style="font-size:.72rem;color:#adb5bd;margin-top:.4rem;">
                        <i class="bi bi-clock"></i>
                        {{ $notif->created_at->diffForHumans() }}
                        &middot;
                        {{ $notif->created_at->format('M d, Y \a\t g:i A') }}
                    </div>
                </div>

                {{-- Actions --}}
                <div style="display:flex;align-items:center;gap:.4rem;flex-shrink:0;">
                    @if(!$notif->is_read)
                        <button class="btn-ghost" style="padding:.32rem .6rem;font-size:.75rem;"
                                onclick="markOneRead({{ $notif->notification_id }})"
                                title="Mark as read">
                            <i class="bi bi-check"></i>
                        </button>
                    @endif
                    <button class="btn-danger-admin" style="padding:.32rem .6rem;font-size:.75rem;background:transparent;border-color:transparent;color:#ccc;"
                            onclick="deleteNotif({{ $notif->notification_id }})"
                            title="Delete">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

            </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
        <div style="padding:1rem 1.4rem;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;">
            {{ $notifications->links() }}
        </div>
        @endif
    @endif
</div>

@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name="csrf-token"]').content;

async function markOneRead(id) {
    await fetch(`/notifications/${id}/read`, {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const row = document.getElementById(`notif-${id}`);
    if (!row) return;
    // Remove unread styles
    row.classList.remove('unread');
    row.querySelector('[style*="position:absolute"]')?.remove();
    // Swap icon bg/color
    const icon = row.querySelector('[style*="border-radius:10px"]');
    if (icon) {
        icon.style.background = 'var(--light-bg)';
        icon.style.color = 'var(--text-muted)';
    }
    // Remove "New" badge
    row.querySelector('.badge-admin')?.remove();
    // Remove the "mark as read" button itself
    row.querySelector('[onclick*="markOneRead"]')?.remove();
    // Un-bold title
    const title = row.querySelector('[style*="font-weight"]');
    if (title) title.style.fontWeight = '500';
}

async function deleteNotif(id) {
    await fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const row = document.getElementById(`notif-${id}`);
    if (row) {
        row.style.transition = 'opacity .25s, max-height .3s';
        row.style.opacity = '0';
        row.style.overflow = 'hidden';
        row.style.maxHeight = row.offsetHeight + 'px';
        setTimeout(() => { row.style.maxHeight = '0'; row.style.padding = '0'; }, 10);
        setTimeout(() => row.remove(), 320);
    }
}

async function markAllReadAndReload() {
    await fetch('{{ route("notifications.readAll") }}', {
        method: 'PATCH',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    location.reload();
}
</script>
@endpush