<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * @see     https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

?>
<div class="toneka-cart-totals">
    <?php do_action( 'woocommerce_before_cart_totals' ); ?>

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
        <div class="toneka-cart-total-savings">
            <span><?php echo esc_html__( 'Oszczędzasz łącznie:', 'woocommerce' ); ?> <?php echo wc_price( $total_savings ); ?> (<?php echo esc_html( $savings_percent ); ?>%)</span>
        </div>
        <?php
    }
    ?>

    <div class="toneka-cart-total">
        <?php
        do_action( 'woocommerce_cart_totals_before_order_total' );
        ?>
        <span><?php echo esc_html__( 'Razem:', 'woocommerce' ); ?> <?php wc_cart_totals_order_total_html(); ?></span>
        <?php
        do_action( 'woocommerce_cart_totals_after_order_total' );
        ?>
    </div>

    <div class="wc-proceed-to-checkout">
        <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
    </div>

    <?php do_action( 'woocommerce_after_cart_totals' ); ?>
</div>



