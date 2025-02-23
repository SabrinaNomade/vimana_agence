
document.addEventListener('DOMContentLoaded', function () {
    // Cibler le formulaire de contact
    const formContact = document.getElementById('form-contact'); // S'assurer que l'ID correspond bien au formulaire
    
    if (formContact) {
        formContact.addEventListener('submit', function(event) {
            const name = formContact.querySelector('input[name="name"]');
            const email = formContact.querySelector('input[name="email"]');
            const message = formContact.querySelector('textarea[name="message"]');

            // Vérification si le champ Nom est vide
            if (!name.value.trim()) {
                alert('Le champ Nom est requis.');
                event.preventDefault(); // Empêche l'envoi du formulaire
            }

            // Vérification si le champ Email est valide
            if (!email.value.trim() || !email.validity.valid) {
                alert('Veuillez entrer un email valide.');
                event.preventDefault(); // Empêche l'envoi du formulaire
            }

            // Vérification si le champ Message est vide
            if (!message.value.trim()) {
                alert('Le champ Message est requis.');
                event.preventDefault(); // Empêche l'envoi du formulaire
            }
        });
    }
});
