@extends('layouts.jobseeker')

@section('title', 'Browse Jobs')

@section('content')

<div class="sn-page-header sn-fade">
    <h1>Browse Jobs</h1>
    <p>Find your next opportunity from all available positions.</p>
</div>

{{-- Filter bar --}}
<form method="GET" action="{{ route('jobseeker.jobs') }}">
    <div class="sn-filter-bar sn-fade sn-d1">
        <div class="sn-search-wrap">
            <i class="bi bi-search sn-search-icon"></i>
            <input type="text" name="search" class="sn-input"
                   placeholder="Search by title, location or keyword..."
                   value="{{ request('search') }}">
        </div>
        <select name="skill" class="sn-input no-icon"
                style="min-width:160px;padding:.58rem .9rem;">
            <option value="">All Skills</option>
            @foreach($allSkills as $skill)
                <option value="{{ $skill->skill_id }}"
                        {{ request('skill') == $skill->skill_id ? 'selected' : '' }}>
                    {{ $skill->skill_name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="sn-btn-primary">
            <i class="bi bi-funnel-fill"></i> Filter
        </button>
        @if(request('search') || request('skill'))
            <a href="{{ route('jobseeker.jobs') }}" class="sn-btn-ghost">
                <i class="bi bi-x"></i> Clear
            </a>
        @endif
    </div>
</form>

{{-- Results count --}}
<div style="font-size:.82rem;color:var(--muted);margin-bottom:1rem;" class="sn-fade sn-d2">
    {{ $jobs->total() }} job{{ $jobs->total() !== 1 ? 's' : '' }} found
    @if(request('search'))
        for "<strong>{{ request('search') }}</strong>"
    @endif
</div>

@if($jobs->isEmpty())
    <div style="background:var(--card-bg);border:1px solid var(--border);
                border-radius:var(--radius);padding:4rem;text-align:center;
                color:var(--muted);" class="sn-fade sn-d2">
        <i class="bi bi-search"
           style="font-size:3rem;display:block;margin-bottom:.75rem;opacity:.25;"></i>
        <div style="font-size:.95rem;font-weight:600;margin-bottom:.3rem;">
            No jobs found
        </div>
        <div style="font-size:.83rem;">
            Try a different search or check back later.
        </div>
    </div>
@else
    <div class="sn-grid">
        @foreach($jobs as $i => $job)
        <div class="sn-job-card sn-fade" style="animation-delay:{{ min($i, 8) * 0.05 }}s;">

            <div class="sn-job-owner">
                <i class="bi bi-building"></i>
                {{ $job->owner->name ?? 'Unknown' }}
            </div>

            <div class="sn-job-title">{{ $job->title }}</div>

            @if($job->description)
                <div style="font-size:.78rem;color:var(--muted);line-height:1.5;
                            display:-webkit-box;-webkit-line-clamp:2;
                            -webkit-box-orient:vertical;overflow:hidden;">
                    {{ $job->description }}
                </div>
            @endif

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
                    {{ $job->created_at->format('M d, Y') }}
                </div>
            </div>

            @if($job->skills->isNotEmpty())
            <div class="sn-job-skills">
                @foreach($job->skills->take(5) as $skill)
                    <span class="sn-skill-tag">{{ $skill->skill_name }}</span>
                @endforeach
                @if($job->skills->count() > 5)
                    <span style="font-size:.67rem;color:var(--muted);">
                        +{{ $job->skills->count() - 5 }}
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

    {{-- Pagination --}}
    @if($jobs->hasPages())
        <div style="margin-top:1.5rem;display:flex;justify-content:center;">
            {{ $jobs->links() }}
        </div>
    @endif
@endif

{{-- Apply Modal (same as dashboard) --}}
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
                            — optional
                        </span>
                    </label>
                    <textarea name="message" class="sn-form-control" rows="4"
                              placeholder="Introduce yourself or mention why you're a great fit..."
                              maxlength="1000"></textarea>
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