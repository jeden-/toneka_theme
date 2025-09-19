/**
 * Navigation functionality for Toneka Theme
 */
(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const primaryMenu = document.querySelector('.toneka-primary-menu');

        if (menuToggle && primaryMenu) {
            menuToggle.addEventListener('click', function() {
                const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
                
                // Toggle aria-expanded
                menuToggle.setAttribute('aria-expanded', !isExpanded);
                
                // Toggle mobile menu class
                if (isExpanded) {
                    primaryMenu.classList.remove('mobile-menu-open');
                } else {
                    primaryMenu.classList.add('mobile-menu-open');
                }
            });

            // Close mobile menu when clicking outside
            document.addEventListener('click', function(event) {
                if (!menuToggle.contains(event.target) && !primaryMenu.contains(event.target)) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    primaryMenu.classList.remove('mobile-menu-open');
                }
            });

            // Close mobile menu on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    primaryMenu.classList.remove('mobile-menu-open');
                }
            });

            // Close mobile menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    menuToggle.setAttribute('aria-expanded', 'false');
                    primaryMenu.classList.remove('mobile-menu-open');
                }
            });
        }

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');
        anchorLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '') return;
                
                const target = document.querySelector(href);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Update cart count via AJAX (if WooCommerce is active)
        function updateCartCount() {
            if (typeof wc_add_to_cart_params === 'undefined') return;
            
            const cartCountElement = document.querySelector('.cart-count');
            if (!cartCountElement) return;

            fetch(wc_add_to_cart_params.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=toneka_get_cart_count'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    cartCountElement.textContent = data.data.count;
                    cartCountElement.style.display = data.data.count > 0 ? 'flex' : 'none';
                }
            })
            .catch(error => {
                console.log('Error updating cart count:', error);
            });
        }

        // Listen for cart updates
        document.body.addEventListener('added_to_cart', updateCartCount);
        document.body.addEventListener('removed_from_cart', updateCartCount);

        // Initial cart count update
        updateCartCount();
    });
})();
