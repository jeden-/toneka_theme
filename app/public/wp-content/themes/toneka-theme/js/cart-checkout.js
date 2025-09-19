jQuery(document).ready(function($) {
    // Cart page functionality
    if ($('.toneka-cart-page').length) {
        
        // Remove item from cart
        $('.toneka-cart-remove').on('click', function(e) {
            e.preventDefault();
            
            const cartKey = $(this).data('cart-key');
            const $item = $(this).closest('.toneka-cart-item');
            
            // Add loading state
            $item.css('opacity', '0.5');
            
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_from_cart' ),
                type: 'POST',
                data: {
                    cart_item_key: cartKey
                },
                success: function(response) {
                    if (response.fragments) {
                        // Reload page to update cart
                        window.location.reload();
                    }
                },
                error: function() {
                    $item.css('opacity', '1');
                    console.error('Error removing item from cart');
                }
            });
        });
        
        // Update quantity
        $('.quantity-btn').on('click', function(e) {
            e.preventDefault();
            
            const $btn = $(this);
            const $input = $btn.siblings('.quantity-input');
            const cartKey = $btn.data('cart-key');
            const isPlus = $btn.hasClass('plus');
            let currentQty = parseInt($input.val());
            
            if (isPlus) {
                currentQty++;
            } else {
                currentQty = Math.max(1, currentQty - 1);
            }
            
            $input.val(currentQty);
            updateCartQuantity(cartKey, currentQty);
        });
        
        // Direct input change
        $('.quantity-input').on('change', function() {
            const cartKey = $(this).data('cart-key');
            const qty = Math.max(1, parseInt($(this).val()) || 1);
            $(this).val(qty);
            updateCartQuantity(cartKey, qty);
        });
        
        function updateCartQuantity(cartKey, quantity) {
            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'update_cart' ),
                type: 'POST',
                data: {
                    cart_item_key: cartKey,
                    quantity: quantity
                },
                success: function(response) {
                    if (response.fragments) {
                        // Update cart totals and refresh page
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                    }
                },
                error: function() {
                    console.error('Error updating cart quantity');
                }
            });
        }
    }
    
    // Checkout page functionality
    if ($('.toneka-checkout-page').length) {
        
        // Form validation and styling
        $('.toneka-checkout-fields input, .toneka-checkout-fields select').on('blur', function() {
            const $field = $(this);
            const value = $field.val().trim();
            
            // Simple validation styling
            if ($field.prop('required') && !value) {
                $field.css('border-color', '#ff4444');
            } else {
                $field.css('border-color', '#404040');
            }
        });
        
        // Clear validation styling on focus
        $('.toneka-checkout-fields input, .toneka-checkout-fields select').on('focus', function() {
            $(this).css('border-color', '#ffffff');
        });
        
        // Place order button loading state
        $('.toneka-place-order-button').on('click', function() {
            const $btn = $(this);
            $btn.prop('disabled', true);
            $btn.text('PRZETWARZANIE...');
            
            // Re-enable after timeout as fallback
            setTimeout(() => {
                $btn.prop('disabled', false);
                $btn.text('ZŁÓŻ ZAMÓWIENIE');
            }, 10000);
        });
    }
    
    // Account pages functionality
    if ($('.woocommerce-account').length || $('.woocommerce-form-login').length || $('.woocommerce-form-register').length) {
        
        // Form field styling
        $('.woocommerce-form-row input').on('blur', function() {
            const $field = $(this);
            const value = $field.val().trim();
            
            // Simple validation styling
            if ($field.prop('required') && !value) {
                $field.css('border-color', '#ff4444');
            } else {
                $field.css('border-color', '#404040');
            }
        });
        
        // Clear validation styling on focus
        $('.woocommerce-form-row input').on('focus', function() {
            $(this).css('border-color', '#ffffff');
        });
        
        // Button loading states
        $('.woocommerce-Button').on('click', function() {
            const $btn = $(this);
            const originalText = $btn.text();
            
            $btn.prop('disabled', true);
            $btn.text('PRZETWARZANIE...');
            
            // Re-enable after timeout as fallback
            setTimeout(() => {
                $btn.prop('disabled', false);
                $btn.text(originalText);
            }, 5000);
        });
    }
    
    // Fade-in animations for all WooCommerce pages
    if ($('.toneka-cart-page, .toneka-checkout-page, .woocommerce-account').length) {
        
        // Animate elements on page load
        $('.toneka-cart-item, .toneka-checkout-item').each(function(index) {
            $(this).css('opacity', 0).delay(index * 100 + 200).animate({ opacity: 1 }, 400);
        });
        
        $('.toneka-cart-totals, .toneka-checkout-totals').css('opacity', 0).delay(600).animate({ opacity: 1 }, 600);
    }
});

