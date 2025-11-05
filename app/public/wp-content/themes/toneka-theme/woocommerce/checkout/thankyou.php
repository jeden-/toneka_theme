<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * @see https://woo.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.7.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );
?>

<div class="toneka-thankyou-page">
    <div class="toneka-thankyou-container">
        <?php if ( $order ) : ?>

            <?php do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>

            <?php if ( $order->has_status( 'failed' ) ) : ?>

                <h1 class="toneka-thankyou-title"><?php esc_html_e( 'Płatność nie powiodła się', 'woocommerce' ); ?></h1>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
                    <?php esc_html_e( 'Niestety Twoje zamówienie nie mogło zostać przetworzone, ponieważ płatność nie powiodła się. Możesz spróbować ponownie lub skontaktować się z nami, jeśli problem będzie się powtarzał.', 'woocommerce' ); ?>
                </p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button toneka-retry-payment-button">
                        <?php esc_html_e( 'Spróbuj ponownie', 'woocommerce' ); ?>
                    </a>
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button toneka-my-account-button">
                            <?php esc_html_e( 'Moje konto', 'woocommerce' ); ?>
                        </a>
                    <?php endif; ?>
                </p>

            <?php else : ?>

                <h1 class="toneka-thankyou-title"><?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Dziękujemy za zamówienie!', 'woocommerce' ), $order ) ); ?></h1>

                <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                    <?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Twoje zamówienie zostało przyjęte i jest przetwarzane.', 'woocommerce' ), $order ) ); ?>
                </p>

                <div class="toneka-order-details">
                    <div class="toneka-order-info">
                        <div class="toneka-order-info-row">
                            <span class="toneka-order-info-label"><?php esc_html_e( 'Numer zamówienia:', 'woocommerce' ); ?></span>
                            <span class="toneka-order-info-value"><?php echo esc_html( $order->get_order_number() ); ?></span>
                        </div>
                        <div class="toneka-order-info-row">
                            <span class="toneka-order-info-label"><?php esc_html_e( 'Data:', 'woocommerce' ); ?></span>
                            <span class="toneka-order-info-value"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
                        </div>
                        <div class="toneka-order-info-row">
                            <span class="toneka-order-info-label"><?php esc_html_e( 'Email:', 'woocommerce' ); ?></span>
                            <span class="toneka-order-info-value"><?php echo esc_html( $order->get_billing_email() ); ?></span>
                        </div>
                        <div class="toneka-order-info-row">
                            <span class="toneka-order-info-label"><?php esc_html_e( 'Razem:', 'woocommerce' ); ?></span>
                            <span class="toneka-order-info-value"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></span>
                        </div>
                        <?php if ( $order->get_payment_method_title() ) : ?>
                            <div class="toneka-order-info-row">
                                <span class="toneka-order-info-label"><?php esc_html_e( 'Metoda płatności:', 'woocommerce' ); ?></span>
                                <span class="toneka-order-info-value"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
                <?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

                <div class="toneka-thankyou-actions">
                    <?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="toneka-my-account-button">
                            <?php esc_html_e( 'Moje konto', 'woocommerce' ); ?>
                        </a>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="toneka-continue-shopping-button">
                        <?php esc_html_e( 'Kontynuuj zakupy', 'woocommerce' ); ?>
                    </a>
                </div>

            <?php endif; ?>

        <?php else : ?>

            <h1 class="toneka-thankyou-title"><?php esc_html_e( 'Dziękujemy za zamówienie!', 'woocommerce' ); ?></h1>

            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
                <?php echo esc_html( apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Twoje zamówienie zostało przyjęte i jest przetwarzane.', 'woocommerce' ), null ) ); ?>
            </p>

        <?php endif; ?>
    </div>
</div>

<?php
get_footer( 'shop' );

