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

			<!-- Hero Section - Idealne kwadraty 50/50 -->
			<div class="toneka-hero-section">
			    <div class="toneka-hero-left">
			        <!-- Główna zawartość hero -->
			        <div class="toneka-hero-content">
			            
			            <?php woocommerce_breadcrumb(); ?>
			            <!-- Tytuł produktu -->
			            <h1 class="toneka-product-title"><?php the_title(); ?></h1>
			            
			            <!-- Przycisk posłuchaj -->
			            <a href="#player-section" class="toneka-listen-button animated-arrow-button">
			                <span class="button-text">POSŁUCHAJ FRAGMENTU</span>
			                <div class="button-arrow">
			                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
			                        <path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
			                        <path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/>
			                        <path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/>
			                    </svg>
			                </div>
			            </a>
			        </div>
			    </div>
			    
			    <div class="toneka-hero-right">
			        <!-- Okładka produktu -->
			        <div class="toneka-product-image">
			            <?php toneka_show_product_images_custom(); ?>
			        </div>
			    </div>
			</div>

			<!-- Product Info Section - 50/50 -->
			<div class="toneka-product-info-section">
			    <div class="toneka-product-details">
			        <?php toneka_display_product_metadata(); ?>
			        <?php toneka_output_variable_product_selector(); ?>
			    </div>
			    
			    <div class="toneka-audio-section" id="player-section">
			        <?php toneka_display_product_samples_player(); ?>
			    </div>
			</div>

			<!-- Sekcja "Więcej" -->
			<div class="toneka-more-section">
			    <div class="toneka-more-content animated-arrow-button">
			        <span>SŁUCHOWISKA, KSIĄŻKI, SZTUKA</span>
			        <div class="button-arrow">
			            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
			                <path d="M5 10h10m0 0l-3-3m3 3l-3 3" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			            </svg>
			        </div>
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
			<div class="toneka-related-products">
			    <?php toneka_display_related_products(); ?>
			</div>



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