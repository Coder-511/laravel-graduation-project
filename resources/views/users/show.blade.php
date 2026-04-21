@extends('layouts.admin')

@section('title', $user->name)
@section('page-title', 'User Detail')

@section('content')

<div class="fade-in-up" style="margin-bottom:1.25rem;display:flex;align-items:center;gap:.75rem;">
    <a href="{{ route('users.index') }}" class="btn-ghost">
        <i class="bi bi-arrow-left"></i> Back to Users
    </a>
</div>

<div class="row g-3">

    {{-- Left: identity card --}}
    <div class="col-lg-4 fade-in-up delay-1">
        <div class="page-card">
            <div class="page-card-body" style="text-align:center;padding:2rem 1.4rem;">

                @if($user->profile_picture)
                    <img src="{{ asset('storage/' . $user->profile_picture) }}"
                         alt="{{ $user->name }}"
                         style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--border-light);margin-bottom:1rem;">
                @else
                    <div style="width:90px;height:90px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--navy-light));display:flex;align-items:center;justify-content:center;color:#fff;font-size:2.2rem;font-weight:700;margin:0 auto 1rem;border:3px solid var(--border-light);">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif

                <div style="font-size:1.15rem;font-weight:800;color:var(--dark);">{{ $user->name }}</div>
                <div style="font-size:.83rem;color:var(--text-muted);margin:.25rem 0 .75rem;">{{ $user->email }}</div>

                @if($user->user_type === 'Admin')
                    <span class="badge-pill badge-admin">Administrator</span>
                @elseif($user->user_type === 'JobOwner')
                    <span class="badge-pill badge-owner">Job Owner</span>
                @else
                    <span class="badge-pill badge-seeker">Job Seeker</span>
                @endif

                <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--border-light);font-size:.82rem;color:var(--text-muted);">
                    <i class="bi bi-calendar3"></i>
                    Member since {{ $user->created_at->format('M d, Y') }}
                </div>

                @if($user->id !== Auth::id())
                <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                      style="margin-top:1rem;"
                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger-admin" style="width:100%;justify-content:center;">
                        <i class="bi bi-trash3"></i> Delete User
                    </button>
                </form>
                @endif

            </div>
        </div>
    </div>

    {{-- Right: details --}}
    <div class="col-lg-8">
        <div class="row g-3">

            {{-- Account Info --}}
            <div class="col-12 fade-in-up delay-2">
                <div class="page-card">
                    <div class="page-card-header">
                        <h6 class="page-card-title">
                            <i class="bi bi-info-circle-fill"></i> Account Information
                        </h6>
                    </div>
                    <div class="page-card-body">
                        <div class="detail-row">
                            <div class="detail-label">Full Name</div>
                            <div class="detail-value">{{ $user->name }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Email</div>
                            <div class="detail-value">{{ $user->email }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Phone</div>
                            <div class="detail-value">{{ $user->phone ?? '—' }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Account Type</div>
                            <div class="detail-value">{{ $user->user_type }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Joined</div>
                            <div class="detail-value">{{ $user->created_at->format('F j, Y \a\t g:i A') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JobOwner: their jobs --}}
            @if($user->isJobOwner())
            <div class="col-12 fade-in-up delay-3">
                <div class="page-card">
                    <div class="page-card-header">
                        <h6 class="page-card-title">
                            <i class="bi bi-briefcase-fill"></i>
                            Posted Jobs
                            <span class="badge-pill badge-admin" style="margin-left:.4rem;">
                                {{ $user->jobs->count() }}
                            </span>
                        </h6>
                    </div>
                    @if($user->jobs->isEmpty())
                        <div style="padding:1.75rem;text-align:center;color:var(--text-muted);font-size:.85rem;">
                            No jobs posted yet.
                        </div>
                    @else
                    <div style="overflow-x:auto;">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Posted</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($user->jobs as $job)
                                <tr>
                                    <td style="font-weight:600;font-size:.85rem;">{{ $job->title }}</td>
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
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- JobSeeker: their skills --}}
            @if($user->isJobSeeker())
            <div class="col-12 fade-in-up delay-3">
                <div class="page-card">
                    <div class="page-card-header">
                        <h6 class="page-card-title">
                            <i class="bi bi-stars"></i>
                            Skills
                            <span class="badge-pill badge-admin" style="margin-left:.4rem;">
                                {{ $user->skills->count() }}
                            </span>
                        </h6>
                    </div>
                    <div class="page-card-body">
                        @if($user->skills->isEmpty())
                            <span style="color:var(--text-muted);font-size:.85rem;">No skills added yet.</span>
                        @else
                            <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                                @foreach($user->skills as $skill)
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
            @endif

        </div>
    </div>

</div>

@endsection