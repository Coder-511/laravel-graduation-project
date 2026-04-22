<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <title>Welcome — {{ config('app.name', 'AJEER') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/jobseeker.css') }}">
</head>
<body>

<div class="sn-ob-wrap">
    <div class="sn-ob-card">

        <div class="sn-ob-top">
            <div class="sn-ob-logo">
                <i class="bi bi-briefcase-fill"></i>
            </div>
            <div style="font-size:1.05rem;font-weight:800;color:var(--dark);">
                Welcome to {{ config('app.name', 'AJEER') }}!
            </div>
            <div style="font-size:.82rem;color:var(--muted);margin-top:.25rem;">
                Let's set up your profile in 2 quick steps.
            </div>

            {{-- Progress --}}
            <div class="sn-ob-progress" id="progressBar">
                <div class="sn-ob-dot active" id="dot1">1</div>
                <div class="sn-ob-line" id="line1"></div>
                <div class="sn-ob-dot" id="dot2">2</div>
            </div>
        </div>

        <div class="sn-ob-body">

            @if($errors->any())
                <div class="sn-alert sn-alert-error" style="margin-bottom:1rem;">
                    <i class="bi bi-exclamation-circle-fill"></i>
                    <div>
                        @foreach($errors->all() as $e)
                            <div>{{ $e }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('jobseeker.onboarding.complete') }}"
                  id="onboardingForm">
                @csrf

                {{-- Step 1: City ──────────────────────────────────── --}}
                <div class="sn-ob-step active" id="step1">
                    <div class="sn-ob-step-title">📍 Where are you based?</div>
                    <div class="sn-ob-step-sub">
                        This helps job owners find candidates near them.
                    </div>

                    <div class="sn-form-group">
                        <label class="sn-form-label">Your City *</label>
                        <input type="text" name="city" id="cityInput"
                               class="sn-form-control"
                               placeholder="e.g. Amman, Zarqa, Irbid..."
                               value="{{ old('city') }}"
                               autocomplete="off"
                               required>
                    </div>

                    <div class="sn-ob-footer">
                        <button type="button" class="sn-btn-primary"
                                onclick="goStep(2)">
                            Next <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                </div>

                {{-- Step 2: Skills ────────────────────────────────── --}}
                <div class="sn-ob-step" id="step2">
                    <div class="sn-ob-step-title">⚡ What are your skills?</div>
                    <div class="sn-ob-step-sub">
                        Select at least one skill so job owners know what you can do.
                    </div>

                    <div class="sn-form-group">
                        <input type="text" id="skillSearchOb"
                               class="sn-form-control"
                               placeholder="🔍 Filter skills..."
                               oninput="filterObSkills()"
                               style="margin-bottom:.6rem;">

                        <div class="sn-chip-grid" id="obChipGrid">
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

                        <div id="obSkillCount"
                             style="font-size:.72rem;color:var(--muted);margin-top:.35rem;">
                            {{ count($selectedSkillIds) }} skills selected
                        </div>
                    </div>

                    <div class="sn-ob-footer">
                        <button type="button" class="sn-btn-ghost"
                                onclick="goStep(1)">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                        <button type="submit" class="sn-btn-primary">
                            <i class="bi bi-check-lg"></i> Let's Go!
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
let currentStep = 1;

// Init chip state
document.querySelectorAll('#obChipGrid .sn-chip').forEach(chip => {
    chip.addEventListener('click', () => {
        const cb = chip.querySelector('input[type="checkbox"]');
        cb.checked = !cb.checked;
        chip.classList.toggle('selected', cb.checked);
        updateObCount();
    });
});

function updateObCount() {
    const n = document.querySelectorAll('#obChipGrid .sn-chip.selected').length;
    document.getElementById('obSkillCount').textContent =
        n === 0 ? '0 skills selected'
                : `${n} skill${n > 1 ? 's' : ''} selected`;
}

function filterObSkills() {
    const q = document.getElementById('skillSearchOb').value.toLowerCase();
    document.querySelectorAll('#obChipGrid .sn-chip').forEach(chip => {
        chip.style.display = chip.dataset.name.includes(q) ? '' : 'none';
    });
}

function goStep(step) {
    // Validate city before going to step 2
    if (step === 2) {
        const city = document.getElementById('cityInput').value.trim();
        if (!city) {
            document.getElementById('cityInput').focus();
            document.getElementById('cityInput').style.borderColor = 'var(--danger)';
            document.getElementById('cityInput').style.boxShadow = '0 0 0 3px rgba(231,76,60,.1)';
            return;
        }
        document.getElementById('cityInput').style.borderColor = '';
        document.getElementById('cityInput').style.boxShadow = '';
    }

    document.getElementById(`step${currentStep}`).classList.remove('active');
    currentStep = step;
    document.getElementById(`step${step}`).classList.add('active');

    // Update progress dots
    for (let i = 1; i <= 2; i++) {
        const dot  = document.getElementById(`dot${i}`);
        const line = document.getElementById(`line${i}`);

        if (i < step) {
            dot.className = 'sn-ob-dot done';
            dot.innerHTML = '<i class="bi bi-check-lg"></i>';
            if (line) line.classList.add('done');
        } else if (i === step) {
            dot.className = 'sn-ob-dot active';
            dot.textContent = i;
        } else {
            dot.className = 'sn-ob-dot';
            dot.textContent = i;
            if (line) line.classList.remove('done');
        }
    }
}

// Re-open on step 2 if there were validation errors and skills were missing
@if($errors->has('skill_ids') || $errors->has('skill_ids.*'))
    document.addEventListener('DOMContentLoaded', () => goStep(2));
@endif
</script>

</body>
</html>