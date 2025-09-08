document.addEventListener("DOMContentLoaded", () => {

    // ********** MISE À JOUR DE L'ANNÉE ACTUELLE **********
    const yearEl = document.getElementById('current-year');
    if (yearEl) yearEl.textContent = new Date().getFullYear();

    // ********** HERO - TITRE STATIQUE (H1) **********
    const heroTitle = document.getElementById("hero-title");
    if (heroTitle) {
        heroTitle.textContent = "Vimana Paris – Agence de conseil et accompagnement pour artistes";
    }

    // ********** HERO - SOUS-TITRE ANIMÉ (H2) **********
    const heroSubtitle = document.getElementById("hero-subtitle");
    const cursorEl = document.getElementById("cursor");
    if (heroSubtitle && cursorEl) {
        const fullText = heroSubtitle.textContent.trim(); // garde le texte pour SEO
        heroSubtitle.textContent = ""; // vide pour l'animation
        let i = 0;

        const typeLetter = () => {
            if (i < fullText.length) {
                heroSubtitle.textContent += fullText[i];
                i++;
                setTimeout(typeLetter, 50); // vitesse adaptée pour phrases plus longues
            } else {
                cursorEl.classList.add("stop"); // stop curseur après animation
            }
        };

        typeLetter();
    }

    // ********** MENU BURGER **********
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');
    const navItems = navLinks ? navLinks.querySelectorAll('li') : [];

    const toggleMenu = () => {
        if (!navLinks) return;
        navLinks.classList.toggle('active');

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
                navItems.forEach(item => {
                    item.style.opacity = "";
                    item.style.transform = "";
                    item.style.transition = "";
                });
            }
        });
    });

    // ********** BANNIÈRE COOKIES **********
    const cookieBanner = document.getElementById('cookie-banner');
    const acceptCookiesBtn = document.getElementById('accept-cookies');
    if (cookieBanner && !localStorage.getItem('cookies_accepted')) {
        cookieBanner.style.display = 'block';
    }
    if (acceptCookiesBtn) {
        acceptCookiesBtn.addEventListener('click', () => {
            localStorage.setItem('cookies_accepted', 'true');
            cookieBanner.style.display = 'none';
        });
    }

    // ********** NEWSLETTER **********
    const newsletterForm = document.querySelector("#newsletter-form");
    const emailInput = document.querySelector("#newsletter-email");
    const errorMessage = document.querySelector("#newsletter-error");
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", (event) => {
            const email = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                event.preventDefault();
                errorMessage.textContent = "Veuillez entrer une adresse email valide.";
                errorMessage.style.display = "block";
            } else {
                errorMessage.textContent = "";
                errorMessage.style.display = "none";
            }
        });
    }

    // ********** CARROUSEL PROJETS (Bootstrap) **********
    const projectCarousel = document.querySelector('#carouselProjects');
    if (projectCarousel) {
        new bootstrap.Carousel(projectCarousel, {
            interval: 5000,
            wrap: true
        });
    }

    // ********** FADE-IN AU SCROLL **********
    const elements = document.querySelectorAll("section");
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) entry.target.classList.add("fade-in");
        });
    }, { threshold: 0.1 });
    elements.forEach(el => observer.observe(el));

});

