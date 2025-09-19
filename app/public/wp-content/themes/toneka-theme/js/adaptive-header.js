/**
 * Adaptive Header - dostosowuje styl headera do jasności tła
 */

class AdaptiveHeader {
    constructor() {
        this.header = document.querySelector('.product-page-header');
        this.heroSection = document.querySelector('.toneka-hero-section');
        this.heroImage = null;
        
        if (this.header && this.heroSection) {
            this.init();
        }
    }
    
    init() {
        // Znajdź główny obraz w hero sekcji
        this.findHeroImage();
        
        // Analizuj jasność co 500ms (throttling)
        this.throttledAnalyze = this.throttle(this.analyzeImageBrightness.bind(this), 500);
        
        // Uruchom analizę po załadowaniu strony
        window.addEventListener('load', () => {
            setTimeout(() => this.analyzeImageBrightness(), 100);
        });
        
        // Monitoruj zmiany w galerii (slideshow)
        this.observeImageChanges();
        
        // Reaguj na resize okna
        window.addEventListener('resize', this.throttledAnalyze);
        
        // Reaguj na zmiany produktów po AJAX (kategorie/sklep)
        document.addEventListener('toneka_products_updated', () => {
            this.findHeroImage();
            setTimeout(() => this.analyzeImageBrightness(), 100);
        });
    }
    
    findHeroImage() {
        // Sprawdź najpierw czy jest slideshow (strona produktu)
        const slideshow = this.heroSection.querySelector('.toneka-gallery-slideshow');
        if (slideshow) {
            const activeSlide = slideshow.querySelector('.gallery-slide.active img') || 
                               slideshow.querySelector('.gallery-slide img');
            if (activeSlide) {
                this.heroImage = activeSlide;
                return;
            }
        }
        
        // Jeśli nie ma slideshow, znajdź pojedynczy obraz
        // Sprawdź różne możliwe lokalizacje obrazu
        this.heroImage = this.heroSection.querySelector('.toneka-hero-right img') ||
                        this.heroSection.querySelector('.toneka-category-image img') ||
                        this.heroSection.querySelector('img');
    }
    
    analyzeImageBrightness() {
        if (!this.heroImage) {
            // Brak obrazu - ustaw domyślny styl (ciemny)
            console.log('Brak obrazu - zastosowano domyślny ciemny header');
            this.setHeaderStyle('dark');
            return;
        }
        
        if (!this.heroImage.complete) {
            // Jeśli obraz nie jest załadowany, spróbuj ponownie za chwilę
            setTimeout(() => this.analyzeImageBrightness(), 200);
            return;
        }
        
        try {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            
            // Ustaw rozmiar canvas - analizujemy tylko górną część obrazu (gdzie jest menu)
            const headerHeight = this.header.offsetHeight;
            const imageRect = this.heroImage.getBoundingClientRect();
            
            canvas.width = 100; // Mały rozmiar dla wydajności
            canvas.height = Math.min(50, Math.floor(headerHeight * 100 / imageRect.height));
            
            // Narysuj fragment obrazu odpowiadający pozycji menu
            ctx.drawImage(
                this.heroImage,
                0, 0, this.heroImage.naturalWidth, this.heroImage.naturalHeight * (headerHeight / imageRect.height),
                0, 0, canvas.width, canvas.height
            );
            
            // Pobierz dane pikseli
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            
            // Oblicz średnią jasność
            let totalBrightness = 0;
            let pixelCount = 0;
            
            for (let i = 0; i < data.length; i += 4) {
                const r = data[i];
                const g = data[i + 1];
                const b = data[i + 2];
                const alpha = data[i + 3];
                
                if (alpha > 0) { // Tylko nieprzeźroczyste piksele
                    // Wzór na jasność: 0.299*R + 0.587*G + 0.114*B
                    const brightness = (0.299 * r + 0.587 * g + 0.114 * b);
                    totalBrightness += brightness;
                    pixelCount++;
                }
            }
            
            if (pixelCount > 0) {
                const avgBrightness = totalBrightness / pixelCount;
                const brightnessPercent = avgBrightness / 255;
                
                console.log('Jasność obrazu pod menu:', Math.round(brightnessPercent * 100) + '%');
                
                this.adjustHeaderStyle(brightnessPercent);
            }
            
        } catch (error) {
            console.warn('Nie można analizować jasności obrazu:', error);
            // Fallback - ustaw domyślny styl
            this.setHeaderStyle('default');
        }
    }
    
    adjustHeaderStyle(brightness) {
        // Usuń poprzednie klasy
        this.header.classList.remove('header-on-light', 'header-on-dark');
        
        if (brightness > 0.6) {
            // Jasny obraz - ciemniejszy header
            this.header.classList.add('header-on-light');
            console.log('Wykryto jasny obraz - zastosowano ciemny header');
        } else {
            // Ciemny obraz - domyślny header
            this.header.classList.add('header-on-dark');
            console.log('Wykryto ciemny obraz - zastosowano normalny header');
        }
    }
    
    setHeaderStyle(type) {
        this.header.classList.remove('header-on-light', 'header-on-dark');
        if (type === 'light') {
            this.header.classList.add('header-on-light');
        } else {
            this.header.classList.add('header-on-dark');
        }
    }
    
    observeImageChanges() {
        // Observer dla zmian w slideshow
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'attributes' && 
                    (mutation.attributeName === 'class' || mutation.attributeName === 'src')) {
                    this.findHeroImage();
                    setTimeout(() => this.analyzeImageBrightness(), 100);
                }
            });
        });
        
        if (this.heroSection) {
            observer.observe(this.heroSection, {
                attributes: true,
                childList: true,
                subtree: true
            });
        }
    }
    
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        }
    }
}

// Inicjalizuj po załadowaniu DOM
document.addEventListener('DOMContentLoaded', () => {
    new AdaptiveHeader();
});













