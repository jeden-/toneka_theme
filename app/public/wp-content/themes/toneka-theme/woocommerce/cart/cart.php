<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * @package Toneka
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="toneka-cart-page">
    <div class="toneka-cart-container">
        
        <h1 class="toneka-cart-title">Cart</h1>
        
        <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="toneka-cart-items">
                <?php
                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                        $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
                        
                        // Get variant text
                        $variant_text = '';
                        if (isset($cart_item['variation']) && !empty($cart_item['variation'])) {
                            $variant_parts = array();
                            foreach ($cart_item['variation'] as $key => $value) {
                                if (strpos($key, 'attribute_') === 0) {
                                    $attribute_name = str_replace('attribute_', '', $key);
                                    $variant_parts[] = strtoupper($value);
                                }
                            }
                            $variant_text = implode(', ', $variant_parts);
                        }
                        
                        // Get product image
                        $thumbnail = $_product->get_image();
                        $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($product_id), 'full');
                        if (!$image_url) {
                            $image_url = wc_placeholder_img_src('full');
                        }
                        ?>
                        
                        <div class="toneka-cart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                            <div class="toneka-cart-item-image">
                                <?php if ($product_permalink): ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($_product->get_name()); ?>">
                                    </a>
                                <?php else: ?>
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($_product->get_name()); ?>">
                                <?php endif; ?>
                            </div>
                            
                            <div class="toneka-cart-item-content">
                                <div class="toneka-cart-item-header">
                                    <button class="toneka-cart-remove" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" title="Usuń produkt">
                                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M20.25 5H16.5V4.25C16.5 3.65326 16.2629 3.08097 15.841 2.65901C15.419 2.23705 14.8467 2 14.25 2H9.75C9.15326 2 8.58097 2.23705 8.15901 2.65901C7.73705 3.08097 7.5 3.65326 7.5 4.25V5H3.75C3.55109 5 3.36032 5.07902 3.21967 5.21967C3.07902 5.36032 3 5.55109 3 5.75C3 5.94891 3.07902 6.13968 3.21967 6.28033C3.36032 6.42098 3.55109 6.5 3.75 6.5H4.5V20C4.5 20.3978 4.65804 20.7794 4.93934 21.0607C5.22064 21.342 5.60218 21.5 6 21.5H18C18.3978 21.5 18.7794 21.342 19.0607 21.0607C19.342 20.7794 19.5 20.3978 19.5 20V6.5H20.25C20.4489 6.5 20.6397 6.42098 20.7803 6.28033C20.921 6.13968 21 5.94891 21 5.75C21 5.55109 20.921 5.36032 20.7803 5.21967C20.6397 5.07902 20.4489 5 20.25 5Z" stroke="white" stroke-width="1.5"/>
                                        </svg>
                                    </button>
                                    
                                    <div class="toneka-cart-quantity">
                                        <button class="quantity-btn minus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                        <input type="number" value="<?php echo $cart_item['quantity']; ?>" min="1" class="quantity-input" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" name="cart[<?php echo $cart_item_key; ?>][qty]">
                                        <button class="quantity-btn plus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                                    </div>
                                </div>
                                
                                <div class="toneka-cart-item-details">
                                    <h4 class="toneka-cart-item-name">
                                        <?php if ($product_permalink): ?>
                                            <a href="<?php echo esc_url($product_permalink); ?>"><?php echo $_product->get_name(); ?></a>
                                        <?php else: ?>
                                            <?php echo $_product->get_name(); ?>
                                        <?php endif; ?>
                                    </h4>
                                    
                                    <?php if ($variant_text): ?>
                                    <div class="toneka-cart-item-variant"><?php echo esc_html($variant_text); ?></div>
                                    <?php else: ?>
                                    <div class="toneka-cart-item-variant">PLIKI CYFROWE</div>
                                    <?php endif; ?>
                                    
                                    <div class="toneka-cart-item-price">
                                        <?php 
                                        // Display full price with sale information
                                        if ($_product->is_on_sale()) {
                                            $regular_price = $_product->get_regular_price();
                                            $sale_price = $_product->get_sale_price();
                                            $savings = ($regular_price - $sale_price) * $cart_item['quantity'];
                                            $savings_percent = round((($regular_price - $sale_price) / $regular_price) * 100);
                                            
                                            echo '<div class="toneka-cart-price-sale">';
                                            echo '<span class="toneka-cart-price-regular">' . wc_price($regular_price * $cart_item['quantity']) . '</span> ';
                                            echo '<span class="toneka-cart-price-current">' . wc_price($sale_price * $cart_item['quantity']) . '</span>';
                                            echo '<div class="toneka-cart-savings">Oszczędzasz: ' . wc_price($savings) . ' (' . $savings_percent . '%)</div>';
                                            echo '</div>';
                                        } else {
                                            echo '<div class="toneka-cart-price-regular">' . wc_price($_product->get_price() * $cart_item['quantity']) . '</div>';
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php
                    }
                }
                ?>
            </div>

            <div class="toneka-cart-actions">
                <button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>
                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
            </div>

            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>

        <div class="toneka-cart-totals">
            <?php
            // Calculate total savings
            $total_savings = 0;
            $total_regular = 0;
            
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $quantity = $cart_item['quantity'];
                
                if ($product->is_on_sale()) {
                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();
                    $item_savings = ($regular_price - $sale_price) * $quantity;
                    $total_savings += $item_savings;
                    $total_regular += $regular_price * $quantity;
                }
            }
            
            if ($total_savings > 0) {
                $savings_percent = round(($total_savings / $total_regular) * 100);
                ?>
                <div class="toneka-cart-total-savings">
                    <span>Oszczędzasz łącznie: <?php echo wc_price($total_savings); ?> (<?php echo $savings_percent; ?>%)</span>
                </div>
                <?php
            }
            ?>
            
            <div class="toneka-cart-total">
                <span>Razem: <?php echo WC()->cart->get_cart_total(); ?></span>
            </div>
            
            <div class="toneka-cart-checkout">
                <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="toneka-checkout-button">
                    ZAMÓW
                </a>
            </div>
        </div>
        
    </div>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

