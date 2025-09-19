<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @package Toneka
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}
?>

<div class="toneka-checkout-page">
    <div class="toneka-checkout-container">
        
        <h1 class="toneka-checkout-title">Checkout</h1>
        
        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <div class="toneka-checkout-content">
                
                <div class="toneka-checkout-billing">
                    <h2>Dane do faktury</h2>
                    
                    <?php if ( $checkout->get_checkout_fields() ) : ?>
                        <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
                        
                        <div class="toneka-checkout-fields">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                        </div>
                        
                        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                    <?php endif; ?>
                    
                    <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>
                </div>
                
                <div class="toneka-checkout-order">
                    <h2>Twoje zamówienie</h2>
                    
                    <div class="toneka-checkout-review">
                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            
                            <!-- Order items -->
                            <div class="toneka-checkout-items">
                                <?php
                                foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                                    $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                                    $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                                    if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                                        
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
                                        $image_url = wp_get_attachment_image_url(get_post_thumbnail_id($product_id), 'thumbnail');
                                        if (!$image_url) {
                                            $image_url = wc_placeholder_img_src('thumbnail');
                                        }
                                        ?>
                                        
                                        <div class="toneka-checkout-item">
                                            <div class="toneka-checkout-item-image">
                                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($_product->get_name()); ?>">
                                                <span class="toneka-checkout-item-qty"><?php echo $cart_item['quantity']; ?></span>
                                            </div>
                                            
                                            <div class="toneka-checkout-item-details">
                                                <h4 class="toneka-checkout-item-name"><?php echo $_product->get_name(); ?></h4>
                                                
                                                <?php if ($variant_text): ?>
                                                <div class="toneka-checkout-item-variant"><?php echo esc_html($variant_text); ?></div>
                                                <?php else: ?>
                                                <div class="toneka-checkout-item-variant">PLIKI CYFROWE</div>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <div class="toneka-checkout-item-price">
                                                <?php 
                                                // Display full price with sale information
                                                if ($_product->is_on_sale()) {
                                                    $regular_price = $_product->get_regular_price();
                                                    $sale_price = $_product->get_sale_price();
                                                    $total_regular = $regular_price * $cart_item['quantity'];
                                                    $total_sale = $sale_price * $cart_item['quantity'];
                                                    
                                                    echo '<div class="toneka-checkout-price-sale">';
                                                    echo '<span class="toneka-checkout-price-regular">' . wc_price($total_regular) . '</span>';
                                                    echo '<span class="toneka-checkout-price-current">' . wc_price($total_sale) . '</span>';
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="toneka-checkout-price-current">' . wc_price($_product->get_price() * $cart_item['quantity']) . '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            
                            <!-- Order totals -->
                            <div class="toneka-checkout-totals">
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
                                    <div class="toneka-checkout-total-savings">
                                        <span>Oszczędzasz łącznie: <?php echo wc_price($total_savings); ?> (<?php echo $savings_percent; ?>%)</span>
                                    </div>
                                    <?php
                                }
                                ?>
                                
                                <div class="toneka-checkout-total">
                                    <span>Razem: <?php echo WC()->cart->get_cart_total(); ?></span>
                                </div>
                            </div>
                            
                            <?php do_action( 'woocommerce_review_order_before_payment' ); ?>

                            <div id="payment" class="woocommerce-checkout-payment">
                                <?php if ( WC()->cart->needs_payment() ) : ?>
                                    <div class="toneka-payment-methods">
                                        <h3>Metoda płatności</h3>
                                        <?php
                                        if ( ! empty( $available_gateways = WC()->payment_gateways->get_available_payment_gateways() ) ) {
                                            foreach ( $available_gateways as $gateway ) {
                                                wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway ) );
                                            }
                                        } else {
                                            echo '<div class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</div>'; // @codingStandardsIgnoreLine
                                        }
                                        ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="form-row place-order">
                                    <noscript>
                                        <?php esc_html_e( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the &lt;em&gt;Update Totals&lt;/em&gt; button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ); ?>
                                        <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
                                    </noscript>

                                    <?php wc_get_template( 'checkout/terms.php' ); ?>

                                    <?php do_action( 'woocommerce_review_order_before_submit' ); ?>

                                    <button type="submit" class="toneka-place-order-button" name="woocommerce_checkout_place_order" id="place_order" value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>" data-value="<?php esc_attr_e( 'Place order', 'woocommerce' ); ?>">
                                        ZŁÓŻ ZAMÓWIENIE
                                    </button>

                                    <?php do_action( 'woocommerce_review_order_after_submit' ); ?>

                                    <?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
                                </div>
                            </div>

                            <?php do_action( 'woocommerce_review_order_after_payment' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>
                
            </div>

        </form>
        
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

