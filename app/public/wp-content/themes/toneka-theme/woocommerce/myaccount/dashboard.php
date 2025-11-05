<?php
/**
 * My Account dashboard
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

defined( 'ABSPATH' ) || exit;

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="toneka-dashboard-content">
	<p class="woocommerce-dashboard-intro">
		<?php
		printf(
			/* translators: 1: user display name 2: logout url */
			wp_kses( __( 'Witaj %1$s (nie jesteś %1$s? <a href="%2$s">Wyloguj się</a>)', 'woocommerce' ), $allowed_html ),
			'<strong>' . esc_html( $current_user->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
		?>
	</p>

	<?php
		/**
		 * My Account dashboard.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_dashboard' );

		/**
		 * Deprecated woocommerce_before_my_account action.
		 *
		 * @deprecated 2.6.0
		 */
		do_action( 'woocommerce_before_my_account' );

		/**
		 * Deprecated woocommerce_after_my_account action.
		 *
		 * @deprecated 2.6.0
		 */
		do_action( 'woocommerce_after_my_account' );
	?>
</div>

