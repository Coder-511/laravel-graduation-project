@extends('layouts.admin')

@section('title', 'Users')
@section('page-title', 'Users')

@section('content')

<div class="page-header fade-in-up">
    <h1>Users</h1>
    <p>View and manage all registered users on the platform.</p>
</div>

<div class="page-card fade-in-up delay-1">
    <div class="page-card-header">
        <h6 class="page-card-title">
            <i class="bi bi-people-fill"></i>
            All Users
            <span class="badge-pill badge-admin" style="margin-left:.4rem;">{{ $users->total() }}</span>
        </h6>

        <form method="GET" action="{{ route('users.index') }}"
              style="display:flex;gap:.5rem;align-items:center;flex-wrap:wrap;">
            <div class="search-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class="search-input"
                       placeholder="Search name or email..." value="{{ request('search') }}"
                       style="min-width:210px;">
            </div>
            <select name="type" class="search-input" style="padding-left:.9rem;min-width:140px;">
                <option value="">All Types</option>
                <option value="JobOwner"  {{ request('type') === 'JobOwner'  ? 'selected' : '' }}>Job Owner</option>
                <option value="JobSeeker" {{ request('type') === 'JobSeeker' ? 'selected' : '' }}>Job Seeker</option>
            </select>
            <button type="submit" class="btn-primary-admin">
                <i class="bi bi-funnel"></i> Filter
            </button>
            @if(request('search') || request('type'))
                <a href="{{ route('users.index') }}" class="btn-ghost">
                    <i class="bi bi-x"></i> Clear
                </a>
            @endif
        </form>
    </div>

    <div style="overflow-x:auto;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                <tr>
                    <td style="color:var(--text-muted);font-size:.78rem;">#{{ $u->id }}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:.7rem;">
                            @if($u->profile_picture)
                                <img src="{{ asset('storage/' . $u->profile_picture) }}"
                                     alt="{{ $u->name }}"
                                     style="width:34px;height:34px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                            @else
                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--navy-light));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.75rem;flex-shrink:0;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                            @endif
                            <span style="font-weight:600;font-size:.875rem;">{{ $u->name }}</span>
                        </div>
                    </td>
                    <td style="font-size:.83rem;color:var(--text-muted);">{{ $u->email }}</td>
                    <td style="font-size:.83rem;">{{ $u->phone ?? '—' }}</td>
                    <td>
                        @if($u->user_type === 'Admin')
                            <span class="badge-pill badge-admin">Admin</span>
                        @elseif($u->user_type === 'JobOwner')
                            <span class="badge-pill badge-owner">Job Owner</span>
                        @else
                            <span class="badge-pill badge-seeker">Job Seeker</span>
                        @endif
                    </td>
                    <td style="font-size:.76rem;color:var(--text-muted);">
                        {{ $u->created_at->format('M d, Y') }}
                    </td>
                    <td>
                        <div style="display:flex;gap:.4rem;align-items:center;">
                            <a href="{{ route('users.show', $u->id) }}" class="btn-ghost"
                               title="Inspect">
                                <i class="bi bi-eye"></i> Inspect
                            </a>
                            @if($u->id !== Auth::id())
                                <form method="POST" action="{{ route('users.destroy', $u->id) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($u->name) }}? This action cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-admin" title="Delete">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:3rem;color:var(--text-muted);">
                        <i class="bi bi-people" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($users->hasPages())
    <div style="padding:1rem 1.4rem;border-top:1px solid var(--border-light);display:flex;justify-content:flex-end;">
        {{ $users->links() }}
    </div>
    @endif
</div>

@endsection