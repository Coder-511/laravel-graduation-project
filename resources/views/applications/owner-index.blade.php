@extends('layouts.jobowner')

@section('title', 'Applications')
@section('page-title', 'Applications')

@section('content')

<div class="page-header fade-in-up">
    <h1>Applications</h1>
    <p>Review and manage applicants for your jobs.</p>
</div>

<div class="page-card fade-in-up delay-1">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="bi bi-people-fill"></i>
            All Applications
        </h6>

        {{-- Filters --}}
        <form method="GET" action="{{ route('applications.ownerIndex') }}"
              style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <select name="job_id" class="search-input"
                    style="padding-left:.9rem;min-width:180px;">
                <option value="">All My Jobs</option>
                @foreach($myJobs as $j)
                    <option value="{{ $j->job_id }}"
                            {{ request('job_id') == $j->job_id ? 'selected' : '' }}>
                        {{ $j->title }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="search-input"
                    style="padding-left:.9rem;min-width:130px;">
                <option value="">All Status</option>
                <option value="Pending"  {{ request('status') === 'Pending'  ? 'selected' : '' }}>Pending</option>
                <option value="Accepted" {{ request('status') === 'Accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="Rejected" {{ request('status') === 'Rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="Canceled" {{ request('status') === 'Canceled' ? 'selected' : '' }}>Canceled</option>
            </select>
            <button type="submit" class="btn-primary-admin">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request('job_id') || request('status'))
                <a href="{{ route('applications.ownerIndex') }}" class="btn-ghost">
                    <i class="bi bi-x"></i> Clear
                </a>
            @endif
        </form>
    </div>

    @if(isset($filterJob))
        <div style="padding:.75rem 1.4rem;background:rgba(52,152,219,.05);
                    border-bottom:1px solid var(--border-light);
                    font-size:.83rem;color:var(--primary);">
            <i class="bi bi-funnel-fill"></i>
            Showing applications for: <strong>{{ $filterJob->title }}</strong>
        </div>
    @endif

    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Applicant</th>
                    <th>Job</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Applied</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:.7rem;">
                            @if($app->seeker->profile_picture)
                                <img src="{{ asset('storage/' . $app->seeker->profile_picture) }}"
                                     alt="{{ $app->seeker->name }}"
                                     style="width:34px;height:34px;border-radius:50%;
                                            object-fit:cover;flex-shrink:0;">
                            @else
                                <div style="width:34px;height:34px;border-radius:50%;
                                            background:linear-gradient(135deg,var(--primary),var(--navy-light));
                                            display:flex;align-items:center;
                                            justify-content:center;color:#fff;
                                            font-weight:700;font-size:.75rem;
                                            flex-shrink:0;">
                                    {{ strtoupper(substr($app->seeker->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:.875rem;">
                                    {{ $app->seeker->name }}
                                </div>
                                <div style="font-size:.73rem;color:var(--text-muted);">
                                    {{ $app->seeker->email }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:.83rem;font-weight:500;">
                        {{ $app->job->title }}
                    </td>
                    <td style="max-width:180px;">
                        @if($app->message)
                            <span style="font-size:.78rem;color:var(--text-muted);
                                         display:-webkit-box;-webkit-line-clamp:2;
                                         -webkit-box-orient:vertical;overflow:hidden;">
                                {{ $app->message }}
                            </span>
                        @else
                            <span style="color:#ccc;font-size:.78rem;">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-pill badge-{{ strtolower($app->status) }}">
                            {{ $app->status }}
                        </span>
                    </td>
                    <td style="font-size:.76rem;color:var(--text-muted);">
                        {{ $app->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        @if($app->status === 'Pending')
                            <div style="display:flex;gap:.4rem;">
                                <form method="POST"
                                      action="{{ route('applications.updateStatus',
                                                        $app->application_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status"
                                           value="Accepted">
                                    <button type="submit" class="btn-success-admin"
                                            style="padding:.38rem .7rem;"
                                            title="Accept">
                                        <i class="bi bi-check-lg"></i> Accept
                                    </button>
                                </form>
                                <form method="POST"
                                      action="{{ route('applications.updateStatus',
                                                        $app->application_id) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status"
                                           value="Rejected">
                                    <button type="submit" class="btn-danger-admin"
                                            style="padding:.38rem .7rem;"
                                            title="Reject">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="font-size:.78rem;color:var(--text-muted);">
                                Reviewed
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6"
                        style="text-align:center;padding:3rem;
                               color:var(--text-muted);">
                        <i class="bi bi-people"
                           style="font-size:2.5rem;display:block;
                                  margin-bottom:.75rem;opacity:.3;"></i>
                        No applications found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection