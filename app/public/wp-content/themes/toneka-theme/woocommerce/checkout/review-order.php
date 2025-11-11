<?php
/**
 * Review Order Before Payment
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="toneka-checkout-items">
    <?php
    do_action( 'woocommerce_review_order_before_cart_contents' );

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

        if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
            ?>
            <div class="toneka-checkout-item">
                <div class="toneka-checkout-item-image">
                    <?php
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
                    echo $thumbnail; // PHPCS: XSS ok.
                    ?>
                </div>

                <div class="toneka-checkout-item-content">
                    <div class="toneka-checkout-item-header">
                        <a href="<?php echo esc_url( wc_get_cart_url() . '?remove_item=' . $cart_item_key ); ?>" class="toneka-checkout-remove" aria-label="<?php esc_attr_e( 'Remove this item', 'woocommerce' ); ?>" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20.25 5H16.5V4.25C16.5 3.65326 16.2629 3.08097 15.841 2.65901C15.419 2.23705 14.8467 2 14.25 2H9.75C9.15326 2 8.58097 2.23705 8.15901 2.65901C7.73705 3.08097 7.5 3.65326 7.5 4.25V5H3.75C3.55109 5 3.36032 5.07902 3.21967 5.21967C3.07902 5.36032 3 5.55109 3 5.75C3 5.94891 3.07902 6.13968 3.21967 6.28033C3.36032 6.42098 3.55109 6.5 3.75 6.5H4.5V20C4.5 20.3978 4.65804 20.7794 4.93934 21.0607C5.22064 21.342 5.60218 21.5 6 21.5H18C18.3978 21.5 18.7794 21.342 19.0607 21.0607C19.342 20.7794 19.5 20.3978 19.5 20V6.5H20.25C20.4489 6.5 20.6397 6.42098 20.7803 6.28033C20.921 6.13968 21 5.94891 21 5.75C21 5.55109 20.921 5.36032 20.7803 5.21967C20.6397 5.07902 20.4489 5 20.25 5ZM10.5 16.25C10.5 16.4489 10.421 16.6397 10.2803 16.7803C10.1397 16.921 9.94891 17 9.75 17C9.55109 17 9.36032 16.921 9.21967 16.7803C9.07902 16.6397 9 16.4489 9 16.25V10.25C9 10.0511 9.07902 9.86032 9.21967 9.71967C9.36032 9.57902 9.55109 9.5 9.75 9.5C9.94891 9.5 10.1397 9.57902 10.2803 9.71967C10.421 9.86032 10.5 10.0511 10.5 10.25V16.25ZM15 16.25C15 16.4489 14.921 16.6397 14.7803 16.7803C14.6397 16.921 14.4489 17 14.25 17C14.0511 17 13.8603 16.921 13.7197 16.7803C13.579 16.6397 13.5 16.4489 13.5 16.25V10.25C13.5 10.0511 13.579 9.86032 13.7197 9.71967C13.8603 9.57902 14.0511 9.5 14.25 9.5C14.4489 9.5 14.6397 9.57902 14.7803 9.71967C14.921 9.86032 15 10.0511 15 10.25V16.25ZM15 5H9V4.25C9 4.05109 9.07902 3.86032 9.21967 3.71967C9.36032 3.57902 9.55109 3.5 9.75 3.5H14.25C14.4489 3.5 14.6397 3.57902 14.7803 3.71967C14.921 3.86032 15 4.05109 15 4.25V5Z" fill="white" fill-opacity="0.4"/>
                            </svg>
                        </a>
                        
                        <div class="toneka-checkout-quantity">
                            <button type="button" class="quantity-btn minus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">-</button>
                            <input type="number" value="<?php echo esc_attr( $cart_item['quantity'] ); ?>" min="1" class="quantity-input" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">
                            <button type="button" class="quantity-btn plus" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">+</button>
                        </div>
                    </div>
                    
                    <div class="toneka-checkout-item-details">
                        <h4 class="toneka-checkout-item-name">
                            <?php
                            // Get parent product name (without variation attributes)
                            $product_name = $_product->get_name();
                            if ( $_product->is_type( 'variation' ) ) {
                                $parent_product = wc_get_product( $_product->get_parent_id() );
                                if ( $parent_product ) {
                                    $product_name = $parent_product->get_name();
                                }
                            }
                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $product_name, $cart_item, $cart_item_key ) );
                            ?>
                        </h4>
                        
                        <?php
                        // Display variation attributes with tooltips
                        $variant_text = '';
                        if ( ! empty( $cart_item['variation'] ) || $_product->is_type( 'variation' ) ) {
                            $variant_text = toneka_format_variation_attributes_for_display( $cart_item );
                        }
                        if ( ! empty( $variant_text ) ) {
                            echo '<div class="toneka-checkout-item-variant-wrapper">' . $variant_text . '</div>'; // PHPCS: XSS ok.
                        }
                        ?>
                        
                        <div class="toneka-checkout-item-price">
                            <?php
                            // Display full price with sale information (unit price like in minicart)
                            if ( $_product->is_on_sale() ) {
                                $regular_price = $_product->get_regular_price();
                                $sale_price = $_product->get_sale_price();
                                $savings = $regular_price - $sale_price;
                                $savings_percent = round( ( $savings / $regular_price ) * 100 );
                                
                                echo '<div class="toneka-checkout-price-sale">';
                                echo '<span class="toneka-checkout-price-regular">' . wc_price( $regular_price ) . '</span>';
                                echo '<span class="toneka-checkout-price-current">' . wc_price( $sale_price ) . '</span>';
                                echo '<div class="toneka-checkout-savings">Oszczędzasz: ' . wc_price( $savings ) . ' (' . $savings_percent . '%)</div>';
                                echo '</div>';
                            } else {
                                echo '<div class="toneka-checkout-price-regular">' . wc_price( $_product->get_price() ) . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }

    do_action( 'woocommerce_review_order_after_cart_contents' );
    ?>
</div>

<div class="toneka-checkout-totals">
    <?php
    // Calculate total savings
    $total_savings = 0;
    $total_regular = 0;
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $product = $cart_item['data'];
        $quantity = $cart_item['quantity'];
        
        if ( $product->is_on_sale() ) {
            $regular_price = $product->get_regular_price();
            $sale_price = $product->get_sale_price();
            $item_savings = ( $regular_price - $sale_price ) * $quantity;
            $total_savings += $item_savings;
            $total_regular += $regular_price * $quantity;
        }
    }
    
    if ( $total_savings > 0 ) {
        $savings_percent = round( ( $total_savings / $total_regular ) * 100 );
        ?>
        <div class="toneka-checkout-total-savings">
            <span><?php echo esc_html__( 'Oszczędzasz łącznie:', 'woocommerce' ); ?> <?php echo wc_price( $total_savings ); ?> (<?php echo esc_html( $savings_percent ); ?>%)</span>
        </div>
        <?php
    }
    ?>

    <div class="toneka-checkout-total">
        <?php
        do_action( 'woocommerce_review_order_before_order_total' );
        ?>
        <span><?php echo esc_html__( 'Razem:', 'woocommerce' ); ?> <?php wc_cart_totals_order_total_html(); ?></span>
        <?php
        do_action( 'woocommerce_review_order_after_order_total' );
        ?>
    </div>
</div>

<?php do_action( 'woocommerce_review_order_before_payment' ); ?>

<?php do_action( 'woocommerce_review_order_after_payment' ); ?>

