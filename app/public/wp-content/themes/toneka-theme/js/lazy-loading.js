/**
 * Lazy Loading dla obrazów i playerów
 * Używa Intersection Observer API
 */

(function() {
    'use strict';

    // Sprawdź czy Intersection Observer jest dostępny
    if (!('IntersectionObserver' in window)) {
        // Fallback - załaduj wszystko od razu (ale pomiń SVG - ładuj od razu)
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            const src = img.dataset.src || '';
            // SVG ładuj od razu, reszta też (fallback)
            img.src = src;
            img.classList.add('toneka-loaded');
        });
        return;
    }

    // Funkcja do ładowania obrazu
    function loadImage(img) {
        if (!img.dataset.src) return;
        
        // Pomijaj SVG - nie używaj lazy loading dla SVG
        const src = img.dataset.src || '';
        if (src.toLowerCase().endsWith('.svg')) {
            img.src = src;
            img.classList.add('toneka-loaded');
            const wrapper = img.closest('.toneka-lazy-wrapper');
            if (wrapper) {
                wrapper.classList.remove('toneka-loading');
                wrapper.classList.add('toneka-loaded');
            }
            return;
        }
        
        const wrapper = img.closest('.toneka-lazy-wrapper');
        if (wrapper) {
            wrapper.classList.add('toneka-loading');
            wrapper.classList.remove('toneka-loaded');
        }

        const imageLoader = new Image();
        imageLoader.onload = function() {
            img.src = img.dataset.src;
            img.classList.add('toneka-loaded');
            
            if (wrapper) {
                wrapper.classList.remove('toneka-loading');
                wrapper.classList.add('toneka-loaded');
            }
        };
        imageLoader.onerror = function() {
            if (wrapper) {
                wrapper.classList.remove('toneka-loading');
            }
        };
        imageLoader.src = img.dataset.src;
    }

    // Funkcja do ładowania playera
    function loadPlayer(playerWrapper) {
        if (playerWrapper.classList.contains('toneka-loaded')) return;
        
        playerWrapper.classList.add('toneka-loading');
        
        const player = playerWrapper.querySelector('.toneka-figma-player');
        if (player && typeof window.initializeTonekaPlayer === 'function') {
            // Inicjalizuj playera
            try {
                window.initializeTonekaPlayer(player);
            } catch (e) {
                console.log('Player initialization:', e);
            }
            
            // Czekamy chwilę i oznaczamy jako załadowany
            setTimeout(() => {
                playerWrapper.classList.remove('toneka-loading');
                playerWrapper.classList.add('toneka-loaded');
                player.classList.add('toneka-player-visible');
            }, 300);
        } else {
            // Fallback - jeśli initializePlayer nie jest dostępny
            setTimeout(() => {
                playerWrapper.classList.remove('toneka-loading');
                playerWrapper.classList.add('toneka-loaded');
                if (player) {
                    player.classList.add('toneka-player-visible');
                }
            }, 500);
        }
    }

    // Opcje dla Intersection Observer
    const observerOptions = {
        root: null,
        rootMargin: '50px', // Rozpocznij ładowanie 50px przed wejściem w viewport
        threshold: 0.01
    };

    // Observer dla obrazów
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                loadImage(img);
                observer.unobserve(img);
            }
        });
    }, observerOptions);

    // Observer dla playerów
    const playerObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const playerWrapper = entry.target;
                loadPlayer(playerWrapper);
                observer.unobserve(playerWrapper);
            }
        });
    }, observerOptions);

    // Inicjalizacja po załadowaniu DOM
    function initLazyLoading() {
        // Znajdź wszystkie obrazy z data-src, wykluczając SVG
        const lazyImages = document.querySelectorAll('img[data-src]');
        lazyImages.forEach(img => {
            // Pomijaj obrazy SVG
            const src = img.dataset.src || '';
            if (src.toLowerCase().endsWith('.svg')) {
                // Załaduj SVG od razu bez lazy loading
                img.src = src;
                img.classList.add('toneka-loaded');
                return;
            }
            imageObserver.observe(img);
        });

        // Znajdź wszystkie playery do załadowania
        const lazyPlayers = document.querySelectorAll('.toneka-player-lazy-wrapper:not(.toneka-loaded)');
        lazyPlayers.forEach(playerWrapper => {
            playerObserver.observe(playerWrapper);
        });
    }

    // Uruchom gdy DOM jest gotowy
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initLazyLoading);
    } else {
        initLazyLoading();
    }

    // Obsługa dynamicznie dodanych elementów (np. przez AJAX)
    document.addEventListener('lazyLoadingRefresh', function() {
        initLazyLoading();
    });

})();
