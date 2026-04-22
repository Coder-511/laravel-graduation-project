@extends('layouts.jobowner')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header fade-in-up">
    <h1>Welcome back, {{ Auth::user()->name }} 👋</h1>
    <p>Here's an overview of your jobs and applicants.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3 fade-in-up delay-1">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
            <div class="stat-value">{{ $stats['totalJobs'] }}</div>
            <div class="stat-label">My Jobs</div>
            <div class="stat-sub">
                {{ $stats['approvedJobs'] }} approved
                &middot; {{ $stats['pendingJobs'] }} pending
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-2">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $stats['totalApplications'] }}</div>
            <div class="stat-label">Total Applications</div>
            <div class="stat-sub">
                {{ $stats['pendingApplications'] }} awaiting review
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ $stats['pendingJobs'] }}</div>
            <div class="stat-label">Pending Approval</div>
            <div class="stat-sub">
                @if($stats['pendingJobs'] > 0)
                    Waiting for admin review
                @else
                    All caught up!
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-4">
        <div class="stat-card red">
            <div class="stat-icon"><i class="bi bi-x-circle-fill"></i></div>
            <div class="stat-value">{{ $stats['rejectedJobs'] }}</div>
            <div class="stat-label">Rejected Jobs</div>
            <div class="stat-sub">
                <a href="{{ route('jobs.index') }}"
                   style="color:var(--danger);font-weight:600;
                          text-decoration:none;">
                    View all →
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">

    {{-- Recent Jobs --}}
    <div class="col-lg-7 fade-in-up delay-2">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-briefcase-fill"></i> My Recent Jobs
                </h6>
                <div style="display:flex;gap:.5rem;">
                    <a href="{{ route('jobs.index') }}" class="btn-ghost">
                        View all <i class="bi bi-arrow-right"></i>
                    </a>
                    <button class="btn-success-admin"
                            data-bs-toggle="modal"
                            data-bs-target="#quickAddJobModal">
                        <i class="bi bi-plus-lg"></i> Post Job
                    </button>
                </div>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Shifts</th>
                            <th>Posted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recentJobs'] as $job)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:.85rem;">
                                    {{ $job->title }}
                                </div>
                                @if($job->location)
                                    <div style="font-size:.73rem;color:var(--text-muted);">
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $job->location }}
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge-pill badge-{{ strtolower($job->status) }}">
                                    {{ $job->status }}
                                </span>
                            </td>
                            <td>
                                @if($job->shifts->count() === 0)
                                    <span style="font-size:.75rem;color:var(--warning);
                                                 font-weight:600;">
                                        <i class="bi bi-exclamation-circle"></i>
                                        None
                                    </span>
                                @else
                                    <span style="font-size:.75rem;color:var(--text-muted);">
                                        {{ $job->shifts->count() }} shift(s)
                                    </span>
                                @endif
                            </td>
                            <td style="font-size:.76rem;color:var(--text-muted);">
                                {{ $job->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('jobs.show', $job->job_id) }}"
                                   class="btn-ghost"
                                   style="padding:.3rem .6rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5"
                                style="text-align:center;padding:2.5rem;
                                       color:var(--text-muted);">
                                No jobs posted yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Applications --}}
    <div class="col-lg-5 fade-in-up delay-3">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-people-fill"></i> Recent Applicants
                </h6>
                <a href="{{ route('applications.ownerIndex') }}" class="btn-ghost">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @forelse($stats['recentApplications'] as $app)
            <div style="display:flex;align-items:center;gap:.8rem;
                        padding:.8rem 1.4rem;
                        border-bottom:1px solid var(--border-light);">
                @if($app->seeker->profile_picture)
                    <img src="{{ asset('storage/' . $app->seeker->profile_picture) }}"
                         alt="{{ $app->seeker->name }}"
                         style="width:36px;height:36px;border-radius:50%;
                                object-fit:cover;flex-shrink:0;">
                @else
                    <div style="width:36px;height:36px;border-radius:50%;
                                background:linear-gradient(135deg,var(--primary),var(--navy-light));
                                display:flex;align-items:center;justify-content:center;
                                color:#fff;font-weight:700;font-size:.78rem;flex-shrink:0;">
                        {{ strtoupper(substr($app->seeker->name, 0, 1)) }}
                    </div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:.84rem;
                                white-space:nowrap;overflow:hidden;
                                text-overflow:ellipsis;">
                        {{ $app->seeker->name }}
                    </div>
                    <div style="font-size:.73rem;color:var(--text-muted);
                                white-space:nowrap;overflow:hidden;
                                text-overflow:ellipsis;">
                        {{ $app->job->title }}
                    </div>
                </div>
                <span class="badge-pill badge-{{ strtolower($app->status) }}">
                    {{ $app->status }}
                </span>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;
                        color:var(--text-muted);font-size:.85rem;">
                No applications yet.
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- Quick Add Job Modal (same as jobs/index modal) --}}
<div class="modal fade" id="quickAddJobModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content"
             style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header"
                 style="background:var(--dark-2);border:none;padding:1.25rem 1.5rem;">
                <h5 class="modal-title"
                    style="color:#fff;font-weight:700;font-size:1rem;">
                    <i class="bi bi-plus-circle-fill"
                       style="color:var(--secondary);margin-right:.5rem;"></i>
                    Post New Job
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('jobs.store') }}">
                @csrf
                <div class="modal-body" style="padding:1.5rem;">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Job Title *</label>
                                <input type="text" name="title"
                                       class="form-control-admin"
                                       placeholder="e.g. Delivery Driver"
                                       required maxlength="100">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Salary (optional)</label>
                                <input type="number" name="salary"
                                       class="form-control-admin"
                                       placeholder="0.00" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Location (optional)</label>
                                <input type="text" name="location"
                                       class="form-control-admin"
                                       placeholder="e.g. Amman, Jordan"
                                       maxlength="150">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Description (optional)</label>
                                <textarea name="description"
                                          class="form-control-admin" rows="3"
                                          placeholder="Job description..."></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">
                                    Required Skills
                                    <span style="font-weight:400;text-transform:none;
                                                 letter-spacing:0;color:var(--text-muted);">
                                        — click to select
                                    </span>
                                </label>
                                <input type="text" id="skillSearchDash"
                                       class="form-control-admin"
                                       placeholder="🔍 Filter skills..."
                                       oninput="filterSkillsDash()"
                                       style="margin-bottom:.6rem;">
                                <div id="skillChipsDash"
                                     style="display:flex;flex-wrap:wrap;gap:.45rem;
                                            max-height:140px;overflow-y:auto;padding:.5rem;
                                            background:var(--light-bg);
                                            border:1.5px solid var(--border-light);
                                            border-radius:var(--radius-sm);">
                                    @foreach(\App\Models\Skill::orderBy('skill_name')->get() as $skill)
                                        <label class="skill-chip"
                                               data-name="{{ strtolower($skill->skill_name) }}">
                                            <input type="checkbox"
                                                   name="skill_ids[]"
                                                   value="{{ $skill->skill_id }}"
                                                   hidden>
                                            <span>{{ $skill->skill_name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div id="skillCountDash"
                                     style="font-size:.72rem;color:var(--text-muted);
                                            margin-top:.35rem;">
                                    0 skills selected
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer"
                     style="border-top:1px solid var(--border-light);padding:1rem 1.5rem;
                            display:flex;align-items:center;
                            justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
                    <div style="font-size:.78rem;color:var(--text-muted);">
                        <i class="bi bi-info-circle"></i>
                        Job will be sent to admin for approval.
                    </div>
                    <div style="display:flex;gap:.5rem;">
                        <button type="button" class="btn-ghost"
                                data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-primary-admin">
                            <i class="bi bi-send"></i> Submit Job
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('#skillChipsDash .skill-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        const cb = chip.querySelector('input[type="checkbox"]');
        cb.checked = !cb.checked;
        chip.classList.toggle('selected', cb.checked);
        const n = document.querySelectorAll(
            '#skillChipsDash .skill-chip.selected').length;
        document.getElementById('skillCountDash').textContent =
            n === 0 ? '0 skills selected'
                    : `${n} skill${n > 1 ? 's' : ''} selected`;
    });
});

function filterSkillsDash() {
    const q = document.getElementById('skillSearchDash').value.toLowerCase();
    document.querySelectorAll('#skillChipsDash .skill-chip').forEach(chip => {
        chip.style.display = chip.dataset.name.includes(q) ? '' : 'none';
    });
}

document.getElementById('quickAddJobModal')
        .addEventListener('hidden.bs.modal', () => {
    document.querySelectorAll('#skillChipsDash .skill-chip.selected')
            .forEach(c => {
        c.classList.remove('selected');
        c.querySelector('input').checked = false;
    });
    document.getElementById('skillSearchDash').value = '';
    document.getElementById('skillCountDash').textContent = '0 skills selected';
    document.querySelectorAll('#skillChipsDash .skill-chip')
            .forEach(c => c.style.display = '');
});
</script>
@endpush