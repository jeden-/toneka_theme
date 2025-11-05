<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

$checkout = WC()->checkout();

// Check if cart is empty
if ( WC()->cart->is_empty() ) {
    ?>
    <div class="toneka-checkout-page">
        <div class="toneka-checkout-container">
            <h1 class="toneka-checkout-title"><?php esc_html_e( 'Kasa', 'woocommerce' ); ?></h1>
            <p class="woocommerce-info" style="text-align: center; color: var(--color-white); font-family: var(--font-primary); margin-top: 30px;">
                <?php esc_html_e( 'Twój koszyk jest pusty. ', 'woocommerce' ); ?>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" style="color: var(--color-white); text-decoration: underline;">
                    <?php esc_html_e( 'Powrót do sklepu', 'woocommerce' ); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
    get_footer( 'shop' );
    return;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	get_footer( 'shop' );
	return;
}

?>

<div class="toneka-checkout-page">
    <div class="toneka-checkout-container">
        <h1 class="toneka-checkout-title"><?php esc_html_e( 'Kasa', 'woocommerce' ); ?></h1>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="toneka-checkout-content">
                    <div class="toneka-checkout-billing">
                        <?php do_action( 'woocommerce_checkout_billing' ); ?>
                    </div>

                    <div class="toneka-checkout-order">
                        <h2><?php esc_html_e( 'Twoje zamówienie', 'woocommerce' ); ?></h2>

                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

        </form>

        <?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
    </div>
</div>

<?php
get_footer( 'shop' );

