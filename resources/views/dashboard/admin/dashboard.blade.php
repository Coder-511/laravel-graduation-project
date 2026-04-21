@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')

<div class="page-header fade-in-up">
    <h1>Welcome back, {{ Auth::user()->name }} 👋</h1>
    <p>Here's an overview of everything happening on the platform.</p>
</div>

{{-- Stat Cards --}}
<div class="row g-3 mb-3">
    <div class="col-6 col-xl-3 fade-in-up delay-1">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-value">{{ $stats['totalUsers'] }}</div>
            <div class="stat-label">Total Users</div>
            <div class="stat-sub">{{ $stats['jobOwners'] }} owners &middot; {{ $stats['jobSeekers'] }} seekers</div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-2">
        <div class="stat-card green">
            <div class="stat-icon"><i class="bi bi-briefcase-fill"></i></div>
            <div class="stat-value">{{ $stats['totalJobs'] }}</div>
            <div class="stat-label">Total Jobs</div>
            <div class="stat-sub">{{ $stats['approvedJobs'] }} approved &middot; {{ $stats['rejectedJobs'] }} rejected</div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-3">
        <div class="stat-card orange">
            <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-value">{{ $stats['pendingJobs'] }}</div>
            <div class="stat-label">Pending Jobs</div>
            <div class="stat-sub">
                @if($stats['pendingJobs'] > 0)
                    <a href="{{ route('jobs.index') }}?status=Pending"
                       style="color:var(--warning);font-weight:600;text-decoration:none;">
                        Review now →
                    </a>
                @else
                    All caught up!
                @endif
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3 fade-in-up delay-4">
        <div class="stat-card blue">
            <div class="stat-icon"><i class="bi bi-stars"></i></div>
            <div class="stat-value">{{ $stats['totalSkills'] }}</div>
            <div class="stat-label">Skills</div>
            <div class="stat-sub">
                <a href="{{ route('skills.index') }}"
                   style="color:var(--primary);font-weight:600;text-decoration:none;">
                    Manage →
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
                    <i class="bi bi-briefcase-fill"></i> Recent Jobs
                </h6>
                <a href="{{ route('jobs.index') }}" class="btn-ghost">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>Posted</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($stats['recentJobs'] as $job)
                        <tr>
                            <td>
                                <div style="font-weight:600;font-size:.85rem;">{{ $job->title }}</div>
                                @if($job->location)
                                    <div style="font-size:.73rem;color:var(--text-muted);">
                                        <i class="bi bi-geo-alt"></i> {{ $job->location }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:.83rem;">{{ $job->owner->name ?? '—' }}</td>
                            <td>
                                <span class="badge-pill badge-{{ strtolower($job->status) }}">
                                    {{ $job->status }}
                                </span>
                            </td>
                            <td style="font-size:.76rem;color:var(--text-muted);">
                                {{ $job->created_at->format('M d, Y') }}
                            </td>
                            <td>
                                <a href="{{ route('jobs.show', $job->job_id) }}" class="btn-ghost"
                                   style="padding:.3rem .6rem;">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2.5rem;color:var(--text-muted);">
                                No jobs posted yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Users --}}
    <div class="col-lg-5 fade-in-up delay-3">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-people-fill"></i> Recent Users
                </h6>
                <a href="{{ route('users.index') }}" class="btn-ghost">
                    View all <i class="bi bi-arrow-right"></i>
                </a>
            </div>

            @forelse($stats['recentUsers'] as $user)
            <div style="display:flex;align-items:center;gap:.8rem;padding:.8rem 1.4rem;border-bottom:1px solid var(--border-light);">
                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                         alt="{{ $user->name }}"
                         style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--navy-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.78rem;flex-shrink:0;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div style="flex:1;min-width:0;">
                    <div style="font-weight:600;font-size:.84rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $user->name }}
                    </div>
                    <div style="font-size:.73rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        {{ $user->email }}
                    </div>
                </div>
                <span class="badge-pill {{ $user->user_type === 'JobOwner' ? 'badge-owner' : 'badge-seeker' }}">
                    {{ $user->user_type === 'JobOwner' ? 'Owner' : 'Seeker' }}
                </span>
            </div>
            @empty
            <div style="text-align:center;padding:2.5rem;color:var(--text-muted);font-size:.85rem;">
                No users yet.
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection