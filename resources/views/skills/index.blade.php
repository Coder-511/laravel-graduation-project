@extends('layouts.admin')

@section('title', 'Skills')
@section('page-title', 'Skills')

@section('content')

<div class="page-header fade-in-up">
    <h1>Skills</h1>
    <p>Manage the skill tags that job owners and seekers can use on the platform.</p>
</div>

<div class="row g-3">

    {{-- Add Skill --}}
    <div class="col-lg-4 fade-in-up delay-1">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-plus-circle-fill"></i> Add New Skill
                </h6>
            </div>
            <div class="page-card-body">
                <form method="POST" action="{{ route('skills.store') }}">
                    @csrf
                    <div class="form-group-admin">
                        <label class="form-label-admin">Skill Name *</label>
                        <input type="text" name="skill_name"
                               class="form-control-admin {{ $errors->has('skill_name') ? 'is-invalid' : '' }}"
                               value="{{ old('skill_name') }}"
                               placeholder="e.g. JavaScript, Welding, Photoshop..."
                               maxlength="100" required>
                        @error('skill_name')
                            <div class="invalid-feedback-admin">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <button type="submit" class="btn-primary-admin" style="width:100%;justify-content:center;">
                        <i class="bi bi-plus-lg"></i> Add Skill
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Skills list --}}
    <div class="col-lg-8 fade-in-up delay-2">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-stars"></i>
                    All Skills
                    <span class="badge-pill badge-admin" style="margin-left:.4rem;">{{ count($skills) }}</span>
                </h6>
                <form method="GET" action="{{ route('skills.index') }}" style="display:flex;gap:.5rem;">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" name="search" class="search-input"
                               placeholder="Search skills..." value="{{ $search ?? '' }}"
                               style="min-width:180px;">
                    </div>
                    <button type="submit" class="btn-ghost">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>

            @if($skills->isEmpty())
                <div style="padding:3rem;text-align:center;color:var(--text-muted);">
                    <i class="bi bi-stars" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
                    No skills found. Add your first skill!
                </div>
            @else
            <div style="overflow-x:auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Skill Name</th>
                            <th>Used by Jobs</th>
                            <th>Used by Seekers</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($skills as $skill)
                        <tr>
                            <td>
                                <span style="font-weight:600;font-size:.875rem;">{{ $skill->skill_name }}</span>
                            </td>
                            <td>
                                <span class="badge-pill badge-owner">{{ $skill->jobs->count() }} jobs</span>
                            </td>
                            <td>
                                <span class="badge-pill badge-seeker">{{ $skill->users->count() }} seekers</span>
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;align-items:center;">

                                    {{-- Edit inline --}}
                                    <button class="btn-ghost" style="padding:.35rem .6rem;"
                                            onclick="openEdit({{ $skill->skill_id }}, '{{ addslashes($skill->skill_name) }}')"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    {{-- Delete --}}
                                    <form method="POST" action="{{ route('skills.destroy', $skill->skill_id) }}"
                                          onsubmit="return confirm('Delete \'{{ addslashes($skill->skill_name) }}\'? It will be removed from all jobs and seekers.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger-admin"
                                                style="padding:.35rem .6rem;" title="Delete">
                                            <i class="bi bi-trash3"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

</div>

{{-- Edit Skill Modal --}}
<div class="modal fade" id="editSkillModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
        <div class="modal-content" style="border:none;border-radius:var(--radius);overflow:hidden;">
            <div class="modal-header" style="background:var(--dark-2);border:none;padding:1.1rem 1.4rem;">
                <h5 class="modal-title" style="color:#fff;font-weight:700;font-size:.95rem;">
                    <i class="bi bi-pencil-fill" style="color:var(--primary);margin-right:.4rem;"></i>
                    Edit Skill
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="editSkillForm" action="">
                @csrf @method('PATCH')
                <div class="modal-body" style="padding:1.4rem;">
                    <div class="form-group-admin">
                        <label class="form-label-admin">Skill Name *</label>
                        <input type="text" name="skill_name" id="editSkillName"
                               class="form-control-admin" maxlength="100" required>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid var(--border-light);padding:1rem 1.4rem;">
                    <button type="button" class="btn-ghost" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-admin">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openEdit(skillId, skillName) {
    document.getElementById('editSkillName').value = skillName;
    document.getElementById('editSkillForm').action = `/admin/skills/${skillId}`;
    new bootstrap.Modal(document.getElementById('editSkillModal')).show();
}
</script>
@endpush