document.addEventListener("DOMContentLoaded", () => {
    // ********** MISE À JOUR DE L'ANNÉE ACTUELLE **********
    const yearEl = document.getElementById('current-year');
    if (yearEl) {
        yearEl.textContent = new Date().getFullYear();
    }

    // ********** MENU BURGER **********
    function toggleMenu() {
        const navLinks = document.querySelector('.nav-links');
        navLinks.classList.toggle('active');
    }

    const navToggle = document.querySelector('.nav-toggle');
    if (navToggle) {
        navToggle.addEventListener('click', toggleMenu);
    }

    // ********** GESTION DE LA BANNIÈRE DE COOKIES **********
    if (!localStorage.getItem('cookies_accepted')) {
        document.getElementById('cookie-banner').style.display = 'block';
    }

    document.getElementById('accept-cookies').addEventListener('click', () => {
        localStorage.setItem('cookies_accepted', 'true');
        document.getElementById('cookie-banner').style.display = 'none';
    });

    // ********** NEWSLETTER VALIDATION **********
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

    // ********** INITIALISATION DU CARROUSEL **********
    const carousel = document.querySelector('.carousel');
    if (carousel) {
        new bootstrap.Carousel(carousel, {
            interval: 3000,
            ride: 'carousel'
        });
    }


  
});
