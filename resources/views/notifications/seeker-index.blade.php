@extends('layouts.jobseeker')

@section('title', 'Notifications')

@section('content')

<div class="sn-page-header sn-fade">
    <h1>Notifications</h1>
    <p>All your updates in one place.</p>
</div>

<div style="display:flex;align-items:center;justify-content:space-between;
            margin-bottom:1rem;flex-wrap:wrap;gap:.5rem;" class="sn-fade sn-d1">
    <span style="font-size:.82rem;color:var(--muted);">
        {{ $notifications->total() }} notification{{ $notifications->total() !== 1 ? 's' : '' }}
    </span>
    @if($notifications->total() > 0)
        <button class="sn-btn-ghost" onclick="markAllReadAndReload()"
                style="font-size:.78rem;padding:.45rem .85rem;">
            <i class="bi bi-check-all"></i> Mark all as read
        </button>
    @endif
</div>

@if($notifications->isEmpty())
    <div style="background:var(--card-bg);border:1px solid var(--border);
                border-radius:var(--radius);padding:4rem;text-align:center;
                color:var(--muted);" class="sn-fade sn-d1">
        <i class="bi bi-bell-slash"
           style="font-size:3rem;display:block;margin-bottom:.75rem;opacity:.25;"></i>
        <div style="font-size:.95rem;font-weight:600;margin-bottom:.3rem;">
            All caught up!
        </div>
        <div style="font-size:.83rem;">No notifications yet.</div>
    </div>
@else
    <div class="sn-notif-list-page">
        @foreach($notifications as $i => $notif)
        <div class="sn-notif-row {{ !$notif->is_read ? 'unread' : '' }} sn-fade"
             id="notif-{{ $notif->notification_id }}"
             style="animation-delay:{{ $i * 0.04 }}s;">

            <div class="sn-notif-row-icon"
                 style="{{ $notif->is_read ? 'background:var(--light-bg);color:var(--muted);' : '' }}">
                <i class="bi bi-bell-fill"></i>
            </div>

            <div class="sn-notif-row-content">
                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;
                            margin-bottom:.18rem;">
                    <span class="sn-notif-row-title"
                          style="font-weight:{{ $notif->is_read ? '500' : '700' }};">
                        {{ $notif->title }}
                    </span>
                    @if(!$notif->is_read)
                        <span style="display:inline-flex;align-items:center;
                                     padding:.1rem .45rem;background:rgba(52,152,219,.1);
                                     color:var(--primary);border-radius:20px;
                                     font-size:.62rem;font-weight:700;">
                            New
                        </span>
                    @endif
                </div>
                <div class="sn-notif-row-text">{{ $notif->message }}</div>
                <div class="sn-notif-row-time">
                    <i class="bi bi-clock" style="font-size:.65rem;"></i>
                    {{ $notif->created_at->diffForHumans() }}
                    &middot;
                    {{ $notif->created_at->format('M d, Y \a\t g:i A') }}
                </div>
            </div>

            <div style="display:flex;align-items:center;gap:.35rem;flex-shrink:0;">
                @if(!$notif->is_read)
                    <button class="sn-btn-ghost"
                            style="padding:.3rem .55rem;font-size:.73rem;"
                            onclick="markOneRead({{ $notif->notification_id }})"
                            title="Mark as read">
                        <i class="bi bi-check"></i>
                    </button>
                @endif
                <button class="sn-btn-danger"
                        style="padding:.3rem .55rem;font-size:.73rem;"
                        onclick="deleteNotif({{ $notif->notification_id }})"
                        title="Delete">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>

        </div>
        @endforeach
    </div>

    @if($notifications->hasPages())
        <div style="margin-top:1.5rem;display:flex;justify-content:center;">
            {{ $notifications->links() }}
        </div>
    @endif
@endif

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
    row.classList.remove('unread');
    row.querySelector('.sn-notif-row-icon').style.cssText =
        'background:var(--light-bg);color:var(--muted);';
    row.querySelector('[style*="font-weight"]').style.fontWeight = '500';
    row.querySelector('[onclick*="markOneRead"]')?.remove();
    row.querySelector('[style*="New"]')?.remove();
}

async function deleteNotif(id) {
    await fetch(`/notifications/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    });
    const row = document.getElementById(`notif-${id}`);
    if (row) {
        row.style.transition = 'opacity .25s, max-height .3s, margin .3s';
        row.style.opacity = '0';
        row.style.overflow = 'hidden';
        row.style.maxHeight = row.offsetHeight + 'px';
        setTimeout(() => { row.style.maxHeight = '0'; row.style.marginBottom = '0'; }, 50);
        setTimeout(() => row.remove(), 350);
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