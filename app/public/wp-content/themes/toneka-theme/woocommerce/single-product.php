<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woo.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>

<!-- DEBUG: TONEKA CUSTOM SINGLE PRODUCT TEMPLATE LOADED -->
	<?php
		/**
		 * woocommerce_before_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20 (removed - we add it manually)
		 */
		// do_action( 'woocommerce_before_main_content' );
	?>

		<?php while ( have_posts() ) : ?>
			<?php the_post(); ?>

			<!-- New Product Layout - Image Left Sticky, Content Right -->
			<div class="toneka-product-layout">
			    <!-- Left Side - Sticky Player -->
			    <div class="toneka-product-image-container">
			        <div class="toneka-product-image">
			            <div class="toneka-player-lazy-wrapper">
			                <?php toneka_display_product_samples_player(); ?>
			            </div>
			        </div>
                </div>

			    <!-- Right Side - All Content -->
			    <div class="toneka-product-content">
			        <?php toneka_display_product_metadata(); ?>
			        <?php toneka_output_variable_product_selector(); ?>
                </div>
            </div>


			<!-- Suggested Merch Section -->
			<div class="toneka-suggested-merch-section">
			    <?php toneka_display_suggested_merch(); ?>
			</div>

			<!-- Suggested Audio Section -->
			<div class="toneka-suggested-audio-section">
			    <?php toneka_display_suggested_audio(); ?>
			</div>

			<!-- Related Products Section -->
			<!-- <div class="toneka-related-products">
			    <?php toneka_display_related_products(); ?>
			</div> -->


			
		<?php endwhile; // end of the loop. ?>

	<?php
		/**
		 * woocommerce_after_main_content hook.
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		// do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook.
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		//do_action( 'woocommerce_sidebar' );
	?>

<?php
get_footer( 'shop' );

/* Omit closing PHP tag in the file that is only PHP, like this one. */