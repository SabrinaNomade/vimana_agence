
document.addEventListener("DOMContentLoaded", () => {
document.getElementById('current-year').textContent = new Date().getFullYear();

    const slides = document.querySelectorAll(".carousel-item");
    const indicators = document.querySelectorAll(".indicator");
    const navToggle = document.querySelector(".nav-toggle"); // Récupère le bouton burger
    const navLinks = document.querySelector(".nav-links"); // Récupère la liste des liens de navigation
    const newsletterForm = document.querySelector("#newsletter-form");
    const emailInput = document.querySelector("#newsletter-email");
    const errorMessage = document.querySelector("#newsletter-error");

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
        navLinks.classList.toggle("active"); // Ajouter ou retirer la classe 'active'
    });

    // Validation de l'email pour la newsletter
    if (newsletterForm) {
        newsletterForm.addEventListener("submit", (event) => {
            console.log("Form submitted"); // Vérifier si la soumission du formulaire est bien déclenchée
            const email = emailInput.value.trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Regex pour valider un email

            if (!emailPattern.test(email)) {
                console.log("Invalid email"); // Vérifier si l'email est invalide
                event.preventDefault(); // Empêche la soumission du formulaire
                errorMessage.textContent = "Veuillez entrer une adresse email valide.";
                errorMessage.style.display = "block";
            } else {
                console.log("Valid email"); // Vérifier si l'email est valide
                errorMessage.textContent = "";
                errorMessage.style.display = "none";
            }
        });
    }

    // Gestion des clics sur les réseaux sociaux (footer)
    const socialLinks = document.querySelectorAll(".footer-social-icons a");
    socialLinks.forEach(link => {
        link.addEventListener("click", (event) => {
            
        });
    });
})
