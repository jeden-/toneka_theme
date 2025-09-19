<?php
/**
 * Theme Inc Loader
 *
 * @package boldnote-child
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Core functionalities
// require_once get_stylesheet_directory() . '/inc/core/class-main.php';

// Elementor Integration
// require_once get_stylesheet_directory() . '/inc/elementor/elementor.php';

// WooCommerce Integration
// require_once get_stylesheet_directory() . '/inc/woocommerce/woocommerce.php';

// Meta Fields
// require_once get_stylesheet_directory() . '/inc/meta-fields/meta-fields.php';

// Assets
require_once get_stylesheet_directory() . '/inc/assets.php';

// Other modules... 