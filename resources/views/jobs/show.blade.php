@extends('layouts.admin')

@section('title', $job->title)
@section('page-title', 'Job Detail')

@section('content')

<div class="fade-in-up" style="margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
    <a href="{{ route('jobs.index') }}" class="btn-ghost">
        <i class="bi bi-arrow-left"></i> Back to Jobs
    </a>
    <div style="display:flex;gap:.5rem;align-items:center;">
        @if($job->status === 'Pending')
            <form method="POST" action="{{ route('jobs.approve', $job->job_id) }}">
                @csrf
                <button type="submit" class="btn-success-admin">
                    <i class="bi bi-check-lg"></i> Approve
                </button>
            </form>
            <button class="btn-danger-admin"
                    onclick="openRejectModal({{ $job->job_id }}, '{{ addslashes($job->title) }}')">
                <i class="bi bi-x-lg"></i> Reject
            </button>
        @endif
        <form method="POST" action="{{ route('jobs.destroy', $job->job_id) }}"
              onsubmit="return confirm('Delete this job? This cannot be undone.')">
            @csrf @method('DELETE')
            <button type="submit" class="btn-danger-admin">
                <i class="bi bi-trash3"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="row g-3">

    {{-- Main details --}}
    <div class="col-lg-8">

        <div class="page-card fade-in-up delay-1">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-briefcase-fill"></i> Job Information
                </h6>
                <span class="badge-pill badge-{{ strtolower($job->status) }}">{{ $job->status }}</span>
            </div>
            <div class="page-card-body">
                <div class="detail-row">
                    <div class="detail-label">Title</div>
                    <div class="detail-value" style="font-weight:700;font-size:1rem;">{{ $job->title }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description</div>
                    <div class="detail-value" style="white-space:pre-wrap;line-height:1.6;">
                        {{ $job->description ?? '—' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        @if($job->location)
                            <i class="bi bi-geo-alt-fill" style="color:var(--primary);"></i>
                            {{ $job->location }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Salary</div>
                    <div class="detail-value">
                        {{ $job->salary ? '$' . number_format($job->salary, 2) : 'Not specified' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Posted</div>
                    <div class="detail-value">{{ $job->created_at->format('F j, Y \a\t g:i A') }}</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Required Skills</div>
                    <div class="detail-value">
                        @if($job->skills->isEmpty())
                            <span style="color:var(--text-muted);">None specified</span>
                        @else
                            <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                                @foreach($job->skills as $skill)
                                    <span class="badge-pill badge-seeker">
                                        <i class="bi bi-check-circle-fill"></i>
                                        {{ $skill->skill_name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Shifts --}}
        @if($job->shifts->isNotEmpty())
        <div class="page-card fade-in-up delay-2" style="margin-top:.75rem;">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-clock-fill"></i>
                    Shifts
                    <span class="badge-pill badge-admin" style="margin-left:.4rem;">{{ $job->shifts->count() }}</span>
                </h6>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($job->shifts as $shift)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($shift->shift_date)->format('M d, Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shift->shift_start)->format('g:i A') }}</td>
                            <td>{{ \Carbon\Carbon::parse($shift->shift_end)->format('g:i A') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>

    {{-- Sidebar: owner + approval info --}}
    <div class="col-lg-4">

        <div class="page-card fade-in-up delay-2">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-person-fill"></i> Posted By
                </h6>
            </div>
            <div class="page-card-body">
                @if($job->owner)
                <div style="display:flex;align-items:center;gap:.85rem;margin-bottom:1rem;">
                    @if($job->owner->profile_picture)
                        <img src="{{ asset('storage/' . $job->owner->profile_picture) }}"
                             alt="{{ $job->owner->name }}"
                             style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                    @else
                        <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--navy-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:1.1rem;">
                            {{ strtoupper(substr($job->owner->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div style="font-weight:700;font-size:.9rem;">{{ $job->owner->name }}</div>
                        <div style="font-size:.78rem;color:var(--text-muted);">{{ $job->owner->email }}</div>
                    </div>
                </div>
                <a href="{{ route('users.show', $job->owner->id) }}" class="btn-ghost"
                   style="width:100%;justify-content:center;">
                    <i class="bi bi-person"></i> View Profile
                </a>
                @else
                    <span style="color:var(--text-muted);font-size:.85rem;">Owner not found.</span>
                @endif
            </div>
        </div>

        @if($job->status !== 'Pending' && $job->approvedBy)
        <div class="page-card fade-in-up delay-3" style="margin-top:.75rem;">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    @if($job->status === 'Approved')
                        <i class="bi bi-check-circle-fill" style="color:var(--secondary);"></i> Approval Info
                    @else
                        <i class="bi bi-x-circle-fill" style="color:var(--danger);"></i> Rejection Info
                    @endif
                </h6>
            </div>
            <div class="page-card-body">
                <div class="detail-row" style="padding:.6rem 0;">
                    <div class="detail-label">By</div>
                    <div class="detail-value">{{ $job->approvedBy->name }}</div>
                </div>
                <div class="detail-row" style="padding:.6rem 0;">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">{{ $job->approved_at->format('M d, Y') }}</div>
                </div>
                @if($job->rejection_reason)
                <div class="detail-row" style="padding:.6rem 0;">
                    <div class="detail-label">Reason</div>
                    <div class="detail-value" style="color:var(--danger);">
                        {{ $job->rejection_reason }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header" style="background:rgba(231,76,60,.06);border-bottom:1px solid rgba(231,76,60,.15);padding:1.1rem 1.4rem;">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;color:var(--danger);">
                    <i class="bi bi-x-circle-fill"></i> Reject Job
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm" action="">
                @csrf
                <div class="modal-body" style="padding:1.4rem;">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Rejection Reason *</label>
                        <textarea name="rejection_reason" class="form-control-admin" rows="3"
                                  placeholder="Explain why this job is being rejected..."
                                  required maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-light);padding:1rem 1.4rem;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-danger-admin">
                        <i class="bi bi-x-lg"></i> Confirm Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openRejectModal(jobId, jobTitle) {
    document.getElementById('rejectForm').action = `/admin/jobs/${jobId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush