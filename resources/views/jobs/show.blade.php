@extends(Auth::user()->isAdmin() ? 'layouts.admin' : 'layouts.jobowner')

@section('title', $job->title)
@section('page-title', 'Job Detail')

@section('content')

<div class="fade-in-up"
     style="margin-bottom:1.25rem;display:flex;align-items:center;
            justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
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

    {{-- ── Left column: job info + shifts ──────────────────── --}}
    <div class="col-lg-8">

        {{-- Job Information --}}
        <div class="page-card fade-in-up delay-1">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-briefcase-fill"></i> Job Information
                </h6>
                <span class="badge-pill badge-{{ strtolower($job->status) }}">
                    {{ $job->status }}
                </span>
            </div>
            <div class="page-card-body">
                <div class="detail-row">
                    <div class="detail-label">Title</div>
                    <div class="detail-value"
                         style="font-weight:700;font-size:1rem;">
                        {{ $job->title }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Description</div>
                    <div class="detail-value"
                         style="white-space:pre-wrap;line-height:1.6;">
                        {{ $job->description ?? '—' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Location</div>
                    <div class="detail-value">
                        @if($job->location)
                            <i class="bi bi-geo-alt-fill"
                               style="color:var(--primary);"></i>
                            {{ $job->location }}
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Salary</div>
                    <div class="detail-value">
                        {{ $job->salary
                            ? '$' . number_format($job->salary, 2)
                            : 'Not specified' }}
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Posted</div>
                    <div class="detail-value">
                        {{ $job->created_at->format('F j, Y \a\t g:i A') }}
                    </div>
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
        <div class="page-card fade-in-up delay-2" style="margin-top:.75rem;">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-clock-fill"></i>
                    Shifts
                    <span class="badge-pill badge-admin" style="margin-left:.4rem;">
                        {{ $job->shifts->count() }}
                    </span>
                </h6>
                <button class="btn-primary-admin"
                        data-bs-toggle="modal"
                        data-bs-target="#addShiftModal">
                    <i class="bi bi-plus-lg"></i> Add Shift
                </button>
            </div>

            @if($job->shifts->isEmpty())
                <div style="padding:2.5rem;text-align:center;
                            color:var(--text-muted);font-size:.85rem;">
                    <i class="bi bi-clock"
                       style="font-size:2rem;display:block;
                              margin-bottom:.5rem;opacity:.3;"></i>
                    No shifts added yet.
                </div>
            @else
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Duration</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($job->shifts as $shift)
                        <tr>
                            <td style="font-weight:600;">
                                {{ \Carbon\Carbon::parse($shift->shift_date)->format('D, M d Y') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($shift->shift_start)->format('g:i A') }}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($shift->shift_end)->format('g:i A') }}
                            </td>
                            <td style="color:var(--text-muted);font-size:.8rem;">
                                @php
                                    $start    = \Carbon\Carbon::parse($shift->shift_start);
                                    $end      = \Carbon\Carbon::parse($shift->shift_end);
                                    $mins     = $start->diffInMinutes($end);
                                    $hours    = intdiv($mins, 60);
                                    $leftover = $mins % 60;
                                @endphp
                                {{ $hours > 0 ? $hours . 'h ' : '' }}
                                {{ $leftover > 0 ? $leftover . 'm' : '' }}
                            </td>
                            <td>
                                <form method="POST"
                                      action="{{ route('shifts.destroy', [
                                          'job_id'   => $job->job_id,
                                          'shift_id' => $shift->shift_id
                                      ]) }}"
                                      onsubmit="return confirm('Remove this shift?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-admin"
                                            style="padding:.32rem .6rem;font-size:.75rem;">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

    </div>

    {{-- ── Right column: owner + approval info ─────────────── --}}
    <div class="col-lg-4">

        {{-- Posted By --}}
        <div class="page-card fade-in-up delay-2">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-person-fill"></i> Posted By
                </h6>
            </div>
            <div class="page-card-body">
                @if($job->owner)
                    <div style="display:flex;align-items:center;gap:.85rem;
                                margin-bottom:1rem;">
                        @if($job->owner->profile_picture)
                            <img src="{{ asset('storage/' . $job->owner->profile_picture) }}"
                                 alt="{{ $job->owner->name }}"
                                 style="width:48px;height:48px;border-radius:50%;
                                        object-fit:cover;">
                        @else
                            <div style="width:48px;height:48px;border-radius:50%;
                                        background:linear-gradient(135deg,var(--primary),var(--navy-light));
                                        display:flex;align-items:center;justify-content:center;
                                        color:#fff;font-weight:700;font-size:1.1rem;">
                                {{ strtoupper(substr($job->owner->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div style="font-weight:700;font-size:.9rem;">
                                {{ $job->owner->name }}
                            </div>
                            <div style="font-size:.78rem;color:var(--text-muted);">
                                {{ $job->owner->email }}
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('users.show', $job->owner->id) }}"
                       class="btn-ghost"
                       style="width:100%;justify-content:center;">
                        <i class="bi bi-person"></i> View Profile
                    </a>
                @else
                    <span style="color:var(--text-muted);font-size:.85rem;">
                        Owner not found.
                    </span>
                @endif
            </div>
        </div>

        {{-- Approval / Rejection Info --}}
        @if($job->status !== 'Pending' && $job->approvedBy)
        <div class="page-card fade-in-up delay-3" style="margin-top:.75rem;">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    @if($job->status === 'Approved')
                        <i class="bi bi-check-circle-fill"
                           style="color:var(--secondary);"></i> Approval Info
                    @else
                        <i class="bi bi-x-circle-fill"
                           style="color:var(--danger);"></i> Rejection Info
                    @endif
                </h6>
            </div>
            <div class="page-card-body">
                <div class="detail-row" style="padding:.6rem 0;">
                    <div class="detail-label">By</div>
                    <div class="detail-value">
                        {{ $job->approvedBy->name }}
                    </div>
                </div>
                <div class="detail-row" style="padding:.6rem 0;">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">
                        {{ $job->approved_at->format('M d, Y') }}
                    </div>
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

{{-- ─── Add Shift Modal ─────────────────────────────────────── --}}
<div class="modal fade" id="addShiftModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
        <div class="modal-content"
             style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header"
                 style="background:var(--dark-2);border:none;padding:1.1rem 1.4rem;">
                <h5 class="modal-title"
                    style="color:#fff;font-weight:700;font-size:.95rem;">
                    <i class="bi bi-clock-fill"
                       style="color:var(--secondary);margin-right:.4rem;"></i>
                    Add Shift
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <form method="POST"
                  action="{{ route('shifts.store', ['job_id' => $job->job_id]) }}">
                @csrf
                <div class="modal-body" style="padding:1.4rem;">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Shift Date *</label>
                                <input type="date" name="shift_date"
                                       class="form-control-admin {{ $errors->has('shift_date') ? 'is-invalid' : '' }}"
                                       value="{{ old('shift_date') }}" required>
                                @error('shift_date')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Start Time *</label>
                                <input type="time" name="shift_start"
                                       class="form-control-admin {{ $errors->has('shift_start') ? 'is-invalid' : '' }}"
                                       value="{{ old('shift_start') }}" required>
                                @error('shift_start')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">End Time *</label>
                                <input type="time" name="shift_end"
                                       class="form-control-admin {{ $errors->has('shift_end') ? 'is-invalid' : '' }}"
                                       value="{{ old('shift_end') }}" required>
                                @error('shift_end')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                     style="border-top:1px solid var(--border-light);
                            padding:1rem 1.4rem;">
                    <button type="button" class="btn-ghost"
                            data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-admin">
                        <i class="bi bi-plus-lg"></i> Add Shift
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── Reject Modal ────────────────────────────────────────── --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content"
             style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header"
                 style="background:rgba(231,76,60,.06);
                        border-bottom:1px solid rgba(231,76,60,.15);
                        padding:1.1rem 1.4rem;">
                <h5 class="modal-title"
                    style="font-weight:700;font-size:.95rem;color:var(--danger);">
                    <i class="bi bi-x-circle-fill"></i> Reject Job
                </h5>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm" action="">
                @csrf
                <div class="modal-body" style="padding:1.4rem;">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Rejection Reason *</label>
                        <textarea name="rejection_reason"
                                  class="form-control-admin" rows="3"
                                  placeholder="Explain why this job is being rejected..."
                                  required maxlength="255"></textarea>
                    </div>
                </div>
                <div class="modal-footer"
                     style="border-top:1px solid var(--border-light);
                            padding:1rem 1.4rem;">
                    <button type="button" class="btn-ghost"
                            data-bs-dismiss="modal">Cancel</button>
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

// Auto-open shift modal if there are shift validation errors
@if($errors->hasAny(['shift_date', 'shift_start', 'shift_end']))
    document.addEventListener('DOMContentLoaded', () => {
        new bootstrap.Modal(document.getElementById('addShiftModal')).show();
    });
@endif
</script>
@endpush