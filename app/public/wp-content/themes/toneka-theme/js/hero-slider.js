/**
 * Hero Slider JavaScript
 * Obsługuje slajder hero z 3 slajdami
 */

class HeroSlider {
    constructor() {
        this.currentSlide = 0;
        this.slides = [];
        this.autoplay = true;
        this.duration = 5000;
        this.transition = 'fade';
        this.autoplayInterval = null;
        this.isTransitioning = false;
        
        this.init();
    }

    init() {
        // Pobierz ustawienia z WordPress Customizer
        this.getSettings();
        
        // Znajdź elementy slajdera
        this.sliderContainer = document.querySelector('.toneka-hero-slider');
        if (!this.sliderContainer) return;
        
        this.slides = this.sliderContainer.querySelectorAll('.toneka-slide');
        this.dots = this.sliderContainer.querySelectorAll('.toneka-slider-dot');
        this.prevBtn = this.sliderContainer.querySelector('.toneka-slider-prev');
        this.nextBtn = this.sliderContainer.querySelector('.toneka-slider-next');
        
        if (this.slides.length === 0) return;
        
        // Inicjalizuj slajder
        this.setupSlides();
        this.bindEvents();
        this.startAutoplay();
        
        // Pokaż pierwszy slajd
        this.showSlide(0);
    }

    getSettings() {
        // Pobierz ustawienia z data attributes
        const container = document.querySelector('.toneka-hero-slider');
        if (container) {
            this.autoplay = container.dataset.autoplay === 'true';
            this.duration = parseInt(container.dataset.duration) || 5000;
            this.transition = container.dataset.transition || 'fade';
        }
    }

    setupSlides() {
        // Dodaj klasy CSS do slajdów
        this.slides.forEach((slide, index) => {
            slide.classList.add('toneka-slide');
            slide.setAttribute('data-slide', index);
            
            // Dodaj overlay dla lepszej czytelności tekstu
            if (!slide.querySelector('.toneka-slide-overlay')) {
                const overlay = document.createElement('div');
                overlay.className = 'toneka-slide-overlay';
                slide.appendChild(overlay);
            }
        });

        // Dodaj klasy do kontenera
        this.sliderContainer.classList.add(`toneka-slider-${this.transition}`);
    }

    bindEvents() {
        // Przyciski nawigacji
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prevSlide());
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.nextSlide());
        }

        // Kropki nawigacji
        this.dots.forEach((dot, index) => {
            dot.addEventListener('click', () => this.goToSlide(index));
        });

        // Pauzuj autoplay przy hover
        this.sliderContainer.addEventListener('mouseenter', () => this.pauseAutoplay());
        this.sliderContainer.addEventListener('mouseleave', () => this.resumeAutoplay());

        // Touch/swipe support
        this.addTouchSupport();

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') this.prevSlide();
            if (e.key === 'ArrowRight') this.nextSlide();
        });
    }

    addTouchSupport() {
        let startX = 0;
        let startY = 0;
        let endX = 0;
        let endY = 0;

        this.sliderContainer.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });

        this.sliderContainer.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            endY = e.changedTouches[0].clientY;
            
            const deltaX = endX - startX;
            const deltaY = endY - startY;
            
            // Sprawdź czy to swipe poziomy (nie pionowy)
            if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 50) {
                if (deltaX > 0) {
                    this.prevSlide();
                } else {
                    this.nextSlide();
                }
            }
        });
    }

    showSlide(index) {
        if (this.isTransitioning || index === this.currentSlide) return;
        
        this.isTransitioning = true;
        const prevSlide = this.currentSlide;
        this.currentSlide = index;

        // Aktualizuj kropki
        this.updateDots();

        // Wykonaj przejście w zależności od typu
        switch (this.transition) {
            case 'fade':
                this.fadeTransition(prevSlide, index);
                break;
            case 'slide':
                this.slideTransition(prevSlide, index);
                break;
            case 'zoom':
                this.zoomTransition(prevSlide, index);
                break;
            default:
                this.fadeTransition(prevSlide, index);
        }

        // Resetuj autoplay
        this.resetAutoplay();
    }

    fadeTransition(prevIndex, nextIndex) {
        const prevSlide = this.slides[prevIndex];
        const nextSlide = this.slides[nextIndex];

        // Najpierw ukryj poprzedni slajd
        prevSlide.style.transition = 'opacity 0.4s ease-in-out';
        prevSlide.style.opacity = '0';
        
        // Po ukryciu poprzedniego, pokaż następny
        setTimeout(() => {
            prevSlide.classList.remove('active');
            prevSlide.style.transition = '';
            prevSlide.style.opacity = '';
            
            // Pokaż następny slajd
            nextSlide.classList.add('active');
            nextSlide.style.opacity = '0';
            
            // Animacja fade in
            requestAnimationFrame(() => {
                nextSlide.style.transition = 'opacity 0.4s ease-in-out';
                nextSlide.style.opacity = '1';
                
                setTimeout(() => {
                    nextSlide.style.transition = '';
                    nextSlide.style.opacity = '';
                    this.isTransitioning = false;
                }, 400);
            });
        }, 400);
    }

    slideTransition(prevIndex, nextIndex) {
        const prevSlide = this.slides[prevIndex];
        const nextSlide = this.slides[nextIndex];
        const direction = nextIndex > prevIndex ? 'next' : 'prev';

        // Pokaż następny slajd
        nextSlide.classList.add('active');
        nextSlide.style.transform = direction === 'next' ? 'translateX(100%)' : 'translateX(-100%)';
        
        // Animacja slide
        requestAnimationFrame(() => {
            nextSlide.style.transition = 'transform 0.8s ease-in-out';
            nextSlide.style.transform = 'translateX(0)';
            
            prevSlide.style.transition = 'transform 0.8s ease-in-out';
            prevSlide.style.transform = direction === 'next' ? 'translateX(-100%)' : 'translateX(100%)';
            
            setTimeout(() => {
                prevSlide.classList.remove('active');
                prevSlide.style.transition = '';
                prevSlide.style.transform = '';
                nextSlide.style.transition = '';
                this.isTransitioning = false;
            }, 800);
        });
    }

    zoomTransition(prevIndex, nextIndex) {
        const prevSlide = this.slides[prevIndex];
        const nextSlide = this.slides[nextIndex];

        // Pokaż następny slajd
        nextSlide.classList.add('active');
        nextSlide.style.transform = 'scale(1.2)';
        nextSlide.style.opacity = '0';
        
        // Animacja zoom
        requestAnimationFrame(() => {
            nextSlide.style.transition = 'transform 0.8s ease-in-out, opacity 0.8s ease-in-out';
            nextSlide.style.transform = 'scale(1)';
            nextSlide.style.opacity = '1';
            
            setTimeout(() => {
                prevSlide.classList.remove('active');
                nextSlide.style.transition = '';
                this.isTransitioning = false;
            }, 800);
        });
    }

    updateDots() {
        this.dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === this.currentSlide);
        });
    }

    nextSlide() {
        const nextIndex = (this.currentSlide + 1) % this.slides.length;
        this.showSlide(nextIndex);
    }

    prevSlide() {
        const prevIndex = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.showSlide(prevIndex);
    }

    goToSlide(index) {
        if (index >= 0 && index < this.slides.length) {
            this.showSlide(index);
        }
    }

    startAutoplay() {
        if (!this.autoplay) return;
        
        this.autoplayInterval = setInterval(() => {
            this.nextSlide();
        }, this.duration);
    }

    pauseAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }

    resumeAutoplay() {
        if (this.autoplay) {
            this.startAutoplay();
        }
    }

    resetAutoplay() {
        this.pauseAutoplay();
        this.resumeAutoplay();
    }

    destroy() {
        this.pauseAutoplay();
        // Usuń event listenery jeśli potrzeba
    }
}

// Inicjalizuj slajder po załadowaniu DOM
document.addEventListener('DOMContentLoaded', () => {
    new HeroSlider();
});

// Eksportuj klasę dla użycia w innych skryptach
window.HeroSlider = HeroSlider;
