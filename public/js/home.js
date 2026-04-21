// ===== LANGUAGE SYSTEM =====
let currentLang = localStorage.getItem('ajeer_lang') || 'en';

function applyLanguage(lang) {
    const html = document.getElementById('htmlRoot');
    if (!html) return;

    const bootstrapCSS = document.getElementById('bootstrapCSS');
    const langBtnText  = document.getElementById('langBtnText');
    const metaDesc     = document.querySelector('meta[name="description"]');

    if (lang === 'ar') {
        html.setAttribute('lang', 'ar');
        html.setAttribute('dir', 'rtl');
        if (bootstrapCSS) bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css';
        if (langBtnText)  langBtnText.textContent = 'English';
        if (metaDesc)     metaDesc.content = 'منصة AJEER للوظائف المرنة في الأردن - نربط أصحاب الأعمال بالباحثين عن عمل مرن';
        document.title = 'AJEER - منصة الوظائف المرنة في الأردن';
    } else {
        html.setAttribute('lang', 'en');
        html.setAttribute('dir', 'ltr');
        if (bootstrapCSS) bootstrapCSS.href = 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css';
        if (langBtnText)  langBtnText.textContent = 'عربي';
        if (metaDesc)     metaDesc.content = 'AJEER is Jordan\'s first flexible jobs platform connecting businesses with job seekers for hourly and part-time work.';
        document.title = 'AJEER - Flexible Jobs Platform in Jordan';
    }

    document.querySelectorAll('[data-en][data-ar]').forEach(el => {
        const text = lang === 'ar'
            ? el.getAttribute('data-ar')
            : el.getAttribute('data-en');

        if (!text) return;

        const icon = el.querySelector('i');
        if (icon) {
            // Update existing text node in-place — never rewrite innerHTML
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
        } else {
            el.textContent = text;
        }
    });

    localStorage.setItem('ajeer_lang', lang);
    currentLang = lang;
}

function toggleLanguage() {
    applyLanguage(currentLang === 'en' ? 'ar' : 'en');
}

// ===== DOM READY =====
document.addEventListener('DOMContentLoaded', function () {

    applyLanguage(currentLang);

    // ===== NAVBAR SCROLL + BACK TO TOP =====
    // ❌ Your version had window.addEventListener('scroll') nested INSIDE
    // another scroll listener — this stacked hundreds of listeners as you scrolled.
    // ✅ Only one scroll listener registered once here:
    window.addEventListener('scroll', function () {
        document.querySelector('.navbar')?.classList.toggle('scrolled', window.scrollY > 50);
        document.getElementById('backToTop')?.classList.toggle('show', window.scrollY > 300);
    });

    // ===== BACK TO TOP =====
    document.getElementById('backToTop')?.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    // ===== SMOOTH SCROLL =====
    document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // ===== SCROLL REVEAL ANIMATIONS =====
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.12,
        rootMargin: '0px 0px -40px 0px'
    });

    document.querySelectorAll('.reveal').forEach(el => {
        revealObserver.observe(el);
    });

});