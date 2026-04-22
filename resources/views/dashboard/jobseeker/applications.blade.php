@extends('layouts.jobseeker')

@section('title', 'My Applications')

@section('content')

<div class="sn-page-header sn-fade">
    <h1>My Applications</h1>
    <p>Track the status of every job you've applied to.</p>
</div>

@if($applications->isEmpty())
    <div style="background:var(--card-bg);border:1px solid var(--border);
                border-radius:var(--radius);padding:4rem;text-align:center;
                color:var(--muted);" class="sn-fade">
        <i class="bi bi-file-earmark-text"
           style="font-size:3rem;display:block;margin-bottom:.75rem;opacity:.25;"></i>
        <div style="font-size:.95rem;font-weight:600;margin-bottom:.3rem;">
            No applications yet
        </div>
        <div style="font-size:.83rem;margin-bottom:1.25rem;">
            Start applying to jobs and your applications will appear here.
        </div>
        <a href="{{ route('jobseeker.jobs') }}" class="sn-btn-primary">
            <i class="bi bi-search"></i> Browse Jobs
        </a>
    </div>
@else
    <div style="display:flex;flex-direction:column;gap:.65rem;">
        @foreach($applications as $i => $app)
        <div class="sn-app-card {{ strtolower($app->status) }} sn-fade"
             style="animation-delay:{{ $i * 0.04 }}s;">

            {{-- Status indicator icon --}}
            <div style="width:44px;height:44px;border-radius:11px;flex-shrink:0;
                        display:flex;align-items:center;justify-content:center;
                        font-size:1.1rem;
                        {{ $app->status === 'Accepted' ? 'background:rgba(46,204,113,.1);color:#1a7a4a;' : '' }}
                        {{ $app->status === 'Pending'  ? 'background:rgba(243,156,18,.1);color:#b7770d;' : '' }}
                        {{ $app->status === 'Rejected' ? 'background:rgba(231,76,60,.1);color:#c0392b;'  : '' }}
                        {{ $app->status === 'Canceled' ? 'background:rgba(127,140,154,.1);color:#566573;' : '' }}">
                @if($app->status === 'Accepted')  <i class="bi bi-check-circle-fill"></i>
                @elseif($app->status === 'Rejected') <i class="bi bi-x-circle-fill"></i>
                @elseif($app->status === 'Canceled') <i class="bi bi-slash-circle-fill"></i>
                @else <i class="bi bi-hourglass-split"></i>
                @endif
            </div>

            {{-- Job info --}}
            <div style="flex:1;min-width:0;">
                <div style="font-weight:700;font-size:.92rem;color:var(--dark);
                            margin-bottom:.2rem;white-space:nowrap;overflow:hidden;
                            text-overflow:ellipsis;">
                    {{ $app->job->title }}
                </div>
                <div style="font-size:.77rem;color:var(--muted);
                            display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
                    @if($app->job->owner)
                        <span>
                            <i class="bi bi-building" style="font-size:.68rem;"></i>
                            {{ $app->job->owner->name }}
                        </span>
                    @endif
                    @if($app->job->location)
                        <span>
                            <i class="bi bi-geo-alt" style="font-size:.68rem;"></i>
                            {{ $app->job->location }}
                        </span>
                    @endif
                    <span>
                        <i class="bi bi-clock" style="font-size:.68rem;"></i>
                        Applied {{ $app->created_at->diffForHumans() }}
                    </span>
                </div>

                @if($app->job->skills->isNotEmpty())
                <div style="display:flex;flex-wrap:wrap;gap:.28rem;margin-top:.45rem;">
                    @foreach($app->job->skills->take(4) as $skill)
                        <span class="sn-skill-tag">{{ $skill->skill_name }}</span>
                    @endforeach
                </div>
                @endif

                @if($app->message)
                    <div style="font-size:.75rem;color:var(--muted);margin-top:.4rem;
                                font-style:italic;
                                display:-webkit-box;-webkit-line-clamp:1;
                                -webkit-box-orient:vertical;overflow:hidden;">
                        "{{ $app->message }}"
                    </div>
                @endif
            </div>

            {{-- Right: status + action --}}
            <div style="display:flex;flex-direction:column;align-items:flex-end;
                        gap:.5rem;flex-shrink:0;">
                <span class="sn-badge sn-badge-{{ strtolower($app->status) }}">
                    {{ $app->status }}
                </span>

                @if($app->status === 'Pending')
                    <form method="POST"
                          action="{{ route('applications.cancelWeb', $app->application_id) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="sn-btn-danger"
                                onclick="return confirm('Cancel this application?')">
                            <i class="bi bi-x"></i> Cancel
                        </button>
                    </form>
                @endif
            </div>

        </div>
        @endforeach
    </div>
@endif

@endsection