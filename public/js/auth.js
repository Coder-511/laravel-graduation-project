// ===== PASSWORD TOGGLE =====
window.togglePassword = function (btnId, inputId) {
    const input = document.getElementById(inputId);
    const btn   = document.getElementById(btnId);

    if (!input || !btn) return;

    const icon = btn.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        if (icon) icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        if (icon) icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
};

// ===== LANGUAGE SYSTEM =====
let authCurrentLang = localStorage.getItem('ajeer_lang') || 'en';

function applyAuthLanguage(lang) {
    const html = document.getElementById('htmlRoot');
    if (!html) return;

    const bootstrapCSS = document.getElementById('bootstrapCSS');
    const langBtnText  = document.getElementById('authLangBtnText');

    if (lang === 'ar') {
        html.setAttribute('lang', 'ar');
        html.setAttribute('dir', 'rtl');
        if (bootstrapCSS) bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css';
        if (langBtnText)  langBtnText.textContent = 'English';
    } else {
        html.setAttribute('lang', 'en');
        html.setAttribute('dir', 'ltr');
        if (bootstrapCSS) bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
        if (langBtnText)  langBtnText.textContent = 'عربي';
    }

    // ── Translate elements with data-en / data-ar ──────────
    document.querySelectorAll('[data-en][data-ar]').forEach(el => {
        const text = lang === 'ar'
            ? el.getAttribute('data-ar')
            : el.getAttribute('data-en');

        if (!text) return;

        const icon = el.querySelector('i');
        if (icon) {
            let textNode = null;
            el.childNodes.forEach(node => {
                if (node.nodeType === Node.TEXT_NODE && node.textContent.trim()) {
                    textNode = node;
                }
            });
            if (textNode) {
                textNode.textContent = ' ' + text;
            } else {
                icon.insertAdjacentText('afterend', ' ' + text);
            }
        } else if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
            el.setAttribute('placeholder', text);
        } else {
            el.textContent = text;
        }
    });

    // ── Translate placeholder-only elements ────────────────
    document.querySelectorAll('[data-placeholder-en][data-placeholder-ar]').forEach(el => {
        el.setAttribute(
            'placeholder',
            lang === 'ar'
                ? el.getAttribute('data-placeholder-ar')
                : el.getAttribute('data-placeholder-en')
        );
    });

    localStorage.setItem('ajeer_lang', lang);
    authCurrentLang = lang;
}

function toggleAuthLanguage() {
    applyAuthLanguage(authCurrentLang === 'en' ? 'ar' : 'en');
}

// ── Apply on page load ─────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    applyAuthLanguage(authCurrentLang);
});