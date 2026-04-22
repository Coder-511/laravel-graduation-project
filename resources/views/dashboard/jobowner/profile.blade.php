@extends('layouts.jobowner')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('content')

<div class="page-header fade-in-up">
    <h1>My Profile</h1>
    <p>Update your personal information and password.</p>
</div>

<div class="row g-3 justify-content-center">
    <div class="col-lg-8 fade-in-up delay-1">
        <div class="page-card">
            <div class="page-card-header">
                <h6 class="page-card-title">
                    <i class="bi bi-person-fill"></i> Profile Information
                </h6>
            </div>
            <div class="page-card-body">

                <div style="display:flex;align-items:center;gap:1.25rem;
                            margin-bottom:1.75rem;padding-bottom:1.5rem;
                            border-bottom:1px solid var(--border-light);">
                    <div id="avatarPreviewWrap">
                        @if($user->profile_picture)
                            <img id="avatarPreview"
                                 src="{{ asset('storage/' . $user->profile_picture) }}"
                                 alt="{{ $user->name }}"
                                 style="width:80px;height:80px;border-radius:50%;
                                        object-fit:cover;
                                        border:3px solid var(--border-light);">
                        @else
                            <div style="width:80px;height:80px;border-radius:50%;
                                        background:linear-gradient(135deg,var(--primary),var(--navy-light));
                                        display:flex;align-items:center;
                                        justify-content:center;color:#fff;
                                        font-size:2rem;font-weight:700;
                                        border:3px solid var(--border-light);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div>
                        <div style="font-size:1.05rem;font-weight:700;
                                    color:var(--dark);">
                            {{ $user->name }}
                        </div>
                        <div style="font-size:.82rem;color:var(--text-muted);">
                            {{ $user->email }}
                        </div>
                        <span class="badge-pill badge-owner" style="margin-top:.4rem;">
                            Job Owner
                        </span>
                    </div>
                </div>

                <form method="POST"
                      action="{{ route('profile.update', $user->id) }}"
                      enctype="multipart/form-data">
                    @csrf @method('PATCH')

                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Full Name</label>
                                <input type="text" name="name"
                                       class="form-control-admin {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name', $user->name) }}"
                                       placeholder="Your full name">
                                @error('name')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Phone</label>
                                <input type="tel" name="phone"
                                       class="form-control-admin {{ $errors->has('phone') ? 'is-invalid' : '' }}"
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="+962 ...">
                                @error('phone')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group-admin">
                                <label class="form-label-admin">Profile Picture</label>
                                <input type="file" name="profile_picture"
                                       id="profilePicInput"
                                       class="form-control-admin {{ $errors->has('profile_picture') ? 'is-invalid' : '' }}"
                                       accept="image/jpeg,image/png,image/jpg,image/webp">
                                <div style="font-size:.73rem;color:var(--text-muted);
                                            margin-top:.3rem;">
                                    JPEG, PNG, WEBP — max 2MB
                                </div>
                                @error('profile_picture')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div style="border-top:1px solid var(--border-light);
                                        padding-top:1.25rem;margin-top:.25rem;">
                                <div style="font-size:.78rem;font-weight:700;
                                            text-transform:uppercase;
                                            letter-spacing:.08em;
                                            color:var(--text-muted);
                                            margin-bottom:1rem;">
                                    Change Password
                                    <span style="font-weight:400;">
                                        (leave blank to keep current)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">New Password</label>
                                <div style="position:relative;">
                                    <input type="password" name="password"
                                           id="pwField"
                                           class="form-control-admin {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                           placeholder="Min 8 characters">
                                    <button type="button"
                                            onclick="togglePw('pwField','pwEye')"
                                            style="position:absolute;right:10px;top:50%;
                                                   transform:translateY(-50%);
                                                   background:none;border:none;
                                                   color:var(--text-muted);cursor:pointer;">
                                        <i class="bi bi-eye" id="pwEye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback-admin">
                                        <i class="bi bi-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group-admin">
                                <label class="form-label-admin">
                                    Confirm New Password
                                </label>
                                <div style="position:relative;">
                                    <input type="password"
                                           name="password_confirmation"
                                           id="pwConfField"
                                           class="form-control-admin"
                                           placeholder="Repeat new password">
                                    <button type="button"
                                            onclick="togglePw('pwConfField','pwConfEye')"
                                            style="position:absolute;right:10px;top:50%;
                                                   transform:translateY(-50%);
                                                   background:none;border:none;
                                                   color:var(--text-muted);cursor:pointer;">
                                        <i class="bi bi-eye" id="pwConfEye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div style="margin-top:1.5rem;display:flex;gap:.75rem;
                                justify-content:flex-end;">
                        <a href="{{ route('dashboard.jobowner') }}"
                           class="btn-ghost">Cancel</a>
                        <button type="submit" class="btn-primary-admin">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('profilePicInput').addEventListener('change', function() {
    if (!this.files || !this.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('avatarPreviewWrap').innerHTML =
            `<img src="${e.target.result}"
                  style="width:80px;height:80px;border-radius:50%;
                         object-fit:cover;border:3px solid var(--border-light);">`;
    };
    reader.readAsDataURL(this.files[0]);
});

function togglePw(fieldId, iconId) {
    const f = document.getElementById(fieldId);
    const i = document.getElementById(iconId);
    if (f.type === 'password') {
        f.type = 'text';
        i.className = 'bi bi-eye-slash';
    } else {
        f.type = 'password';
        i.className = 'bi bi-eye';
    }
}
</script>
@endpush