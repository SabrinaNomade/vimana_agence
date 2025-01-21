document.addEventListener("DOMContentLoaded", () => {
    const slides = document.querySelectorAll(".carousel-item");
    const indicators = document.querySelectorAll(".indicator");
    const navToggle = document.querySelector(".nav-toggle");  // Récupère le bouton burger
    const navLinks = document.querySelector(".nav-links");    // Récupère la liste des liens de navigation

    let currentIndex = 0;

    // Fonction pour afficher une diapositive
    const showSlide = (index) => {
        slides.forEach((slide, i) => {
            slide.classList.remove("active");
            indicators[i].classList.remove("active");
            if (i === index) {
                slide.classList.add("active");
                indicators[i].classList.add("active");
            }
        });
    };

    // Gestion des clics sur les indicateurs
    indicators.forEach((indicator, index) => {
        indicator.addEventListener("click", () => {
            currentIndex = index;
            showSlide(currentIndex);
        });
    });

    // Changement automatique de diapositive toutes les 5 secondes
    setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        showSlide(currentIndex);
    }, 5000);

    // Initialisation
    showSlide(currentIndex);

    // Menu burger : afficher/masquer les liens de navigation
    navToggle.addEventListener("click", () => {
        navLinks.classList.toggle("active");  // Ajouter ou retirer la classe 'active'
    });
});

