document.addEventListener("DOMContentLoaded", () => {
    const formSection = document.querySelectorAll('.form-container');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, { threshold: 0.1 });

    formSection.forEach(el => {
        el.classList.add('hidden');
        observer.observe(el);
    });
});
