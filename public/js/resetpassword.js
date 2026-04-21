document.addEventListener('DOMContentLoaded', function () {

    const pwInput       = document.getElementById('password');
    const pwConfirm     = document.getElementById('password_confirmation');
    const strengthBar   = document.getElementById('strengthBar');
    const strengthLabel = document.getElementById('strengthLabel');
    const strengthWrap  = document.getElementById('strengthWrap');

    if (!pwInput || !strengthBar || !strengthLabel || !strengthWrap) return;

    // ── Match indicator — reuse blade element if present, else inject ──
    let matchIndicator = document.getElementById('matchIndicator');
    if (!matchIndicator && pwConfirm) {
        matchIndicator = document.createElement('div');
        matchIndicator.id = 'matchIndicator';
        matchIndicator.style.cssText = 'display:none;font-size:.78rem;margin-top:.35rem;align-items:center;gap:.3rem;';
        const group = pwConfirm.closest('.form-group-custom');
        if (group) group.appendChild(matchIndicator);
    }

    // ── Read language live ───────────────────────────────────────
    function isArabic() {
        return document.documentElement.getAttribute('lang') === 'ar';
    }

    // ── Strength levels ──────────────────────────────────────────
    const LEVELS = {
        en: [
            { color: '#e55353', label: 'Too weak',    width: '20%'  },
            { color: '#f59e0b', label: 'Weak',        width: '40%'  },
            { color: '#f59e0b', label: 'Fair',        width: '60%'  },
            { color: '#22c55e', label: 'Strong',      width: '80%'  },
            { color: '#16a34a', label: 'Very strong', width: '100%' },
        ],
        ar: [
            { color: '#e55353', label: 'ضعيفة جداً', width: '20%'  },
            { color: '#f59e0b', label: 'ضعيفة',      width: '40%'  },
            { color: '#f59e0b', label: 'متوسطة',     width: '60%'  },
            { color: '#22c55e', label: 'قوية',       width: '80%'  },
            { color: '#16a34a', label: 'قوية جداً',  width: '100%' },
        ],
    };

    function getStrength(pw) {
        let score = 0;
        if (pw.length >= 8)          score++;
        if (pw.length >= 12)         score++;
        if (/[A-Z]/.test(pw))        score++;
        if (/[0-9]/.test(pw))        score++;
        if (/[^A-Za-z0-9]/.test(pw)) score++;
        return Math.min(score, 4);
    }

    // ── Refresh strength label ───────────────────────────────────
    function refreshStrength() {
        const pw = pwInput.value;
        if (!pw) {
            strengthWrap.style.display = 'none';
            return;
        }
        const levels = isArabic() ? LEVELS.ar : LEVELS.en;
        const lvl = getStrength(pw);
        strengthWrap.style.display   = 'block';
        strengthBar.style.width      = levels[lvl].width;
        strengthBar.style.background = levels[lvl].color;
        strengthLabel.textContent    = levels[lvl].label;
        strengthLabel.style.color    = levels[lvl].color;
    }

    // ── Refresh match indicator ──────────────────────────────────
    function checkMatch() {
        if (!pwConfirm || !matchIndicator) return;
        if (!pwConfirm.value) {
            matchIndicator.style.display = 'none';
            pwConfirm.style.borderColor  = '';
            pwConfirm.style.boxShadow    = '';
            return;
        }

        const okText   = isArabic() ? 'كلمتا المرور متطابقتان'     : 'Passwords match';
        const failText = isArabic() ? 'كلمتا المرور غير متطابقتين' : 'Passwords do not match';

        matchIndicator.style.display = 'flex';

        if (pwInput.value === pwConfirm.value) {
            matchIndicator.innerHTML    = '<i class="bi bi-check-circle-fill"></i><span>' + okText + '</span>';
            matchIndicator.style.color  = '#22c55e';
            pwConfirm.style.borderColor = '#22c55e';
            pwConfirm.style.boxShadow   = '0 0 0 3px rgba(34,197,94,.12)';
        } else {
            matchIndicator.innerHTML    = '<i class="bi bi-x-circle-fill"></i><span>' + failText + '</span>';
            matchIndicator.style.color  = '#e55353';
            pwConfirm.style.borderColor = '#e55353';
            pwConfirm.style.boxShadow   = '0 0 0 3px rgba(229,83,83,.1)';
        }
    }

    // ── Listeners ────────────────────────────────────────────────
    pwInput.addEventListener('input', function () {
        refreshStrength();
        checkMatch();
    });

    if (pwConfirm) {
        pwConfirm.addEventListener('input', checkMatch);
    }

    // ── Re-render both when language switches ────────────────────
    new MutationObserver(function () {
        refreshStrength();
        checkMatch();
    }).observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['lang'],
    });

});