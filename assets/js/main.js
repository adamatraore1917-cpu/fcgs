// FC Grande-Synthe — JavaScript principal

document.addEventListener('DOMContentLoaded', function () {

    // ==================== HAMBURGER MENU ====================
    const hamburger = document.getElementById('nav-hamburger');
    const navMenu   = document.getElementById('nav-menu');
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', function () {
            navMenu.classList.toggle('open');
        });
    }

    // ==================== COOKIE BANNER ====================
    const cookieBanner = document.getElementById('cookie-banner');
    if (cookieBanner) {
        if (localStorage.getItem('cookies_accepted')) {
            cookieBanner.classList.add('hidden');
        }
        const btnAccept = document.getElementById('cookie-accept');
        const btnRefuse = document.getElementById('cookie-refuse');
        if (btnAccept) {
            btnAccept.addEventListener('click', function () {
                localStorage.setItem('cookies_accepted', '1');
                cookieBanner.classList.add('hidden');
            });
        }
        if (btnRefuse) {
            btnRefuse.addEventListener('click', function () {
                localStorage.setItem('cookies_accepted', '0');
                cookieBanner.classList.add('hidden');
            });
        }
    }

    // ==================== CALENDAR NAV (HOME) ====================
    const calPrev = document.getElementById('cal-prev');
    const calNext = document.getElementById('cal-next');
    if (calPrev && calNext) {
        calPrev.addEventListener('click', function () {
            navigateCalendar(-1);
        });
        calNext.addEventListener('click', function () {
            navigateCalendar(1);
        });
    }

    function navigateCalendar(direction) {
        const url = new URL(window.location.href);
        let month = parseInt(url.searchParams.get('month') || new Date().getMonth() + 1);
        let year  = parseInt(url.searchParams.get('year')  || new Date().getFullYear());
        month += direction;
        if (month < 1)  { month = 12; year--; }
        if (month > 12) { month = 1;  year++; }
        url.searchParams.set('month', month);
        url.searchParams.set('year',  year);
        window.location.href = url.toString();
    }

    // ==================== RESERVATION CALENDAR ====================
    const reservationCells = document.querySelectorAll('.reservation-day-cell[data-date]');
    const dateDebutInput    = document.getElementById('date_debut');
    const dateFinInput      = document.getElementById('date_fin');
    if (reservationCells.length && dateDebutInput) {
        reservationCells.forEach(cell => {
            cell.addEventListener('click', function () {
                const date = this.getAttribute('data-date');
                dateDebutInput.value = date;
                if (dateFinInput) dateFinInput.value = date;
                reservationCells.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
        });
    }

    // ==================== CHAR COUNTER ====================
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(ta => {
        const counter = ta.nextElementSibling;
        if (counter && counter.classList.contains('char-counter')) {
            ta.addEventListener('input', function () {
                counter.textContent = this.value.length + '/' + this.getAttribute('maxlength');
            });
        }
    });

    // ==================== MODAL CONTACTS ====================
    const btnPro   = document.getElementById('btn-contact-pro');
    const btnClub  = document.getElementById('btn-contact-club');
    const modalPro = document.getElementById('modal-contact-pro');
    const modalClub= document.getElementById('modal-contact-club');
    const closeButtons = document.querySelectorAll('.modal-close');

    function openModal(modal) {
        if (modal) modal.classList.add('active');
    }
    function closeAllModals() {
        document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('active'));
    }

    if (btnPro)  btnPro.addEventListener('click',  () => openModal(modalPro));
    if (btnClub) btnClub.addEventListener('click',  () => openModal(modalClub));
    closeButtons.forEach(btn => btn.addEventListener('click', closeAllModals));
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', function (e) {
            if (e.target === this) closeAllModals();
        });
    });

    // ==================== FILE UPLOAD PREVIEW ====================
    const fileInputs = document.querySelectorAll('input[type="file"][data-preview]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const previewId = this.getAttribute('data-preview');
            const preview   = document.getElementById(previewId);
            if (preview && this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    });

    // ==================== ID CARD CLICK ====================
    const idCardAreas = document.querySelectorAll('.id-card-area[data-input]');
    idCardAreas.forEach(area => {
        area.addEventListener('click', function () {
            const inputId = this.getAttribute('data-input');
            const input   = document.getElementById(inputId);
            if (input) input.click();
        });
    });

    // ==================== LICENSE SELECTOR ====================
    const licenseRows = document.querySelectorAll('.license-row');
    const licenseInput = document.getElementById('type_license');
    licenseRows.forEach(row => {
        const btn = row.querySelector('.btn-choisir');
        if (btn) {
            btn.addEventListener('click', function () {
                licenseRows.forEach(r => r.classList.remove('selected'));
                row.classList.add('selected');
                if (licenseInput) licenseInput.value = row.getAttribute('data-license');
                btn.textContent = 'CHOISI';
            });
        }
    });

    // ==================== ADMIN GALLERY UPLOAD ====================
    const gallerySlots = document.querySelectorAll('.admin-gallery-slot');
    gallerySlots.forEach(slot => {
        slot.addEventListener('click', function () {
            const slotNum = this.getAttribute('data-slot');
            const form    = document.getElementById('gallery-upload-form');
            const slotField = document.getElementById('gallery-slot-number');
            const fileField = document.getElementById('gallery-file');
            if (form && slotField && fileField) {
                slotField.value = slotNum;
                fileField.click();
            }
        });
    });

    const galleryFile = document.getElementById('gallery-file');
    if (galleryFile) {
        galleryFile.addEventListener('change', function () {
            if (this.files && this.files[0]) {
                document.getElementById('gallery-upload-form').submit();
            }
        });
    }

    // ==================== ADMIN SEARCH ====================
    const searchAdmin = document.getElementById('search-admin');
    if (searchAdmin) {
        searchAdmin.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.account-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(q) ? '' : 'none';
            });
        });
    }

    // ==================== FORM VALIDATION ====================
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function (e) {
            const email = this.querySelector('[name="email"]').value.trim();
            const pass  = this.querySelector('[name="password"]').value;
            if (!email || !pass) {
                e.preventDefault();
                showError(this, 'Veuillez remplir tous les champs.');
            }
        });
    }

    // ==================== SIGNUP FORM REAL-TIME VALIDATION ====================
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {

        // --- Block digits in text-only fields (nom, prenom, ville) ---
        const textOnlyFields = ['nom', 'prenom', 'ville'];
        textOnlyFields.forEach(function(fieldName) {
            const el = document.getElementById(fieldName);
            if (!el) return;

            el.addEventListener('keypress', function(e) {
                // Allow: letters (including accented), spaces, hyphens, apostrophes, control keys
                const char = e.key;
                if (char.length === 1 && /[\d]/.test(char)) {
                    e.preventDefault();
                    showFieldError(el, 'Ce champ n\'accepte pas de chiffres.');
                }
            });

            el.addEventListener('input', function() {
                // Remove any digit that may have been pasted
                const cleaned = this.value.replace(/\d/g, '');
                if (cleaned !== this.value) {
                    this.value = cleaned;
                    showFieldError(el, 'Ce champ n\'accepte pas de chiffres.');
                } else {
                    clearFieldError(el);
                }
            });
        });

        // --- Block letters in code_postal ---
        const cpField = document.getElementById('code_postal');
        if (cpField) {
            cpField.addEventListener('keypress', function(e) {
                const char = e.key;
                if (char.length === 1 && !/\d/.test(char)) {
                    e.preventDefault();
                    showFieldError(cpField, 'Le code postal ne doit contenir que des chiffres.');
                }
            });
            cpField.addEventListener('input', function() {
                const cleaned = this.value.replace(/\D/g, '');
                if (cleaned !== this.value) {
                    this.value = cleaned;
                    showFieldError(cpField, 'Le code postal ne doit contenir que des chiffres.');
                } else {
                    clearFieldError(cpField);
                }
            });
        }

        // --- Block non-numeric in frais_annexes and cotisations ---
        ['frais_annexes', 'cotisations'].forEach(function(fieldName) {
            const el = document.getElementById(fieldName);
            if (!el) return;
            el.addEventListener('keypress', function(e) {
                const char = e.key;
                // Allow digits, dot, comma
                if (char.length === 1 && !/[\d.,]/.test(char)) {
                    e.preventDefault();
                    showFieldError(el, 'Ce champ n\'accepte que des chiffres.');
                }
            });
        });

        // --- Block manual text input in date_naissance (type=date already prevents it) ---
        const dobField = document.getElementById('date_naissance');
        if (dobField) {
            dobField.addEventListener('keypress', function(e) {
                // Allow digits and dash (for manual date input on some browsers)
                const char = e.key;
                if (char.length === 1 && !/[\d\-\/]/.test(char)) {
                    e.preventDefault();
                }
            });
        }

        // --- Password confirmation match ---
        const passField = document.getElementById('password');
        const passConf  = document.getElementById('password_confirm');
        if (passField && passConf) {
            passConf.addEventListener('input', function() {
                if (this.value && this.value !== passField.value) {
                    showFieldError(passConf, 'Les mots de passe ne correspondent pas.');
                } else {
                    clearFieldError(passConf);
                }
            });
        }

        // --- Form submit validation ---
        signupForm.addEventListener('submit', function(e) {
            const nom     = document.getElementById('nom')?.value.trim();
            const prenom  = document.getElementById('prenom')?.value.trim();
            const email   = document.getElementById('email')?.value.trim();
            const dob     = document.getElementById('date_naissance')?.value;
            const license = document.getElementById('type_license')?.value;
            const pass    = document.getElementById('password')?.value;
            const passC   = document.getElementById('password_confirm')?.value;

            const errs = [];
            if (!nom)    errs.push('Le nom est requis.');
            if (/\d/.test(nom)) errs.push('Le nom ne doit pas contenir de chiffres.');
            if (!prenom) errs.push('Le prénom est requis.');
            if (/\d/.test(prenom)) errs.push('Le prénom ne doit pas contenir de chiffres.');
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errs.push('Email invalide.');
            if (!dob)    errs.push('La date de naissance est requise.');
            if (!license) errs.push('Veuillez choisir un type de licence.');
            if (!pass || pass.length < 8) errs.push('Mot de passe : 8 caractères minimum.');
            if (pass !== passC)  errs.push('Les mots de passe ne correspondent pas.');

            if (errs.length > 0) {
                e.preventDefault();
                showError(signupForm, errs.join(' | '));
            }
        });
    }

    function showError(form, msg) {
        let err = form.querySelector('.form-error-js');
        if (!err) {
            err = document.createElement('div');
            err.className = 'flash-message flash-error form-error-js';
            form.prepend(err);
        }
        err.textContent = msg;
        err.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    // ==================== NEWSLETTER FORM ====================
    const newsletterForms = document.querySelectorAll('.newsletter-form');
    newsletterForms.forEach(form => {
        form.addEventListener('submit', function (e) {
            const email = this.querySelector('[name="newsletter_email"]').value.trim();
            if (!email) {
                e.preventDefault();
                alert('Veuillez saisir une adresse email.');
            }
        });
    });

    // ==================== AUTO HIDE FLASH ====================
    const flashMsg = document.querySelector('.flash-message');
    if (flashMsg) {
        setTimeout(() => {
            flashMsg.style.transition = 'opacity 0.5s';
            flashMsg.style.opacity = '0';
            setTimeout(() => flashMsg.remove(), 500);
        }, 5000);
    }

    // ==================== ADMIN PERMISSION TOGGLES ====================
    const permToggles = document.querySelectorAll('.perm-toggle');
    permToggles.forEach(toggle => {
        toggle.addEventListener('change', function () {
            const userId = this.getAttribute('data-user');
            const perm   = this.getAttribute('data-perm');
            const val    = this.checked ? 1 : 0;
            fetch('?action=toggle_perm', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `user_id=${userId}&perm=${perm}&val=${val}&csrf=${document.getElementById('csrf-token')?.value || ''}`
            });
        });
    });
});

// ==================== COOKIE SYSTEM ====================
function cookieInit() {
    const consent = localStorage.getItem('fcgs_cookie_consent');
    if (!consent) {
        const banner = document.getElementById('cookie-banner');
        if (banner) banner.style.display = 'block';
    }
}

function cookieAcceptAll() {
    const prefs = { necessary: true, stats: true, social: true, marketing: true, date: new Date().toISOString() };
    localStorage.setItem('fcgs_cookie_consent', JSON.stringify(prefs));
    document.getElementById('cookie-banner').style.display = 'none';
    document.getElementById('cookie-panel').style.display  = 'none';
}

function cookieRefuseAll() {
    const prefs = { necessary: true, stats: false, social: false, marketing: false, date: new Date().toISOString() };
    localStorage.setItem('fcgs_cookie_consent', JSON.stringify(prefs));
    document.getElementById('cookie-banner').style.display = 'none';
    document.getElementById('cookie-panel').style.display  = 'none';
}

function cookieOpenPanel() {
    document.getElementById('cookie-banner').style.display = 'none';
    document.getElementById('cookie-panel').style.display  = 'flex';
    // Load saved prefs into toggles
    try {
        const saved = JSON.parse(localStorage.getItem('fcgs_cookie_consent') || '{}');
        if (document.getElementById('ck-stats'))     document.getElementById('ck-stats').checked     = saved.stats     !== false;
        if (document.getElementById('ck-social'))    document.getElementById('ck-social').checked    = saved.social    !== false;
        if (document.getElementById('ck-marketing')) document.getElementById('ck-marketing').checked = saved.marketing === true;
    } catch(e) {}
}

function cookieSaveChoices() {
    const prefs = {
        necessary: true,
        stats:     document.getElementById('ck-stats')?.checked     ?? true,
        social:    document.getElementById('ck-social')?.checked    ?? true,
        marketing: document.getElementById('ck-marketing')?.checked ?? false,
        date: new Date().toISOString()
    };
    localStorage.setItem('fcgs_cookie_consent', JSON.stringify(prefs));
    document.getElementById('cookie-panel').style.display = 'none';
}

// Close panel on overlay click
document.addEventListener('DOMContentLoaded', function() {
    cookieInit();
    const panel = document.getElementById('cookie-panel');
    if (panel) {
        panel.addEventListener('click', function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                cookieInit();
            }
        });
    }
    // "Gestion des cookies" footer link opens panel
    document.querySelectorAll('a[href*="/cookies"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            cookieOpenPanel();
        });
    });
});

const fadeElements = document.querySelectorAll('.esport-card, .sidebar-card, .gallery-item, .match-row');

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = 1;
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, { threshold: 0.2 });

fadeElements.forEach(el => {
    el.style.opacity = 0;
    el.style.transform = 'translateY(20px)';
    el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    observer.observe(el);
});