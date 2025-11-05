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
                    <div class="toneka-checkout-item-qty"><?php echo esc_html( $cart_item['quantity'] ); ?></div>
                </div>

                <div class="toneka-checkout-item-details">
                    <div class="toneka-checkout-item-name">
                        <?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ); ?>
                    </div>
                    <?php
                    // Display variation attributes with tooltips
                    if ( ! empty( $cart_item['variation'] ) || $_product->is_type( 'variation' ) ) {
                        echo toneka_format_variation_attributes_for_display( $cart_item );
                    }
                    ?>
                </div>

                <div class="toneka-checkout-item-price">
                    <?php
                    echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
                    ?>
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

<?php if ( WC()->cart->needs_payment() ) : ?>
    <div class="toneka-payment-methods">
        <?php
        if ( ! empty( WC()->payment_gateways()->get_available_payment_gateways() ) ) {
            ?>
            <h3><?php esc_html_e( 'Metoda płatności', 'woocommerce' ); ?></h3>
            <?php
            wc_get_template( 'checkout/payment.php' );
        }
        ?>
    </div>
<?php endif; ?>

<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

<?php
echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    'woocommerce_order_button_html',
    '<button type="submit" class="toneka-place-order-button button alt' . esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ) . '" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( __( 'Place order', 'woocommerce' ) ) . '" data-value="' . esc_attr( __( 'Place order', 'woocommerce' ) ) . '">' . esc_html__( 'Złóż zamówienie', 'woocommerce' ) . '</button>'
); // @codingStandardsIgnoreLine
?>

<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>


