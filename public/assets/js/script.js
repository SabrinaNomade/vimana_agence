document.addEventListener("DOMContentLoaded", () => {

    /* =========================
       🗓️ MISE À JOUR DE L'ANNÉE ACTUELLE
    ========================== */
    const yearEl = document.getElementById('current-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    /* =========================
       🎯 HERO - TITRE STATIQUE (H1)
    ========================== */
    const heroTitle = document.getElementById("hero-title");
    if (heroTitle) {
        heroTitle.textContent = "Vimana Paris – Agence de conseil et accompagnement pour artistes";
    }

    /* =========================
       ✨ HERO - SOUS-TITRE ANIMÉ (H2)
    ========================== */
    const heroSubtitle = document.getElementById("hero-subtitle");
    const cursorEl = document.getElementById("cursor");
    if (heroSubtitle && cursorEl) {
        const fullText = heroSubtitle.textContent.trim();
        heroSubtitle.textContent = "";
        let i = 0;
        const typeLetter = () => {
            if (i < fullText.length) {
                heroSubtitle.textContent += fullText[i];
                i++;
                setTimeout(typeLetter, 50);
            } else {
                cursorEl.classList.add("stop");
            }
        };
        typeLetter();
    }



    /* =========================
       🍔 MENU BURGER RESPONSIVE + ANIMATION
    ========================== */
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    const toggleMenu = () => {
        if (!navLinks || !navToggle) return;
        navLinks.classList.toggle('active');
        navToggle.classList.toggle('active');
        const navItems = navLinks.querySelectorAll('li');
        if (navLinks.classList.contains('active')) {
            navItems.forEach((item, i) => {
                item.style.opacity = "0";
                item.style.transform = "translateY(-10px)";
                setTimeout(() => {
                    item.style.opacity = "1";
                    item.style.transform = "translateY(0)";
                    item.style.transition = "all 0.3s ease";
                }, i * 100);
            });
        } else {
            navItems.forEach(item => {
                item.style.opacity = "";
                item.style.transform = "";
                item.style.transition = "";
            });
        }
    };
    if (navToggle) navToggle.addEventListener('click', toggleMenu);
    document.querySelectorAll('.nav-links a').forEach(link => {
        link.addEventListener('click', () => {
            if (navLinks && navLinks.classList.contains('active')) {
                navLinks.classList.remove('active');
                navToggle.classList.remove('active');
                const navItems = navLinks.querySelectorAll('li');
                navItems.forEach(item => {
                    item.style.opacity = "";
                    item.style.transform = "";
                    item.style.transition = "";
                });
            }
        });
    });

    /* =========================
       🌈 NAVBAR AU SCROLL (doux)
   

    /* =========================
       🔑 FORMULAIRES LOGIN / REGISTER
    ========================== */
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');
    if (loginForm) loginForm.style.display = 'block';
    if (registerForm) registerForm.style.display = 'none';
    document.querySelectorAll('a[href="#login"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            if (loginForm && registerForm) {
                loginForm.style.display = 'block';
                registerForm.style.display = 'none';
                loginForm.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });
    document.querySelectorAll('a[href="#register"]').forEach(link => {
        link.addEventListener('click', e => {
            e.preventDefault();
            if (loginForm && registerForm) {
                loginForm.style.display = 'none';
                registerForm.style.display = 'block';
                registerForm.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    const emailInput = document.querySelector("#registerForm_email");
    const passwordInput = document.querySelector("#registerForm_plainPassword_first");
    if (emailInput && passwordInput && registerForm) {
        registerForm.addEventListener('submit', e => {
            let valid = true;
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(emailInput.value.trim())) {
                valid = false;
                alert("Veuillez entrer un email valide !");
            }
            if (passwordInput.value.length < 6) {
                valid = false;
                alert("Le mot de passe doit contenir au moins 6 caractères !");
            }
            if (!valid) e.preventDefault();
        });
    }

    /* =========================
       🍪 BANNIÈRE COOKIES MODERNE
    ========================== */
    const cookieBanner = document.getElementById('cookie-banner');
    const acceptBtn = document.getElementById('accept-cookies');
    const refuseBtn = document.getElementById('refuse-cookies');
    const manageBtn = document.getElementById('manage-cookies');
    const options = document.getElementById('cookie-options');
    const saveBtn = document.getElementById('save-cookies');

    if (cookieBanner && !localStorage.getItem('cookies_accepted') && !localStorage.getItem('cookies_refused')) {
        cookieBanner.style.display = 'block';
        setTimeout(() => cookieBanner.classList.add('show'), 50); // animation entrée
    }

    acceptBtn?.addEventListener('click', () => {
        localStorage.setItem('cookies_accepted', 'true');
        cookieBanner.classList.remove('show');
        setTimeout(() => cookieBanner.style.display = 'none', 500);
    });

    refuseBtn?.addEventListener('click', () => {
        localStorage.setItem('cookies_refused', 'true');
        cookieBanner.classList.remove('show');
        setTimeout(() => cookieBanner.style.display = 'none', 500);
    });

    manageBtn?.addEventListener('click', () => {
        if (options) options.style.display = options.style.display === 'block' ? 'none' : 'block';
    });

    saveBtn?.addEventListener('click', () => {
        const analytics = document.getElementById('analytics-cookies')?.checked || false;
        const marketing = document.getElementById('marketing-cookies')?.checked || false;
        localStorage.setItem('cookies_analytics', analytics);
        localStorage.setItem('cookies_marketing', marketing);
        localStorage.setItem('cookies_accepted', 'true');
        cookieBanner.classList.remove('show');
        setTimeout(() => cookieBanner.style.display = 'none', 500);
    });

    /* =========================
       📨 NEWSLETTER
    ========================== */
    const newsletterForm = document.querySelector("#newsletter-form");
    const newsletterEmail = document.querySelector("#newsletter-email");
    const newsletterError = document.querySelector("#newsletter-error");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", (event) => {
            const email = (newsletterEmail && newsletterEmail.value) ? newsletterEmail.value.trim() : "";
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                event.preventDefault();
                if (newsletterError) {
                    newsletterError.textContent = "Veuillez entrer une adresse email valide.";
                    newsletterError.style.display = "block";
                }
            } else {
                if (newsletterError) {
                    newsletterError.textContent = "";
                    newsletterError.style.display = "none";
                }
            }
        });
    }

    /* =========================
       🎡 CARROUSEL PROJETS (Bootstrap)
    ========================== */
    const projectCarousel = document.querySelector('#carouselProjects');
    if (projectCarousel && typeof bootstrap !== 'undefined') {
        new bootstrap.Carousel(projectCarousel, { interval: 5000, wrap: true });
    }

    /* =========================
       🌟 FADE-IN AU SCROLL
    ========================== */
    const sections = document.querySelectorAll("section");
    if (sections.length > 0) {
        const sectionObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("fade-in");
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        sections.forEach(s => sectionObserver.observe(s));
    }

    /* =========================
       👩‍💼 ANIMATION SECTION FONDATRICE
    ========================== */
    const founderElements = document.querySelectorAll("[data-animate]");
    if (founderElements.length > 0) {
        const founderObserver = new IntersectionObserver((entries, obs) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("visible");
                    obs.unobserve(entry.target);
                }
            });
        }, { threshold: 0.18 });
        founderElements.forEach(el => founderObserver.observe(el));
    }

});
