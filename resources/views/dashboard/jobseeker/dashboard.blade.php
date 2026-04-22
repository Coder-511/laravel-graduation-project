@extends('layouts.jobseeker')

@section('title', 'Home')

@section('content')

{{-- Hero --}}
<div class="sn-hero sn-fade">
    <div class="sn-hero-inner">
        <div class="sn-hero-eyebrow">Job Seeker Dashboard</div>
        <h1 class="sn-hero-title">
            Hey {{ explode(' ', Auth::user()->name)[0] }}, find your next opportunity 👋
        </h1>
        <p class="sn-hero-sub">
            Browse fresh jobs, track your applications, and get hired.
        </p>
        <div class="sn-hero-pills">
            <div class="sn-hero-pill blue">
                <i class="bi bi-file-earmark-text-fill"></i>
                {{ $totalApplications }} Applications
            </div>
            <div class="sn-hero-pill green">
                <i class="bi bi-check-circle-fill"></i>
                {{ $acceptedApplications }} Accepted
            </div>
            <div class="sn-hero-pill">
                <i class="bi bi-hourglass-split"></i>
                {{ $pendingApplications }} Pending
            </div>
        </div>
    </div>
</div>

{{-- Recent Jobs --}}
<div class="sn-fade sn-d1">
    <div class="sn-section-header">
        <div class="sn-section-title">
            <i class="bi bi-lightning-charge-fill"></i>
            Recently Posted Jobs
        </div>
        <a href="{{ route('jobseeker.jobs') }}" class="sn-view-all">
            Browse all jobs <i class="bi bi-arrow-right"></i>
        </a>
    </div>

    @if($recentJobs->isEmpty())
        <div style="background:var(--card-bg);border:1px solid var(--border);
                    border-radius:var(--radius);padding:3rem;text-align:center;
                    color:var(--muted);">
            <i class="bi bi-briefcase"
               style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
            No jobs available yet. Check back soon!
        </div>
    @else
        <div class="sn-grid">
            @foreach($recentJobs as $i => $job)
            <div class="sn-job-card sn-fade" style="animation-delay:{{ $i * 0.05 }}s;">
                <div class="sn-job-owner">
                    <i class="bi bi-building"></i>
                    {{ $job->owner->name ?? 'Unknown' }}
                </div>

                <div class="sn-job-title">{{ $job->title }}</div>

                <div class="sn-job-meta">
                    @if($job->location)
                        <div class="sn-job-meta-item">
                            <i class="bi bi-geo-alt-fill"></i>
                            {{ $job->location }}
                        </div>
                    @endif
                    @if($job->shifts->count())
                        <div class="sn-job-meta-item">
                            <i class="bi bi-clock-fill"></i>
                            {{ $job->shifts->count() }} shift(s)
                        </div>
                    @endif
                    <div class="sn-job-meta-item">
                        <i class="bi bi-calendar3"></i>
                        {{ $job->created_at->diffForHumans() }}
                    </div>
                </div>

                @if($job->skills->isNotEmpty())
                <div class="sn-job-skills">
                    @foreach($job->skills->take(4) as $skill)
                        <span class="sn-skill-tag">{{ $skill->skill_name }}</span>
                    @endforeach
                    @if($job->skills->count() > 4)
                        <span style="font-size:.67rem;color:var(--muted);">
                            +{{ $job->skills->count() - 4 }}
                        </span>
                    @endif
                </div>
                @endif

                <div class="sn-job-footer">
                    <div class="{{ $job->salary ? 'sn-salary' : 'sn-salary none' }}">
                        {{ $job->salary ? '$' . number_format($job->salary, 2) : 'Salary not listed' }}
                    </div>

                    @if(in_array($job->job_id, $appliedJobIds))
                        <div class="sn-btn-applied">
                            <i class="bi bi-check-circle-fill"></i> Applied
                        </div>
                    @else
                        <button class="sn-btn-apply"
                                onclick="openApplyModal(
                                    {{ $job->job_id }},
                                    '{{ addslashes($job->title) }}',
                                    '{{ addslashes($job->owner->name ?? '') }}')">
                            <i class="bi bi-send-fill"></i> Apply
                        </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

{{-- Apply Modal --}}
<div class="sn-modal-overlay" id="applyOverlay" onclick="closeApplyIfOutside(event)">
    <div class="sn-modal" id="applyModal">
        <div class="sn-modal-header">
            <div class="sn-modal-icon">
                <i class="bi bi-send-fill"></i>
            </div>
            <div>
                <div class="sn-modal-title" id="applyModalTitle">Apply for Job</div>
                <div class="sn-modal-sub" id="applyModalSub"></div>
            </div>
            <button class="sn-modal-close" onclick="closeApplyModal()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form method="POST" id="applyForm" action="">
            @csrf
            <div class="sn-modal-body">
                <div class="sn-form-group">
                    <label class="sn-form-label">
                        Message
                        <span style="font-weight:400;text-transform:none;
                                     letter-spacing:0;color:var(--muted);">
                            — optional intro
                        </span>
                    </label>
                    <textarea name="message" class="sn-form-control" rows="4"
                              placeholder="Introduce yourself briefly, or mention why you're a great fit..."
                              maxlength="1000"></textarea>
                    <div style="font-size:.71rem;color:var(--muted);margin-top:.28rem;">
                        Max 1000 characters
                    </div>
                </div>
            </div>
            <div class="sn-modal-footer">
                <button type="button" class="sn-btn-ghost"
                        onclick="closeApplyModal()">Cancel</button>
                <button type="submit" class="sn-btn-primary">
                    <i class="bi bi-send-fill"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openApplyModal(jobId, title, owner) {
    document.getElementById('applyModalTitle').textContent = title;
    document.getElementById('applyModalSub').textContent   = owner ? `at ${owner}` : '';
    document.getElementById('applyForm').action = `/jobs/${jobId}/apply-web`;
    document.getElementById('applyOverlay').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeApplyModal() {
    document.getElementById('applyOverlay').classList.remove('open');
    document.body.style.overflow = '';
}

function closeApplyIfOutside(e) {
    if (e.target === document.getElementById('applyOverlay')) closeApplyModal();
}
</script>
@endpush