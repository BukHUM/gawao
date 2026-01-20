// Hero Slider Functionality
let currentSlide = 0;
let totalSlides = 4;
let slides = [];
let dots = [];

function initHeroSlider() {
    slides = document.querySelectorAll('.hero-slide');
    dots = document.querySelectorAll('.hero-dot');
    totalSlides = slides.length;
    
    if (slides.length === 0) return;
    
    function showSlide(index) {
        // Hide all slides
        slides.forEach((slide, i) => {
            slide.classList.remove('opacity-100', 'z-10');
            slide.classList.add('opacity-0', 'z-0');
        });
        
        // Show current slide
        slides[index].classList.remove('opacity-0', 'z-0');
        slides[index].classList.add('opacity-100', 'z-10');
        
        // Update dots
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.remove('bg-white/50', 'w-2.5');
                dot.classList.add('bg-white', 'w-3');
            } else {
                dot.classList.remove('bg-white', 'w-3');
                dot.classList.add('bg-white/50', 'w-2.5');
            }
        });
    }
    
    function nextSlide() {
        currentSlide = (currentSlide + 1) % totalSlides;
        showSlide(currentSlide);
    }
    
    function prevSlide() {
        currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(currentSlide);
    }
    
    // Event Listeners
    const prevBtn = document.getElementById('hero-prev');
    const nextBtn = document.getElementById('hero-next');
    
    if (prevBtn) prevBtn.addEventListener('click', prevSlide);
    if (nextBtn) nextBtn.addEventListener('click', nextSlide);
    
    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });
    
    // Auto-play slider (every 5 seconds) - only when home page is active
    setInterval(() => {
        if (document.getElementById('view-home')?.classList.contains('active')) {
            nextSlide();
        }
    }, 5000);
    
    // Initialize first slide
    showSlide(0);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroSlider);
} else {
    initHeroSlider();
}

// Re-initialize when home page is loaded
document.addEventListener('pageLoaded', function(event) {
    if (event.detail.pageName === 'home') {
        setTimeout(initHeroSlider, 100);
    }
});
