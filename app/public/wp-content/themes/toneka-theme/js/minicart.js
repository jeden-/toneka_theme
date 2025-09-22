jQuery(document).ready(function($) {
    'use strict';
    
    console.log('🚀 TONEKA MINICART: minicart.js loaded and ready!');
    console.log('🚀 TONEKA MINICART: jQuery version:', $.fn.jquery);
    console.log('🚀 TONEKA MINICART: Document body:', $(document.body).length);
    // minicart.js loaded successfully

    // Otwieranie minikoszyka
    $(document).on('click', '.cart-contents, .toneka-cart-icon', function(e) {
        e.preventDefault();
        console.log('TONEKA MINICART: Cart icon clicked');
        openMinicart();
    });

    // Zamykanie minikoszyka
    $(document).on('click', '.toneka-minicart-close, .toneka-minicart-overlay', function(e) {
        e.preventDefault();
        closeMinicart();
    });

    // Escape key to close
    $(document).on('keyup', function(e) {
        if (e.keyCode === 27) { // ESC key
            closeMinicart();
        }
    });

    // Quantity controls
    $(document).on('click', '.quantity-btn.minus', function(e) {
        e.preventDefault();
        const input = $(this).siblings('.quantity-input');
        const currentVal = parseInt(input.val()) || 1;
        if (currentVal > 1) {
            input.val(currentVal - 1);
            updateCartQuantity($(this).data('cart-key'), currentVal - 1);
        }
    });

    $(document).on('click', '.quantity-btn.plus', function(e) {
        e.preventDefault();
        const input = $(this).siblings('.quantity-input');
        const currentVal = parseInt(input.val()) || 1;
        input.val(currentVal + 1);
        updateCartQuantity($(this).data('cart-key'), currentVal + 1);
    });

    $(document).on('change', '.quantity-input', function() {
        const newVal = parseInt($(this).val()) || 1;
        if (newVal < 1) {
            $(this).val(1);
            return;
        }
        updateCartQuantity($(this).data('cart-key'), newVal);
    });

    // Remove item
    $(document).on('click', '.toneka-minicart-remove', function(e) {
        e.preventDefault();
        const cartKey = $(this).data('cart-key');
        removeCartItem(cartKey);
    });

            // Order button
            $(document).on('click', '.toneka-minicart-order-btn', function(e) {
                e.preventDefault();
                // Redirect to checkout
                const checkoutUrl = typeof toneka_minicart_params !== 'undefined' ? 
                    toneka_minicart_params.checkout_url : 
                    (typeof wc_add_to_cart_params !== 'undefined' ? wc_add_to_cart_params.checkout_url : '/checkout/');
                window.location.href = checkoutUrl;
            });

            // Upsell quantity controls
            $(document).on('click', '.upsell-quantity-btn.minus', function(e) {
                e.preventDefault();
                const input = $(this).siblings('.upsell-quantity-input');
                const currentVal = parseInt(input.val()) || 1;
                if (currentVal > 1) {
                    input.val(currentVal - 1);
                }
            });

            $(document).on('click', '.upsell-quantity-btn.plus', function(e) {
                e.preventDefault();
                const input = $(this).siblings('.upsell-quantity-input');
                const currentVal = parseInt(input.val()) || 1;
                input.val(currentVal + 1);
            });

            // Add upsell product
            $(document).on('click', '.toneka-minicart-upsell-add-btn', function(e) {
                e.preventDefault();
                console.log('TONEKA MINICART: Upsell add button clicked');
                
                const $button = $(this);
                const productId = $button.data('product-id');
                const quantity = parseInt($button.siblings('.toneka-minicart-upsell-quantity').find('.upsell-quantity-input').val()) || 1;
                
                console.log('TONEKA MINICART: Product ID:', productId, 'Quantity:', quantity);
                
                if (!productId) {
                    console.error('TONEKA MINICART: No product ID found');
                    return;
                }
                
                // Disable button during request
                $button.prop('disabled', true).text('DODAJĘ...');
                
                addUpsellProduct(productId, quantity, $button);
            });

    // Aktualizacja minikoszyka po dodaniu produktu przez AJAX
    console.log('TONEKA MINICART: Setting up added_to_cart event listener on body:', $(document.body));
    console.log('TONEKA MINICART: Body selector test:', $('body').length);
    
    $(document.body).on('added_to_cart', function(event, fragments, cart_hash) {
        console.log('TONEKA MINICART: *** JQUERY EVENT TRIGGERED! ***');
        console.log('TONEKA MINICART: Product added to cart event triggered!');
        console.log('TONEKA MINICART: Event details:', event, fragments, cart_hash);
        // Automatycznie otwórz minikoszyk po dodaniu produktu
        setTimeout(function() {
            console.log('TONEKA MINICART: Refreshing and opening minicart');
            refreshMinicart();
            openMinicart();
        }, 300);
    });
    
    // Test backup listener
    $('body').on('added_to_cart', function(event, fragments, cart_hash) {
        console.log('TONEKA MINICART: *** BACKUP LISTENER TRIGGERED! ***');
    });
    
    // Event listeners are set up - no test trigger needed
    
    // Additional test listener
    $(document).on('added_to_cart', 'body', function(event, fragments, cart_hash) {
        console.log('TONEKA MINICART: Alternative listener triggered!');
    });
    
    // Global test functions
    window.toneka_test_minicart = function() {
        console.log('TONEKA TEST: Testing minicart opening');
        openMinicart();
    };
    
    window.toneka_trigger_cart_event = function() {
        console.log('TONEKA TEST: Triggering added_to_cart event');
        $(document.body).trigger('added_to_cart');
    };
    
    // Will make refreshMinicart available globally after it's defined

    // Functions
    function openMinicart() {
        const minicart = $('.toneka-minicart');
        const overlay = $('.toneka-minicart-overlay');
        
        if (minicart.length && overlay.length) {
            console.log('TONEKA MINICART: Opening minicart');
            minicart.addClass('is-active');
            overlay.addClass('is-active');
            $('body').addClass('minicart-open');
        } else {
            console.warn('TONEKA MINICART: Minicart elements not found');
        }
    }

    function closeMinicart() {
        $('.toneka-minicart').removeClass('is-active');
        $('.toneka-minicart-overlay').removeClass('is-active');
        $('body').removeClass('minicart-open');
    }

    function updateCartQuantity(cartKey, quantity) {
        const ajaxParams = typeof toneka_minicart_params !== 'undefined' ? toneka_minicart_params : wc_add_to_cart_params;
        
        $.ajax({
            url: ajaxParams.ajax_url,
            type: 'POST',
            data: {
                action: 'toneka_update_cart_quantity',
                cart_key: cartKey,
                quantity: quantity,
                security: ajaxParams.wc_ajax_nonce
            },
            success: function(response) {
                if (response.success) {
                    refreshMinicart();
                }
            }
        });
    }

    function removeCartItem(cartKey) {
        const ajaxParams = typeof toneka_minicart_params !== 'undefined' ? toneka_minicart_params : wc_add_to_cart_params;
        
        $.ajax({
            url: ajaxParams.ajax_url,
            type: 'POST',
            data: {
                action: 'toneka_remove_cart_item',
                cart_key: cartKey,
                security: ajaxParams.wc_ajax_nonce
            },
            success: function(response) {
                if (response.success) {
                    refreshMinicart();
                }
            }
        });
    }

            function addUpsellProduct(productId, quantity = 1, $button = null) {
                const ajaxParams = typeof toneka_minicart_params !== 'undefined' ? toneka_minicart_params : wc_add_to_cart_params;
                
                console.log('TONEKA MINICART: Starting AJAX request');
                console.log('TONEKA MINICART: AJAX URL:', ajaxParams.ajax_url);
                console.log('TONEKA MINICART: Security nonce:', ajaxParams.wc_ajax_nonce);
                
                $.ajax({
                    url: ajaxParams.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'toneka_ajax_add_to_cart',
                        product_id: productId,
                        quantity: quantity,
                        security: ajaxParams.wc_ajax_nonce
                    },
                    success: function(response) {
                        console.log('TONEKA MINICART: AJAX Success response:', response);
                        
                        if ($button) {
                            $button.prop('disabled', false).text('DODAJ DO KOSZYKA');
                        }
                        
                        if (response.success) {
                            console.log('TONEKA MINICART: Product added successfully');
                            refreshMinicart();
                            // Trigger added_to_cart event for auto-open
                            $(document.body).trigger('added_to_cart');
                            // Show success message
                            showNotification('Produkt został dodany do koszyka!');
                        } else {
                            console.error('TONEKA MINICART: Server returned error:', response);
                            showNotification('Błąd: ' + (response.data || 'Nieznany błąd'));
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('TONEKA MINICART: AJAX Error:', error);
                        console.error('TONEKA MINICART: Response:', xhr.responseText);
                        
                        if ($button) {
                            $button.prop('disabled', false).text('DODAJ DO KOSZYKA');
                        }
                        
                        showNotification('Błąd podczas dodawania produktu: ' + error);
                    }
                });
            }

    function refreshMinicart() {
        const ajaxParams = typeof toneka_minicart_params !== 'undefined' ? toneka_minicart_params : wc_add_to_cart_params;
        
        $.ajax({
            url: ajaxParams.ajax_url,
            type: 'POST',
            data: {
                action: 'toneka_refresh_minicart',
                security: ajaxParams.wc_ajax_nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.toneka-minicart').html(response.data.minicart_html);
                }
            }
        });
    }

    function showNotification(message) {
        // Simple notification (you can enhance this)
        const notification = $('<div class="toneka-notification">' + message + '</div>');
        $('body').append(notification);
        
        setTimeout(function() {
            notification.addClass('show');
        }, 100);
        
        setTimeout(function() {
            notification.removeClass('show');
            setTimeout(function() {
                notification.remove();
            }, 300);
        }, 3000);
    }

    // Make functions available globally
    window.refreshMinicart = refreshMinicart;
    window.openMinicart = openMinicart;

});

