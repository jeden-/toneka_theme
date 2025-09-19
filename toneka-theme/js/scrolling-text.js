/**
 * Scrolling Text - handle dynamic speed and responsive behavior
 */

document.addEventListener('DOMContentLoaded', function() {
    const scrollingText = document.querySelector('.toneka-scrolling-text');
    
    if (scrollingText) {
        // Get speed from data attribute
        const speed = scrollingText.dataset.speed || 50;
        
        // Set CSS custom property for animation duration
        scrollingText.style.setProperty('--scroll-duration', speed + 's');
        
        // Handle window resize - restart animation for smooth experience
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                // Temporarily pause animation
                scrollingText.style.animationPlayState = 'paused';
                
                // Restart animation after a brief delay
                setTimeout(function() {
                    scrollingText.style.animation = 'none';
                    scrollingText.offsetHeight; // Trigger reflow
                    scrollingText.style.animation = `scroll-left ${speed}s linear infinite`;
                }, 100);
            }, 250);
        });
        
        // Add accessibility: pause on focus
        scrollingText.addEventListener('focusin', function() {
            this.style.animationPlayState = 'paused';
        });
        
        scrollingText.addEventListener('focusout', function() {
            this.style.animationPlayState = 'running';
        });
    }
});
