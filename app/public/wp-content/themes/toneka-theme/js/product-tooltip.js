/**
 * Product Tooltip - follows cursor like BoldNote
 */
(function() {
    'use strict';

    function initProductTooltips() {
        const productCards = document.querySelectorAll('.toneka-product-card');
        
        productCards.forEach(card => {
            const tooltip = card.querySelector('.toneka-product-tooltip');
            if (!tooltip) return;
            
            const imageWrapper = card.querySelector('.toneka-product-image-wrapper');
            if (!imageWrapper) return;
            
            let isHovering = false;
            
            // Mouse enter - pokaż tooltip
            imageWrapper.addEventListener('mouseenter', function() {
                isHovering = true;
                tooltip.style.opacity = '1';
                tooltip.style.visibility = 'visible';
            });
            
            // Mouse move - przesuń tooltip za kursorem
            imageWrapper.addEventListener('mousemove', function(e) {
                if (!isHovering) return;
                
                const rect = imageWrapper.getBoundingClientRect();
                const tooltipRect = tooltip.getBoundingClientRect();
                
                // Pozycja kursora względem kontenera
                let x = e.clientX - rect.left;
                let y = e.clientY - rect.top;
                
                // Offset dla tooltipa (15px od kursora)
                const offset = 15;
                x += offset;
                y += offset;
                
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
                
                // Ustaw pozycję tooltipa
                tooltip.style.left = x + 'px';
                tooltip.style.top = y + 'px';
                tooltip.style.transform = 'none'; // Wyłącz centrowanie
            });
            
            // Mouse leave - ukryj tooltip
            imageWrapper.addEventListener('mouseleave', function() {
                isHovering = false;
                tooltip.style.opacity = '0';
                tooltip.style.visibility = 'hidden';
                
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

