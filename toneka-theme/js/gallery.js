/**
 * Toneka Gallery Slideshow
 * Obsługuje slideshow galerii produktów z fade transition
 */

document.addEventListener('DOMContentLoaded', function() {
    const gallery = document.querySelector('.toneka-custom-gallery');
    
    if (!gallery) return;
    
    const slideshow = gallery.querySelector('.toneka-gallery-slideshow');
    if (!slideshow) return;
    
    const slides = slideshow.querySelectorAll('.gallery-slide');
    const dots = slideshow.querySelectorAll('.gallery-dot');
    
    if (slides.length <= 1) return;
    
    let currentSlide = 0;
    let autoSlideInterval;
    
    // Funkcja przełączania slajdów
    function showSlide(index) {
        // Usuń aktywną klasę ze wszystkich slajdów i kropek
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));
        
        // Dodaj aktywną klasę do nowego slajdu
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        
        currentSlide = index;
    }
    
    // Funkcja następnego slajdu
    function nextSlide() {
        const next = (currentSlide + 1) % slides.length;
        showSlide(next);
    }
    
    // Funkcja poprzedniego slajdu
    function prevSlide() {
        const prev = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(prev);
    }
    
    // Auto slideshow co 4 sekundy
    function startAutoSlide() {
        autoSlideInterval = setInterval(nextSlide, 4000);
    }
    
    function stopAutoSlide() {
        if (autoSlideInterval) {
            clearInterval(autoSlideInterval);
        }
    }
    
    // Obsługa kliknięć na kropki
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            showSlide(index);
            stopAutoSlide();
            startAutoSlide(); // Restart auto slideshow
        });
    });
    
    // Obsługa hover - zatrzymaj auto slideshow
    slideshow.addEventListener('mouseenter', stopAutoSlide);
    slideshow.addEventListener('mouseleave', startAutoSlide);
    
    // Obsługa klawiatury (opcjonalne)
    document.addEventListener('keydown', (e) => {
        if (!slideshow.matches(':hover')) return;
        
        if (e.key === 'ArrowLeft') {
            prevSlide();
            stopAutoSlide();
            startAutoSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
            stopAutoSlide();
            startAutoSlide();
        }
    });
    
    // Obsługa touch/swipe dla urządzeń mobilnych
    let startX = 0;
    let startY = 0;
    
    slideshow.addEventListener('touchstart', (e) => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    });
    
    slideshow.addEventListener('touchend', (e) => {
        if (!startX || !startY) return;
        
        const endX = e.changedTouches[0].clientX;
        const endY = e.changedTouches[0].clientY;
        
        const diffX = startX - endX;
        const diffY = startY - endY;
        
        // Sprawdź czy to był swipe poziomy
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                // Swipe left - następny slajd
                nextSlide();
            } else {
                // Swipe right - poprzedni slajd
                prevSlide();
            }
            stopAutoSlide();
            startAutoSlide();
        }
        
        startX = 0;
        startY = 0;
    });
    
    // Rozpocznij auto slideshow
    startAutoSlide();
});
