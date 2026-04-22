@extends('layouts.jobseeker')

@section('title', 'My Profile')

@section('content')

<div class="sn-page-header sn-fade">
    <h1>My Profile</h1>
    <p>Manage your personal info, skills and availability.</p>
</div>

<div class="row g-3">

    {{-- Left: Personal Info --}}
    <div class="col-lg-5 sn-fade sn-d1">

        {{-- Avatar + basic info --}}
        <div class="sn-card" style="margin-bottom:1rem;">
            <div class="sn-card-body" style="text-align:center;padding:1.75rem 1.35rem;">
                <div id="avatarPreviewWrap" style="margin-bottom:1rem;">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}"
                             alt="{{ $user->name }}"
                             style="width:90px;height:90px;border-radius:50%;
                                    object-fit:cover;
                                    border:3px solid var(--border);">
                    @else
                        <div style="width:90px;height:90px;border-radius:50%;
                                    background:linear-gradient(135deg,var(--primary),#34495e);
                                    display:flex;align-items:center;justify-content:center;
                                    color:#fff;font-size:2.2rem;font-weight:700;
                                    margin:0 auto;border:3px solid var(--border);">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div style="font-size:1.1rem;font-weight:800;color:var(--dark);">
                    {{ $user->name }}
                </div>
                <div style="font-size:.82rem;color:var(--muted);margin:.2rem 0 .5rem;">
                    {{ $user->email }}
                </div>
                @if($profile?->city)
                    <div style="font-size:.78rem;color:var(--primary);font-weight:600;">
                        <i class="bi bi-geo-alt-fill"></i> {{ $profile->city }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Personal info form --}}
        <div class="sn-card">
            <div class="sn-card-header">
                <div class="sn-card-title">
                    <i class="bi bi-person-fill"></i> Personal Info
                </div>
            </div>
            <div class="sn-card-body">
                <form method="POST"
                      action="{{ route('profile.update', $user->id) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="sn-form-group">
                        <label class="sn-form-label">Full Name</label>
                        <input type="text" name="name" class="sn-form-control"
                               value="{{ old('name', $user->name) }}"
                               placeholder="Your full name">
                        @error('name')
                            <div class="sn-invalid">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Phone</label>
                        <input type="tel" name="phone" class="sn-form-control"
                               value="{{ old('phone', $user->phone) }}"
                               placeholder="+962 ...">
                        @error('phone')
                            <div class="sn-invalid">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Profile Picture</label>
                        <input type="file" name="profile_picture"
                               id="profilePicInput" class="sn-form-control"
                               accept="image/jpeg,image/png,image/jpg,image/webp">
                        <div style="font-size:.71rem;color:var(--muted);margin-top:.28rem;">
                            JPEG, PNG, WEBP — max 2MB
                        </div>
                        @error('profile_picture')
                            <div class="sn-invalid">
                                <i class="bi bi-exclamation-circle"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div style="border-top:1px solid var(--border);padding-top:1rem;
                                margin-bottom:1rem;">
                        <div style="font-size:.71rem;font-weight:700;text-transform:uppercase;
                                    letter-spacing:.08em;color:var(--muted);margin-bottom:.9rem;">
                            Change Password
                            <span style="font-weight:400;text-transform:none;letter-spacing:0;">
                                (leave blank to keep current)
                            </span>
                        </div>
                        <div class="sn-form-group">
                            <label class="sn-form-label">New Password</label>
                            <div style="position:relative;">
                                <input type="password" name="password"
                                       id="pwField" class="sn-form-control"
                                       placeholder="Min 8 characters">
                                <button type="button"
                                        onclick="togglePw('pwField','pwEye')"
                                        style="position:absolute;right:10px;top:50%;
                                               transform:translateY(-50%);background:none;
                                               border:none;color:var(--muted);cursor:pointer;">
                                    <i class="bi bi-eye" id="pwEye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="sn-invalid">
                                    <i class="bi bi-exclamation-circle"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="sn-form-group">
                            <label class="sn-form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation"
                                   class="sn-form-control"
                                   placeholder="Repeat new password">
                        </div>
                    </div>

                    <button type="submit" class="sn-btn-primary"
                            style="width:100%;justify-content:center;">
                        <i class="bi bi-check-lg"></i> Save Changes
                    </button>

                </form>
            </div>
        </div>

    </div>

    {{-- Right column --}}
    <div class="col-lg-7">

        {{-- City --}}
        <div class="sn-card sn-fade sn-d2" style="margin-bottom:1rem;">
            <div class="sn-card-header">
                <div class="sn-card-title">
                    <i class="bi bi-geo-alt-fill"></i> Location
                </div>
            </div>
            <div class="sn-card-body">
                <form method="POST" action="{{ route('skills.sync') }}"
                      id="cityForm">
                    @csrf
                    {{-- We'll handle city via a dedicated update --}}
                </form>
                <form method="POST" action="{{ route('profile.update', $user->id) }}">
                    @csrf @method('PATCH')
                    <div style="display:flex;gap:.65rem;align-items:flex-end;">
                        <div style="flex:1;" class="sn-form-group" style="margin-bottom:0;">
                            <input type="text" name="name"
                                   class="sn-form-control" style="display:none;">
                            {{-- We use a hidden trick: post city via profile update --}}
                        </div>
                    </div>
                </form>

                {{-- Standalone city form --}}
                <form method="POST" action="{{ route('jobseeker.onboarding.complete') }}">
                    @csrf
                    {{-- Reuse onboarding complete but only update city --}}
                </form>

                {{-- Simple city + skills sync form --}}
                <form method="POST" action="{{ route('jobseeker.onboarding.complete') }}"
                      id="profileUpdateForm">
                    @csrf
                    <div class="sn-form-group" style="margin-bottom:0;">
                        <label class="sn-form-label">Your City</label>
                        <div style="display:flex;gap:.6rem;">
                            <input type="text" name="city"
                                   class="sn-form-control"
                                   value="{{ old('city', $profile?->city) }}"
                                   placeholder="e.g. Amman"
                                   required>
                            {{-- carry existing skills so they don't get wiped --}}
                            @foreach($selectedSkillIds as $sid)
                                <input type="hidden" name="skill_ids[]"
                                       value="{{ $sid }}">
                            @endforeach
                            <button type="submit" class="sn-btn-primary"
                                    style="white-space:nowrap;">
                                <i class="bi bi-check-lg"></i> Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Skills --}}
        <div class="sn-card sn-fade sn-d3" style="margin-bottom:1rem;">
            <div class="sn-card-header">
                <div class="sn-card-title">
                    <i class="bi bi-stars"></i> My Skills
                </div>
                <span style="font-size:.75rem;color:var(--muted);">
                    {{ count($selectedSkillIds) }} selected
                </span>
            </div>
            <div class="sn-card-body">
                <form method="POST" action="{{ route('skills.sync') }}">
                    @csrf

                    <input type="text" id="skillSearchProfile"
                           class="sn-form-control"
                           placeholder="🔍 Filter skills..."
                           oninput="filterProfileSkills()"
                           style="margin-bottom:.65rem;">

                    <div class="sn-chip-grid" id="profileChipGrid">
                        @foreach($skills as $skill)
                            <label class="sn-chip {{ in_array($skill->skill_id, $selectedSkillIds) ? 'selected' : '' }}"
                                   data-name="{{ strtolower($skill->skill_name) }}">
                                <input type="checkbox"
                                       name="skill_ids[]"
                                       value="{{ $skill->skill_id }}"
                                       {{ in_array($skill->skill_id, $selectedSkillIds) ? 'checked' : '' }}
                                       hidden>
                                <span>{{ $skill->skill_name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div style="display:flex;align-items:center;
                                justify-content:space-between;margin-top:.65rem;">
                        <div id="profileSkillCount"
                             style="font-size:.72rem;color:var(--muted);">
                            {{ count($selectedSkillIds) }} skill{{ count($selectedSkillIds) !== 1 ? 's' : '' }} selected
                        </div>
                        <button type="submit" class="sn-btn-primary">
                            <i class="bi bi-check-lg"></i> Update Skills
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- Availability --}}
        <div class="sn-card sn-fade sn-d4">
            <div class="sn-card-header">
                <div class="sn-card-title">
                    <i class="bi bi-calendar-check-fill"></i> My Availability
                </div>
                <span style="font-size:.75rem;color:var(--muted);">
                    {{ $availabilities->count() }} slot(s)
                </span>
            </div>
            <div class="sn-card-body">

                {{-- Add new slot --}}
                <form method="POST" action="{{ route('availabilities.store') }}"
                      style="margin-bottom:1.25rem;padding-bottom:1.25rem;
                             border-bottom:1px solid var(--border);">
                    @csrf
                    <div class="row g-2">
                        <div class="col-7">
                            <div class="sn-form-group" style="margin-bottom:0;">
                                <label class="sn-form-label">Date *</label>
                                <input type="date" name="available_date"
                                       class="sn-form-control"
                                       min="{{ date('Y-m-d') }}"
                                       required>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="sn-form-group" style="margin-bottom:0;">
                                <label class="sn-form-label">Time *</label>
                                <input type="time" name="available_time"
                                       class="sn-form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="sn-btn-primary"
                            style="margin-top:.75rem;width:100%;justify-content:center;">
                        <i class="bi bi-plus-lg"></i> Add Slot
                    </button>
                </form>

                {{-- Existing slots --}}
                @if($availabilities->isEmpty())
                    <div style="text-align:center;padding:1.5rem;
                                color:var(--muted);font-size:.83rem;">
                        <i class="bi bi-calendar-x"
                           style="font-size:1.75rem;display:block;
                                  margin-bottom:.4rem;opacity:.3;"></i>
                        No availability slots yet.
                    </div>
                @else
                    @foreach($availabilities as $avail)
                    <div class="sn-avail-item">
                        <div class="sn-avail-icon">
                            <i class="bi bi-clock-fill"></i>
                        </div>
                        <div style="flex:1;">
                            <div class="sn-avail-text">
                                {{ \Carbon\Carbon::parse($avail->available_date)->format('D, M d Y') }}
                            </div>
                            <div class="sn-avail-sub">
                                {{ \Carbon\Carbon::parse($avail->available_time)->format('g:i A') }}
                            </div>
                        </div>
                        <form method="POST"
                              action="{{ route('availabilities.destroy', $avail->availability_id) }}"
                              onsubmit="return confirm('Remove this slot?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="sn-btn-danger"
                                    style="padding:.3rem .6rem;font-size:.72rem;">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                @endif

            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
// Avatar preview
document.getElementById('profilePicInput').addEventListener('change', function() {
    if (!this.files || !this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('avatarPreviewWrap').innerHTML =
            `<img src="${e.target.result}"
                  style="width:90px;height:90px;border-radius:50%;
                         object-fit:cover;border:3px solid var(--border);">`;
    };
    reader.readAsDataURL(this.files[0]);
});

// Password toggle
function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') { f.type = 'text'; i.className = 'bi bi-eye-slash'; }
    else { f.type = 'password'; i.className = 'bi bi-eye'; }
}

// Skill chips
document.querySelectorAll('#profileChipGrid .sn-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        const cb = chip.querySelector('input[type="checkbox"]');
        cb.checked = !cb.checked;
        chip.classList.toggle('selected', cb.checked);
        const n = document.querySelectorAll('#profileChipGrid .sn-chip.selected').length;
        document.getElementById('profileSkillCount').textContent =
            `${n} skill${n !== 1 ? 's' : ''} selected`;
    });
});

function filterProfileSkills() {
    const q = document.getElementById('skillSearchProfile').value.toLowerCase();
    document.querySelectorAll('#profileChipGrid .sn-chip').forEach(chip => {
        chip.style.display = chip.dataset.name.includes(q) ? '' : 'none';
    });
}
</script>
@endpush