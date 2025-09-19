/**
 * Creator Page JavaScript
 * Obsługuje smooth scrolling i animacje na stronie twórcy
 */

document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling dla przycisku PORTFOLIO
    const portfolioButton = document.querySelector('.toneka-portfolio-button');
    if (portfolioButton) {
        portfolioButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetSection = document.querySelector('#portfolio-section');
            if (targetSection) {
                const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
                const targetPosition = targetSection.offsetTop - headerHeight - 20;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    }
    
    // Animacje fade-in dla portfolio items
    const portfolioItems = document.querySelectorAll('.toneka-portfolio-item');
    portfolioItems.forEach((item, index) => {
        item.style.opacity = '0';
        
        setTimeout(() => {
            item.style.transition = 'opacity 0.4s ease';
            item.style.opacity = '1';
        }, index * 100 + 200);
    });
});
