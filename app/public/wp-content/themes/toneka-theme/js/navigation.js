/**
 * Navigation functionality for Toneka Theme
 */
(function() {
    'use strict';
    
    console.log('TONEKA NAV: navigation.js loaded!');
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('TONEKA NAV: DOMContentLoaded event fired');
        // Mobile menu toggle
        const menuToggle = document.querySelector('.menu-toggle');
        const menuClose = document.querySelector('.menu-close');
        const mobileMenuOverlay = document.querySelector('.mobile-menu-overlay');

        if (menuToggle && mobileMenuOverlay) {
            // Open mobile menu
            menuToggle.addEventListener('click', function() {
                const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';
                
                // Toggle aria-expanded
                menuToggle.setAttribute('aria-expanded', !isExpanded);
                
                // Toggle mobile menu overlay
                if (isExpanded) {
                    mobileMenuOverlay.classList.remove('is-active');
                } else {
                    mobileMenuOverlay.classList.add('is-active');
                }
            });

            // Close mobile menu
            if (menuClose) {
                menuClose.addEventListener('click', function() {
                    mobileMenuOverlay.classList.remove('is-active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                });
                }

            // Close mobile menu on escape key
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    mobileMenuOverlay.classList.remove('is-active');
                    menuToggle.setAttribute('aria-expanded', 'false');
                }
            });

            // Close mobile menu when window is resized to desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    mobileMenuOverlay.classList.remove('is-active');
                    menuToggle.setAttribute('aria-expanded', 'false');
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
            const ajaxUrl = typeof toneka_nav_params !== 'undefined' ? toneka_nav_params.ajax_url : 
                           (typeof wc_add_to_cart_params !== 'undefined' ? wc_add_to_cart_params.ajax_url : '/wp-admin/admin-ajax.php');
            
            const cartCountElement = document.querySelector('.cart-count');
            if (!cartCountElement) {
                console.warn('TONEKA NAV: Cart count element not found');
                return;
            }

            console.log('TONEKA NAV: Updating cart count via AJAX');
            fetch(ajaxUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=toneka_get_cart_count'
            })
            .then(response => response.json())
            .then(data => {
                console.log('TONEKA NAV: Cart count response:', data);
                if (data.success) {
                    cartCountElement.textContent = data.data.count;
                    cartCountElement.style.display = data.data.count > 0 ? 'flex' : 'none';
                    console.log('TONEKA NAV: Cart count updated to:', data.data.count);
                } else {
                    console.warn('TONEKA NAV: Cart count update failed:', data);
                }
            })
            .catch(error => {
                console.error('TONEKA NAV: Error updating cart count:', error);
            });
        }

        // Listen for cart updates (multiple event types for compatibility)
        document.body.addEventListener('added_to_cart', function(event) {
            console.log('TONEKA NAV: Native added_to_cart event received:', event);
            updateCartCount();
        });
        document.body.addEventListener('removed_from_cart', function(event) {
            console.log('TONEKA NAV: Native removed_from_cart event received:', event);
            updateCartCount();
        });
        
        // Also listen for jQuery events (fallback)
        if (typeof jQuery !== 'undefined') {
            jQuery(document.body).on('added_to_cart', function(event) {
                console.log('TONEKA NAV: jQuery added_to_cart event received:', event);
                updateCartCount();
            });
            jQuery(document.body).on('removed_from_cart', function(event) {
                console.log('TONEKA NAV: jQuery removed_from_cart event received:', event);
                updateCartCount();
            });
        }

        // Initial cart count update
        updateCartCount();
        
        // Make updateCartCount available globally
        window.updateCartCount = updateCartCount;
    });
})();
