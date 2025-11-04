/**
 * Product Tooltip - follows cursor like BoldNote
 * Z płynnym "doganianiem" kursora używając easeInOutBack
 */
(function() {
    'use strict';

    // Easing function: easeInOutBack
    function easeInOutBack(t) {
        const c1 = 1.70158;
        const c2 = c1 * 1.525;
        return t < 0.5
            ? (Math.pow(2 * t, 2) * ((c2 + 1) * 2 * t - c2)) / 2
            : (Math.pow(2 * t - 2, 2) * ((c2 + 1) * (t * 2 - 2) + c2) + 2) / 2;
    }

    function initProductTooltips() {
        const productCards = document.querySelectorAll('.toneka-product-card');
        
        productCards.forEach(card => {
            const tooltip = card.querySelector('.toneka-product-tooltip');
            if (!tooltip) return;
            
            const imageWrapper = card.querySelector('.toneka-product-image-wrapper');
            if (!imageWrapper) return;
            
            let isHovering = false;
            let animationFrameId = null;
            
            // Aktualna pozycja tooltipa
            let currentX = 0;
            let currentY = 0;
            
            // Docelowa pozycja (gdzie jest kursor)
            let targetX = 0;
            let targetY = 0;
            
            // Prędkość doganiania (0-1, im mniejsza tym wolniejsze)
            const followSpeed = 0.01; // Bardzo leniwy - smooth przejścia między stronami
            
            // Funkcja animacji - płynne doganianie
            function animate() {
                if (!isHovering) return;
                
                // Interpolacja z easingiem
                const dx = targetX - currentX;
                const dy = targetY - currentY;
                
                // Dystans do celu
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                // Jeśli jesteśmy bardzo blisko, przestań animować
                if (distance < 0.5) {
                    currentX = targetX;
                    currentY = targetY;
                } else {
                    // Płynne doganianie z easing
                    currentX += dx * followSpeed;
                    currentY += dy * followSpeed;
                }
                
                // Ustaw pozycję
                tooltip.style.left = currentX + 'px';
                tooltip.style.top = currentY + 'px';
                tooltip.style.transform = 'none';
                
                // Kontynuuj animację
                animationFrameId = requestAnimationFrame(animate);
            }
            
            // Mouse enter - pokaż tooltip i rozpocznij animację
            imageWrapper.addEventListener('mouseenter', function(e) {
                isHovering = true;
                tooltip.style.opacity = '1';
                tooltip.style.visibility = 'visible';
                
                // Ustaw początkową pozycję na pozycji kursora
                const rect = imageWrapper.getBoundingClientRect();
                currentX = e.clientX - rect.left + 15;
                currentY = e.clientY - rect.top + 15;
                targetX = currentX;
                targetY = currentY;
                
                // Rozpocznij animację
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                }
                animationFrameId = requestAnimationFrame(animate);
            });
            
            // Mouse move - aktualizuj docelową pozycję
            imageWrapper.addEventListener('mousemove', function(e) {
                if (!isHovering) return;
                
                const rect = imageWrapper.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();
                
                // Pozycja kursora względem kontenera
                let cursorX = e.clientX - rect.left;
                let cursorY = e.clientY - rect.top;
                
                // Sprawdź, czy kursor jest po lewej czy prawej stronie (punkt środkowy)
                const isLeftSide = cursorX < rect.width / 2;
                
                // Sprawdź, czy kursor jest w górnej czy dolnej części (punkt środkowy)
                const isTopHalf = cursorY < rect.height / 2;
                
                // Zmień układ tooltipa w zależności od pozycji kursora
                if (isLeftSide) {
                    // Kursor po lewej → tooltip po prawej, czarna plakietka z lewej
                    tooltip.classList.remove('tooltip-flipped');
                } else {
                    // Kursor po prawej → tooltip po lewej, czarna plakietka z prawej
                    tooltip.classList.add('tooltip-flipped');
                }
                
                if (isTopHalf) {
                    // Kursor u góry → tooltip pod kursorem, CZYTA nad nazwiskiem
                    tooltip.classList.remove('tooltip-bottom');
                } else {
                    // Kursor na dole → tooltip nad kursorem, CZYTA pod nazwiskiem
                    tooltip.classList.add('tooltip-bottom');
                }
                
                // Offset dla tooltipa (15px od kursora)
                const offset = 15;
                let x, y;
                
                // Pozycja X (lewo/prawo)
                if (isLeftSide) {
                    // Tooltip po prawej od kursora
                    x = cursorX + offset;
                } else {
                    // Tooltip po lewej od kursora
                    x = cursorX - tooltipRect.width - offset;
                }
                
                // Pozycja Y (góra/dół)
                if (isTopHalf) {
                    // Tooltip poniżej kursora
                    y = cursorY + offset;
                } else {
                    // Tooltip powyżej kursora
                    y = cursorY - tooltipRect.height - offset;
                }
                
                // Granice kontenera - tooltip nie może wyjść poza plakietkę
                const tooltipWidth = tooltipRect.width;
                const tooltipHeight = tooltipRect.height;
                
                // Ogranicz x
                if (x + tooltipWidth > rect.width) {
                    x = rect.width - tooltipWidth - 10;
                }
                if (x < 10) {
                    x = 10;
                }
                
                // Ogranicz y
                if (y + tooltipHeight > rect.height) {
                    y = rect.height - tooltipHeight - 10;
                }
                if (y < 10) {
                    y = 10;
                }
                
                // Aktualizuj docelową pozycję (tooltip będzie do niej "gonił")
                targetX = x;
                targetY = y;
            });
            
            // Mouse leave - ukryj tooltip i zatrzymaj animację
            imageWrapper.addEventListener('mouseleave', function() {
                isHovering = false;
                tooltip.style.opacity = '0';
                tooltip.style.visibility = 'hidden';
                
                // Zatrzymaj animację
                if (animationFrameId) {
                    cancelAnimationFrame(animationFrameId);
                    animationFrameId = null;
                }
                
                // Reset pozycji
                tooltip.style.left = '50%';
                tooltip.style.top = '50%';
                tooltip.style.transform = 'translate(-50%, -50%)';
            });
        });
    }
    
    // Initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initProductTooltips);
    } else {
        initProductTooltips();
    }
    
    // Re-initialize after AJAX loads (for dynamic content)
    document.addEventListener('toneka-products-loaded', initProductTooltips);
    
})();

