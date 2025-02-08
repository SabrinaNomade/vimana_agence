document.addEventListener("DOMContentLoaded", () => {
    document.getElementById('current-year').textContent = new Date().getFullYear();

    // ********** CAROUSEL HERO **********
    const slides = document.querySelectorAll(".carousel-item");
    const indicators = document.querySelectorAll(".indicator");
    let currentIndex = 0;
    let interval = setInterval(nextSlide, 5000);

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.toggle("active", i === index);
            indicators[i].classList.toggle("active", i === index);
        });
    }

    function nextSlide() {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }

    function resetInterval() {
        clearInterval(interval);
        interval = setInterval(nextSlide, 5000);
    }

    indicators.forEach((indicator, index) => {
        indicator.addEventListener("click", () => {
            currentIndex = index;
            showSlide(currentIndex);
            resetInterval();
        });
    });

    showSlide(currentIndex);

    // ********** NOUVEAU CAROUSEL SLIDER **********
    document.querySelectorAll('.card-carousel').forEach(carousel => {
        let currentIndex = 0;
        const carouselContainer = carousel.querySelector('.carousel');
        const cards = carousel.querySelectorAll('.card');
        const totalCards = cards.length;
        const nextButton = carousel.querySelector('.next');
        const prevButton = carousel.querySelector('.prev');

        function getCardWidth() {
            return cards[0].getBoundingClientRect().width;
        }

        function updateCarousel() {
            const offset = -currentIndex * getCardWidth();
            carouselContainer.style.transform = `translateX(${offset}px)`;
        }

        nextButton.addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % totalCards;
            updateCarousel();
        });

        prevButton.addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + totalCards) % totalCards;
            updateCarousel();
        });

        window.addEventListener('load', updateCarousel);
    });

    // ********** MENU BURGER **********
    const navToggle = document.querySelector(".nav-toggle");
    const navLinks = document.querySelector(".nav-links");

    if (navToggle && navLinks) {
    navToggle.addEventListener("click", () => {
        navLinks.classList.toggle("active");
    });
} else {
    console.error("Élément(s) du menu introuvable(s) !");
}

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

    // ********** SOCIAL MEDIA LINKS **********
    const socialLinks = document.querySelectorAll(".footer-social-icons a");
    socialLinks.forEach(link => {
        link.addEventListener("click", (event) => {
            // Ajoute ici un suivi ou une action spécifique
        });
    });
});
