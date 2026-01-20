// Utility Functions

// Back to Top Button
const backToTopButton = document.getElementById('back-to-top');

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function toggleBackToTop() {
    if (window.scrollY > 300) {
        backToTopButton.classList.remove('opacity-0', 'pointer-events-none');
        backToTopButton.classList.add('opacity-100', 'pointer-events-auto');
    } else {
        backToTopButton.classList.add('opacity-0', 'pointer-events-none');
        backToTopButton.classList.remove('opacity-100', 'pointer-events-auto');
    }
}

// Show/hide button on scroll
if (backToTopButton) {
    window.addEventListener('scroll', toggleBackToTop);
    toggleBackToTop();
}
