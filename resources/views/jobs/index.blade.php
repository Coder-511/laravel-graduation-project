@extends('layouts.admin')

@section('title', 'Jobs')
@section('page-title', 'Jobs')

@section('content')

<div class="page-header fade-in-up">
    <h1>Jobs</h1>
    <p>Manage all jobs, approve or reject pending submissions.</p>
</div>

{{-- Add Job button + filter bar --}}
<div class="page-card fade-in-up delay-1">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="bi bi-briefcase-fill"></i>
            All Jobs
        </h6>
        <div style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <form method="GET" action="{{ route('jobs.index') }}"
                  style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
                <div class="search-wrapper">
                    <i class="bi bi-search search-icon"></i>
                    <input type="text" name="search" class="search-input"
                           placeholder="Search title..." value="{{ request('search') }}"
                           style="min-width:190px;">
                </div>
                <select name="status" class="search-input" style="padding-left:.9rem;min-width:130px;">
                    <option value="">All Status</option>
                    <option value="Pending"  {{ request('status') === 'Pending'  ? 'selected' : '' }}>Pending</option>
                    <option value="Approved" {{ request('status') === 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <button type="submit" class="btn-primary-admin">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                @if(request('search') || request('status'))
                    <a href="{{ route('jobs.index') }}" class="btn-ghost">
                        <i class="bi bi-x"></i> Clear
                    </a>
                @endif
            </form>

            {{-- Add job button → opens modal --}}
            <button class="btn-success-admin" data-bs-toggle="modal" data-bs-target="#addJobModal">
                <i class="bi bi-plus-lg"></i> Add Job
            </button>
        </div>
    </div>

    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Location</th>
                    <th>Salary</th>
                    <th>Status</th>
                    <th>Posted</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td style="color:var(--text-muted);font-size:.78rem;">#{{ $job->job_id }}</td>
                    <td>
                        <div style="font-weight:600;font-size:.875rem;">{{ $job->title }}</div>
                        @if($job->skills->isNotEmpty())
                            <div style="margin-top:.3rem;display:flex;flex-wrap:wrap;gap:.25rem;">
                                @foreach($job->skills->take(3) as $skill)
                                    <span class="badge-pill badge-seeker" style="font-size:.62rem;padding:.15rem .5rem;">
                                        {{ $skill->skill_name }}
                                    </span>
                                @endforeach
                                @if($job->skills->count() > 3)
                                    <span style="font-size:.7rem;color:var(--text-muted);">+{{ $job->skills->count() - 3 }} more</span>
                                @endif
                            </div>
                        @endif
                    </td>
                    <td style="font-size:.83rem;">{{ $job->owner->name ?? '—' }}</td>
                    <td style="font-size:.83rem;color:var(--text-muted);">
                        {{ $job->location ?? '—' }}
                    </td>
                    <td style="font-size:.83rem;">
                        {{ $job->salary ? '$' . number_format($job->salary, 2) : '—' }}
                    </td>
                    <td>
                        <span class="badge-pill badge-{{ strtolower($job->status) }}">
                            {{ $job->status }}
                        </span>
                    </td>
                    <td style="font-size:.76rem;color:var(--text-muted);">
                        {{ $job->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.35rem;align-items:center;flex-wrap:wrap;">

                            <a href="{{ route('jobs.show', $job->job_id) }}" class="btn-ghost"
                               title="Inspect" style="padding:.35rem .6rem;">
                                <i class="bi bi-eye"></i>
                            </a>

                            @if($job->status === 'Pending')
                                {{-- Approve --}}
                                <form method="POST" action="{{ route('jobs.approve', $job->job_id) }}">
                                    @csrf
                                    <button type="submit" class="btn-success-admin"
                                            style="padding:.35rem .65rem;" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>

                                {{-- Reject --}}
                                <button class="btn-danger-admin" style="padding:.35rem .65rem;" title="Reject"
                                        onclick="openRejectModal({{ $job->job_id }}, '{{ addslashes($job->title) }}')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            @endif

                            <form method="POST" action="{{ route('jobs.destroy', $job->job_id) }}"
                                  onsubmit="return confirm('Delete \'{{ addslashes($job->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-danger-admin"
                                        style="padding:.35rem .6rem;background:transparent;border-color:transparent;color:#ccc;"
                                        title="Delete">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:3rem;color:var(--text-muted);">
                        <i class="bi bi-briefcase" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
                        No jobs found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ─── Add Job Modal ───────────────────────────────────────── --}}
<div class="modal fade" id="addJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header" style="background:var(--dark-2);border:none;padding:1.25rem 1.5rem;">
                <h5 class="modal-title" style="color:#fff;font-weight:700;font-size:1rem;">
                    <i class="bi bi-plus-circle-fill" style="color:var(--secondary);margin-right:.5rem;"></i>
                    Post New Job
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('jobs.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Job Title *</label>
                                <input type="text" name="title" class="form-control-admin"
                                       placeholder="e.g. Senior Software Engineer" required maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Salary (optional)</label>
                                <input type="number" name="salary" class="form-control-admin"
                                       placeholder="0.00" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Location (optional)</label>
                                <input type="text" name="location" class="form-control-admin"
                                       placeholder="e.g. Amman, Jordan" maxlength="150">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Description (optional)</label>
                                <textarea name="description" class="form-control-admin"
                                          rows="4" placeholder="Job description..."></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Required Skills (optional)</label>
                                <select name="skill_ids[]" class="form-control-admin" multiple
                                        style="height:110px;">
                                    @foreach(\App\Models\Skill::orderBy('skill_name')->get() as $skill)
                                        <option value="{{ $skill->skill_id }}">{{ $skill->skill_name }}</option>
                                    @endforeach
                                </select>
                                <div style="font-size:.72rem;color:var(--text-muted);margin-top:.3rem;">
                                    Hold Ctrl / Cmd to select multiple skills.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-light);padding:1rem 1.5rem;">
                    <div style="font-size:.78rem;color:var(--text-muted);">
                        <i class="bi bi-info-circle"></i>
                        As admin, this job will be instantly approved.
                    </div>
                    <div style="display:flex;gap:.5rem;margin-left:auto;">
                        <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-admin">
                            <i class="bi bi-check-lg"></i> Post & Approve
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ─── Reject Modal ────────────────────────────────────────── --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header" style="background:rgba(231,76,60,.06);border-bottom:1px solid rgba(231,76,60,.15);padding:1.1rem 1.4rem;">
                <h5 class="modal-title" style="font-weight:700;font-size:.95rem;color:var(--danger);">
                    <i class="bi bi-x-circle-fill" style="margin-right:.4rem;"></i>
                    Reject Job
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="rejectForm" action="">
                @csrf
                <div class="modal-body" style="padding:1.4rem;">
                    <p style="font-size:.875rem;color:var(--dark);margin-bottom:1rem;">
                        Rejecting: <strong id="rejectJobTitle"></strong>
                    </p>
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
    document.getElementById('rejectJobTitle').textContent = jobTitle;
    document.getElementById('rejectForm').action = `/admin/jobs/${jobId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>
@endpush