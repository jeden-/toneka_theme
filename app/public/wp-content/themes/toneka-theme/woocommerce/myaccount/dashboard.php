<?php
/**
 * My Account Dashboard
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
			'<strong>' . esc_html( wp_get_current_user()->display_name ) . '</strong>',
			esc_url( wc_logout_url() )
		);
		?>
	</p>

	<p>
		<?php
		/* translators: 1: Orders URL 2: Address URL 3: Account URL. */
		$dashboard_desc = __( 'Z pulpitu nawigacyjnego konta możesz przeglądać swoje <a href="%1$s">ostatnie zamówienia</a>, zarządzać <a href="%2$s">adresem rozliczeniowym</a> oraz <a href="%3$s">edytować swoje hasło i szczegóły konta</a>.', 'woocommerce' );
		if ( wc_shipping_enabled() ) {
			/* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
			$dashboard_desc = __( 'Z pulpitu nawigacyjnego konta możesz przeglądać swoje <a href="%1$s">ostatnie zamówienia</a>, zarządzać <a href="%2$s">adresami wysyłkowymi i rozliczeniowymi</a> oraz <a href="%3$s">edytować swoje hasło i szczegóły konta</a>.', 'woocommerce' );
		}
		printf(
			wp_kses( $dashboard_desc, $allowed_html ),
			esc_url( wc_get_endpoint_url( 'orders' ) ),
			esc_url( wc_get_endpoint_url( 'edit-address' ) ),
			esc_url( wc_get_endpoint_url( 'edit-account' ) )
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
