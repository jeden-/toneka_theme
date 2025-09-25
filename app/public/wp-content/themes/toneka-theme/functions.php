<?php

if ( ! function_exists( 'toneka_theme_setup' ) ) {
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 */
	function toneka_theme_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'tonekatheme', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'tonekatheme' ),
				'footer'  => esc_html__( 'Footer Menu', 'tonekatheme' ),
			)
		);
		
		// Add WooCommerce support
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'toneka_theme_setup' );

// ==================================================================
// CUSTOM MINI-CART
// ==================================================================

/**
 * Display custom mini-cart
 */
function toneka_display_custom_minicart() {
    if (WC()->cart->is_empty()) {
        echo '<div class="toneka-minicart-empty">
                <p>Twój koszyk jest pusty</p>
              </div>';
        return;
    }
    
    ?>
    <div class="toneka-minicart-content">
        <div class="toneka-minicart-header">
            <h3>Twój koszyk</h3>
            <button class="toneka-minicart-close">&times;</button>
        </div>
        
        <div class="toneka-minicart-items">
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $product = $cart_item['data'];
                $product_id = $cart_item['product_id'];
                $variation_id = $cart_item['variation_id'];
                $quantity = $cart_item['quantity'];
                
                // Get product image
                $product_image = wp_get_attachment_image_src(get_post_thumbnail_id($product_id), 'thumbnail');
                $image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
                
                // Get variant info
                $variant_text = '';
                if ($variation_id) {
                    $variation = wc_get_product($variation_id);
                    if ($variation && is_object($variation) && method_exists($variation, 'get_variation_attributes')) {
                        $attributes = $variation->get_variation_attributes();
                        $variant_text = implode(', ', $attributes);
                    }
                }
                
                ?>
                <div class="toneka-minicart-item" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                    <div class="toneka-minicart-item-image">
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                    </div>
                    
                    <div class="toneka-minicart-item-content">
                        <div class="toneka-minicart-item-header">
                            <button class="toneka-minicart-remove" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M20.25 5H16.5V4.25C16.5 3.65326 16.2629 3.08097 15.841 2.65901C15.419 2.23705 14.8467 2 14.25 2H9.75C9.15326 2 8.58097 2.23705 8.15901 2.65901C7.73705 3.08097 7.5 3.65326 7.5 4.25V5H3.75C3.55109 5 3.36032 5.07902 3.21967 5.21967C3.07902 5.36032 3 5.55109 3 5.75C3 5.94891 3.07902 6.13968 3.21967 6.28033C3.36032 6.42098 3.55109 6.5 3.75 6.5H4.5V20C4.5 20.3978 4.65804 20.7794 4.93934 21.0607C5.22064 21.342 5.60218 21.5 6 21.5H18C18.3978 21.5 18.7794 21.342 19.0607 21.0607C19.342 20.7794 19.5 20.3978 19.5 20V6.5H20.25C20.4489 6.5 20.6397 6.42098 20.7803 6.28033C20.921 6.13968 21 5.94891 21 5.75C21 5.55109 20.921 5.36032 20.7803 5.21967C20.6397 5.07902 20.4489 5 20.25 5ZM10.5 16.25C10.5 16.4489 10.421 16.6397 10.2803 16.7803C10.1397 16.921 9.94891 17 9.75 17C9.55109 17 9.36032 16.921 9.21967 16.7803C9.07902 16.6397 9 16.4489 9 16.25V10.25C9 10.0511 9.07902 9.86032 9.21967 9.71967C9.36032 9.57902 9.55109 9.5 9.75 9.5C9.94891 9.5 10.1397 9.57902 10.2803 9.71967C10.421 9.86032 10.5 10.0511 10.5 10.25V16.25ZM15 16.25C15 16.4489 14.921 16.6397 14.7803 16.7803C14.6397 16.921 14.4489 17 14.25 17C14.0511 17 13.8603 16.921 13.7197 16.7803C13.579 16.6397 13.5 16.4489 13.5 16.25V10.25C13.5 10.0511 13.579 9.86032 13.7197 9.71967C13.8603 9.57902 14.0511 9.5 14.25 9.5C14.4489 9.5 14.6397 9.57902 14.7803 9.71967C14.921 9.86032 15 10.0511 15 10.25V16.25ZM15 5H9V4.25C9 4.05109 9.07902 3.86032 9.21967 3.71967C9.36032 3.57902 9.55109 3.5 9.75 3.5H14.25C14.4489 3.5 14.6397 3.57902 14.7803 3.71967C14.921 3.86032 15 4.05109 15 4.25V5Z" fill="white" fill-opacity="0.4"/>
                                </svg>
                            </button>
                            
                            <div class="toneka-minicart-quantity">
                                <button class="quantity-btn minus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">-</button>
                                <input type="number" value="<?php echo $quantity; ?>" min="1" class="quantity-input" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">
                                <button class="quantity-btn plus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>">+</button>
                            </div>
                        </div>
                        
                        <div class="toneka-minicart-item-details">
                            <h4 class="toneka-minicart-item-name"><?php echo $product->get_name(); ?></h4>
                            
                            <?php if ($variant_text): ?>
                            <div class="toneka-minicart-item-variant"><?php echo esc_html($variant_text); ?></div>
                            <?php else: ?>
                            <div class="toneka-minicart-item-variant">PLIKI CYFROWE</div>
                            <?php endif; ?>
                            
                            <div class="toneka-minicart-item-price">
                                <?php 
                                // Display full price with sale information
                                if ($product->is_on_sale()) {
                                    $regular_price = $product->get_regular_price();
                                    $sale_price = $product->get_sale_price();
                                    $savings = $regular_price - $sale_price;
                                    $savings_percent = round(($savings / $regular_price) * 100);
                                    
                                    echo '<div class="toneka-minicart-price-sale">';
                                    echo '<span class="toneka-minicart-price-regular">' . wc_price($regular_price) . '</span> ';
                                    echo '<span class="toneka-minicart-price-current">' . wc_price($sale_price) . '</span>';
                                    echo '<div class="toneka-minicart-savings">Oszczędzasz: ' . wc_price($savings) . ' (' . $savings_percent . '%)</div>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="toneka-minicart-price-regular">' . wc_price($product->get_price()) . '</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
                </div>

                <?php
                // Get up-sell products (simplified for now)
                $upsell_ids = array();
                foreach (WC()->cart->get_cart() as $cart_item) {
                    $product = wc_get_product($cart_item['product_id']);
                    if ($product && $product->get_upsell_ids()) {
                        $upsell_ids = array_merge($upsell_ids, $product->get_upsell_ids());
                    }
                }

                if (!empty($upsell_ids)) {
                    ?>
                    <!-- Up-sell products section -->
                    <div class="toneka-minicart-upsells">
                        <h4>Może Cię też zainteresować:</h4>
                        <?php
                        $upsell_ids = array_unique(array_slice($upsell_ids, 0, 2)); // Max 2 products
                        foreach ($upsell_ids as $upsell_id) {
                            $upsell_product = wc_get_product($upsell_id);
                            if ($upsell_product) {
                                ?>
                                <div class="toneka-minicart-upsell-item">
                                    <div class="toneka-minicart-upsell-item-image">
                                        <img src="<?php echo wp_get_attachment_image_url(get_post_thumbnail_id($upsell_id), 'thumbnail'); ?>" alt="<?php echo $upsell_product->get_name(); ?>">
                                    </div>
                                    
                                    <div class="toneka-minicart-upsell-item-content">
                                        <div class="toneka-minicart-upsell-item-details">
                                            <h5 class="toneka-minicart-upsell-item-name"><?php echo $upsell_product->get_name(); ?></h5>
                                            <div class="toneka-minicart-upsell-item-variant">PLIKI CYFROWE</div>
                                            <div class="toneka-minicart-upsell-item-price">
                                                <?php 
                                                // Display full price with sale information for upsells
                                                if ($upsell_product->is_on_sale()) {
                                                    $regular_price = $upsell_product->get_regular_price();
                                                    $sale_price = $upsell_product->get_sale_price();
                                                    $savings = $regular_price - $sale_price;
                                                    $savings_percent = round(($savings / $regular_price) * 100);
                                                    
                                                    echo '<div class="toneka-minicart-upsell-price-sale">';
                                                    echo '<span class="toneka-minicart-upsell-price-regular">' . wc_price($regular_price) . '</span> ';
                                                    echo '<span class="toneka-minicart-upsell-price-current">' . wc_price($sale_price) . '</span>';
                                                    echo '<div class="toneka-minicart-upsell-savings">-' . $savings_percent . '%</div>';
                                                    echo '</div>';
                                                } else {
                                                    echo '<div class="toneka-minicart-upsell-price-regular">' . wc_price($upsell_product->get_price()) . '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        
                                        <div class="toneka-minicart-upsell-item-actions">
                                            <div class="toneka-minicart-upsell-cart-section">
                                                <div class="toneka-minicart-upsell-quantity">
                                                    <button class="upsell-quantity-btn minus" data-product-id="<?php echo $upsell_id; ?>">-</button>
                                                    <input type="number" value="1" min="1" class="upsell-quantity-input" data-product-id="<?php echo $upsell_id; ?>">
                                                    <button class="upsell-quantity-btn plus" data-product-id="<?php echo $upsell_id; ?>">+</button>
                                                </div>
                                                <button class="toneka-minicart-upsell-add-btn" data-product-id="<?php echo $upsell_id; ?>">DODAJ DO KOSZYKA</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <?php
                }
                ?>

                <div class="toneka-minicart-footer">
                    <?php
                    // Calculate total savings in cart
                    $total_savings = 0;
                    $total_regular = 0;
                    $total_sale = 0;
                    
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                        $product = $cart_item['data'];
                        $quantity = $cart_item['quantity'];
                        
                        if ($product->is_on_sale()) {
                            $regular_price = $product->get_regular_price();
                            $sale_price = $product->get_sale_price();
                            $item_savings = ($regular_price - $sale_price) * $quantity;
                            $total_savings += $item_savings;
                            $total_regular += $regular_price * $quantity;
                            $total_sale += $sale_price * $quantity;
                        }
                    }
                    
                    if ($total_savings > 0) {
                        $savings_percent = round(($total_savings / $total_regular) * 100);
                        ?>
                        <div class="toneka-minicart-total-savings">
                            <span>Oszczędzasz łącznie: <?php echo wc_price($total_savings); ?> (<?php echo $savings_percent; ?>%)</span>
                        </div>
                        <?php
                    }
                    ?>
                    
                    <div class="toneka-minicart-total">
                        <span>Razem: <?php echo WC()->cart->get_cart_total(); ?></span>
                    </div>
                    
                    <button class="toneka-minicart-order-btn">ZAMÓW</button>
                </div>
    </div>
    <?php
}

/**
 * AJAX handler for updating cart quantity
 */
function toneka_update_cart_quantity() {
    check_ajax_referer('wc_add_to_cart_nonce', 'security');
    
    if (!isset($_POST['cart_key']) || !isset($_POST['quantity'])) {
        wp_die();
    }
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    $quantity = intval($_POST['quantity']);
    
    if ($quantity <= 0) {
        WC()->cart->remove_cart_item($cart_key);
    } else {
        WC()->cart->set_quantity($cart_key, $quantity);
    }
    
    wp_send_json_success();
}
add_action('wp_ajax_toneka_update_cart_quantity', 'toneka_update_cart_quantity');
add_action('wp_ajax_nopriv_toneka_update_cart_quantity', 'toneka_update_cart_quantity');

/**
 * AJAX handler for removing cart item
 */
function toneka_remove_cart_item() {
    check_ajax_referer('wc_add_to_cart_nonce', 'security');
    
    if (!isset($_POST['cart_key'])) {
        wp_die();
    }
    
    $cart_key = sanitize_text_field($_POST['cart_key']);
    WC()->cart->remove_cart_item($cart_key);
    
    wp_send_json_success();
}
add_action('wp_ajax_toneka_remove_cart_item', 'toneka_remove_cart_item');
add_action('wp_ajax_nopriv_toneka_remove_cart_item', 'toneka_remove_cart_item');

/**
 * AJAX handler for refreshing minicart
 */
function toneka_refresh_minicart() {
    check_ajax_referer('wc_add_to_cart_nonce', 'security');
    
    ob_start();
    toneka_display_custom_minicart();
    $minicart_html = ob_get_clean();
    
    wp_send_json_success(array(
        'minicart_html' => $minicart_html
    ));
}
add_action('wp_ajax_toneka_refresh_minicart', 'toneka_refresh_minicart');
add_action('wp_ajax_nopriv_toneka_refresh_minicart', 'toneka_refresh_minicart');

/**
 * Auto-open minicart after adding product
 * Handled in minicart.js to avoid duplication
 */

/**
 * Diagnostic script for minicart debugging
 */
function toneka_minicart_diagnostics() {
    if (is_product()) {
        ?>
        <script type="text/javascript">
        jQuery(document).ready(function($) {
            console.log('TONEKA DIAGNOSTIC: Page loaded');
            console.log('TONEKA DIAGNOSTIC: Minicart elements exist:', $('.toneka-minicart').length > 0);
            console.log('TONEKA DIAGNOSTIC: Overlay elements exist:', $('.toneka-minicart-overlay').length > 0);
            
            // Listen for all added_to_cart events
            $(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
                console.log('TONEKA DIAGNOSTIC: added_to_cart event fired!');
                console.log('TONEKA DIAGNOSTIC: Event object:', event);
                console.log('TONEKA DIAGNOSTIC: Fragments:', fragments);
                console.log('TONEKA DIAGNOSTIC: Cart hash:', cart_hash);
                console.log('TONEKA DIAGNOSTIC: Button:', button);
            });
            
            // Test manual trigger
            window.toneka_test_cart_event = function() {
                console.log('TONEKA DIAGNOSTIC: Manually triggering added_to_cart event');
                $(document.body).trigger('added_to_cart');
            };
        });
        </script>
        <?php
    }
}
add_action('wp_footer', 'toneka_minicart_diagnostics');

/**
 * Register footer widget areas.
 */
function toneka_footer_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area 2', 'tonekatheme' ),
			'id'            => 'footer-widget-2',
			'description'   => esc_html__( 'Add widgets here for the second footer column.', 'tonekatheme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		)
	);

	register_sidebar(
		array(
			'name'          => esc_html__( 'Footer Widget Area 3', 'tonekatheme' ),
			'id'            => 'footer-widget-3',
			'description'   => esc_html__( 'Add widgets here for the third footer column.', 'tonekatheme' ),
			'before_widget' => '<div id="%1$s" class="footer-widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h4 class="footer-widget-title">',
			'after_title'   => '</h4>',
		)
	);
}
add_action( 'widgets_init', 'toneka_footer_widgets_init' );

/**
 * Customize Register - Add scrolling text option
 */
function toneka_customize_register( $wp_customize ) {
	// Add Footer Section
	$wp_customize->add_section( 'toneka_footer_options', array(
		'title'    => __( 'Footer Options', 'tonekatheme' ),
		'priority' => 120,
	) );

	// Add Scrolling Text Setting
	$wp_customize->add_setting( 'toneka_scrolling_text', array(
		'default'           => 'CZYM JEST MUZYKA ? NIE WIEM. MOŻE PO PROSTU NIEBEM. Z NUTAMI ZAMIAST GWIAZD.',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );

	// Add Scrolling Text Control
	$wp_customize->add_control( 'toneka_scrolling_text', array(
		'label'       => __( 'Przewijający się tekst', 'tonekatheme' ),
		'description' => __( 'Tekst który będzie przewijał się w footerze między produktami a informacjami kontaktowymi.', 'tonekatheme' ),
		'section'     => 'toneka_footer_options',
		'type'        => 'textarea',
	) );

	// Add option to enable/disable scrolling text
	$wp_customize->add_setting( 'toneka_scrolling_text_enabled', array(
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'toneka_scrolling_text_enabled', array(
		'label'   => __( 'Włącz przewijający się tekst', 'tonekatheme' ),
		'section' => 'toneka_footer_options',
		'type'    => 'checkbox',
	) );

	// Add scrolling speed setting
	$wp_customize->add_setting( 'toneka_scrolling_speed', array(
		'default'           => 50,
		'sanitize_callback' => 'absint',
		'transport'         => 'refresh',
	) );

	$wp_customize->add_control( 'toneka_scrolling_speed', array(
		'label'       => __( 'Prędkość przewijania (sekundy)', 'tonekatheme' ),
		'description' => __( 'Im większa liczba, tym wolniejsze przewijanie.', 'tonekatheme' ),
		'section'     => 'toneka_footer_options',
		'type'        => 'range',
		'input_attrs' => array(
			'min'  => 20,
			'max'  => 100,
			'step' => 5,
		),
	) );

	// Add Creators Archive Section
	$wp_customize->add_section('toneka_creators_archive', array(
		'title'    => __('Creators Archive', 'toneka-theme'),
		'priority' => 130,
	));

	// Hero Image Setting
	$wp_customize->add_setting('creators_archive_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'creators_archive_hero_image', array(
		'label'    => __('Hero Background Image', 'toneka-theme'),
		'section'  => 'toneka_creators_archive',
		'settings' => 'creators_archive_hero_image',
		'description' => __('Upload an image for the creators archive hero section background.', 'toneka-theme'),
	)));

	// Hero Title Setting
	$wp_customize->add_setting('creators_archive_hero_title', array(
		'default'           => 'TWÓRCY',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('creators_archive_hero_title', array(
		'label'    => __('Hero Title', 'toneka-theme'),
		'section'  => 'toneka_creators_archive',
		'type'     => 'text',
		'description' => __('Title displayed in the hero section.', 'toneka-theme'),
	));

	// Hero Description Setting
	$wp_customize->add_setting('creators_archive_hero_description', array(
		'default'           => 'Poznaj twórców, którzy współtworzą nasz świat dźwięków i obrazów. Artyści, muzycy, graficy i reżyserzy, którzy nadają kształt naszym produkcjom.',
		'sanitize_callback' => 'wp_kses_post',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('creators_archive_hero_description', array(
		'label'    => __('Hero Description', 'toneka-theme'),
		'section'  => 'toneka_creators_archive',
		'type'     => 'textarea',
		'description' => __('Description text displayed in the hero section.', 'toneka-theme'),
	));

	// Homepage Section
	$wp_customize->add_section('toneka_homepage', array(
		'title'    => __('Strona Główna', 'toneka-theme'),
		'priority' => 30,
	));

	// Hero Text
	$wp_customize->add_setting('toneka_homepage_hero_text', array(
		'default'           => 'Melodie jak fale, co w stronę marzeń płyną, a dźwięki to słowa, co w ciszy z duszą śpiewają',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_hero_text', array(
		'label'    => __('Hero - Tekst główny', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'textarea',
	));

	// Hero Button Text
	$wp_customize->add_setting('toneka_homepage_button_text', array(
		'default'           => 'ostatnio dodane',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_button_text', array(
		'label'    => __('Hero - Tekst przycisku', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'text',
	));

	// Hero Button URL
	$wp_customize->add_setting('toneka_homepage_button_url', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_button_url', array(
		'label'    => __('Hero - Link przycisku', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'url',
	));

	// Hero Image
	$wp_customize->add_setting('toneka_homepage_hero_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'toneka_homepage_hero_image', array(
		'label'    => __('Hero - Zdjęcie', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'settings' => 'toneka_homepage_hero_image',
	)));

	// Section Title
	$wp_customize->add_setting('toneka_homepage_section_title', array(
		'default'           => 'sekcja jakiś tytuł',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_section_title', array(
		'label'    => __('Tytuł sekcji produktów', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'text',
	));

	// Quote
	$wp_customize->add_setting('toneka_homepage_quote', array(
		'default'           => 'Czym jest muzyka ? Nie wiem. Może po prostu niebem. Z nutami zamiast gwiazd.',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_quote', array(
		'label'    => __('Cytat', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'textarea',
	));

	// Bottom Text
	$wp_customize->add_setting('toneka_homepage_bottom_text', array(
		'default'           => 'Melodie jak fale, co w stronę marzeń płyną, a dźwięki to słowa, co w ciszy z duszą grają. śpiewają',
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_bottom_text', array(
		'label'    => __('Dolna sekcja - Tekst', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'textarea',
	));

	// Bottom Button Text
	$wp_customize->add_setting('toneka_homepage_bottom_button_text', array(
		'default'           => 'posłuchaj',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_bottom_button_text', array(
		'label'    => __('Dolna sekcja - Tekst przycisku', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'text',
	));

	// Bottom Button URL
	$wp_customize->add_setting('toneka_homepage_bottom_button_url', array(
		'default'           => '#',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_homepage_bottom_button_url', array(
		'label'    => __('Dolna sekcja - Link przycisku', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'type'     => 'url',
	));

	// Bottom Image
	$wp_customize->add_setting('toneka_homepage_bottom_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'toneka_homepage_bottom_image', array(
		'label'    => __('Dolna sekcja - Zdjęcie', 'toneka-theme'),
		'section'  => 'toneka_homepage',
		'settings' => 'toneka_homepage_bottom_image',
	)));

	// Social Media Section
	$wp_customize->add_section('toneka_social_media', array(
		'title'    => __('Social Media', 'toneka-theme'),
		'priority' => 130,
	));

	// Instagram URL
	$wp_customize->add_setting('toneka_instagram_url', array(
		'default'           => 'https://instagram.com/toneka',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_instagram_url', array(
		'label'    => __('Instagram - URL', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'url',
	));

	// Instagram Text
	$wp_customize->add_setting('toneka_instagram_text', array(
		'default'           => 'INSTAGRAM',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_instagram_text', array(
		'label'    => __('Instagram - Tekst', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'text',
	));

	// Facebook URL
	$wp_customize->add_setting('toneka_facebook_url', array(
		'default'           => 'https://facebook.com/toneka',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_facebook_url', array(
		'label'    => __('Facebook - URL', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'url',
	));

	// Facebook Text
	$wp_customize->add_setting('toneka_facebook_text', array(
		'default'           => 'FACEBOOK',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_facebook_text', array(
		'label'    => __('Facebook - Tekst', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'text',
	));

	// YouTube URL
	$wp_customize->add_setting('toneka_youtube_url', array(
		'default'           => 'https://youtube.com/toneka',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_youtube_url', array(
		'label'    => __('YouTube - URL', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'url',
	));

	// YouTube Text
	$wp_customize->add_setting('toneka_youtube_text', array(
		'default'           => 'YOU TUBE',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	));

	$wp_customize->add_control('toneka_youtube_text', array(
		'label'    => __('YouTube - Tekst', 'toneka-theme'),
		'section'  => 'toneka_social_media',
		'type'     => 'text',
	));
}
add_action( 'customize_register', 'toneka_customize_register' );

/**
 * Enqueue scripts and styles.
 */
function toneka_theme_scripts() {
	// Ładuj Google Fonts - tylko Figtree
	wp_enqueue_style( 'toneka-google-fonts', 'https://fonts.googleapis.com/css2?family=Figtree:wght@300;400;500;600;700&display=swap', array(), null );
	
	// Główny arkusz stylów
	wp_enqueue_style( 'toneka-theme-style', get_stylesheet_uri(), array('toneka-google-fonts'), wp_get_theme()->get( 'Version' ) );

	// Skrypt nawigacji
	wp_enqueue_script(
		'toneka-navigation',
		get_template_directory_uri() . '/js/navigation.js',
		array('jquery'),
		'1.0.0',
		true
	);
	
	// Category/Shop page script - universal for all product pages
	if ((function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()) || is_tax('product_cat')) {
		wp_enqueue_script(
			'toneka-category-page',
			get_template_directory_uri() . '/js/category-page.js',
			array('jquery'),
			'1.0.0',
			true
		);
	}
	
	// Creator page script
	if (is_singular('creator')) {
		wp_enqueue_script(
			'toneka-creator-page',
			get_template_directory_uri() . '/js/creator-page.js',
			array(),
			'1.0.0',
			true
		);
	}

	// Cart, checkout and account pages script
	if (is_cart() || is_checkout() || is_account_page()) {
		wp_enqueue_script(
			'toneka-cart-checkout',
			get_template_directory_uri() . '/js/cart-checkout.js',
			array('jquery'),
			'1.0.0',
			true
		);
	}
	
	// Creators archive page script
	if (is_post_type_archive('creator')) {
		wp_enqueue_script(
			'toneka-creators-archive',
			get_template_directory_uri() . '/js/creators-archive.js',
			array('jquery'),
			'1.0.0',
			true
		);
	}
	
	// Add AJAX parameters for category filtering - only for category/shop pages
	if ((function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()) || is_tax('product_cat')) {
		$current_category_id = 0; // Default to 0 (WSZYSTKO) for shop page
		if ((function_exists('is_product_category') && is_product_category()) || is_tax('product_cat')) {
			$current_category_id = get_queried_object_id();
		}
		
		wp_localize_script('toneka-category-page', 'toneka_category_params', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('toneka_category_filter_nonce'),
			'current_category_id' => $current_category_id,
			'shop_url' => wc_get_page_permalink('shop')
		));
	}
	
	// Dodaj parametry AJAX dla navigation.js
	wp_localize_script('toneka-navigation', 'toneka_nav_params', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce' => wp_create_nonce('toneka_nav_nonce')
	));

	// Skrypt dla minikoszyka (jeśli istnieje)
	if (file_exists(get_template_directory() . '/js/minicart.js')) {
		wp_enqueue_script(
			'toneka-minicart',
			get_template_directory_uri() . '/js/minicart.js',
			array('jquery'),
			'1.0.0',
			true
		);
		
		// Localize script for AJAX - only basic params, detailed params set in product-specific function
		wp_localize_script('toneka-minicart', 'toneka_minicart_params', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'wc_ajax_nonce' => wp_create_nonce('wc_add_to_cart_nonce'),
			'checkout_url' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/')
		));
	}

	// Skrypt galerii produktów
	wp_enqueue_script(
		'toneka-gallery',
		get_template_directory_uri() . '/js/gallery.js',
		array(),
		'1.0.0',
		true
	);
	
	// Adaptacyjny header - dostosowuje się do jasności tła (produkty, kategorie, sklep, tagi, twórcy, strona główna)
	if ( (function_exists('is_product') && is_product()) || (function_exists('is_product_category') && is_product_category()) || (function_exists('is_shop') && is_shop()) || (function_exists('is_product_tag') && is_product_tag()) || is_singular('creator') || is_post_type_archive('creator') || is_tax('product_cat') || is_tax('product_tag') || is_front_page() ) {
		wp_enqueue_script(
			'toneka-adaptive-header',
			get_template_directory_uri() . '/js/adaptive-header.js',
			array(),
			'1.0.0',
			true
		);
	}
	
	// Przewijający się tekst w footerze
	if ( get_theme_mod( 'toneka_scrolling_text_enabled', true ) ) {
		wp_enqueue_script(
			'toneka-scrolling-text',
			get_template_directory_uri() . '/js/scrolling-text.js',
			array(),
			'1.0.0',
			true
		);
	}

	// Skrypt dla rozwijania/zwijania opisu
	wp_enqueue_script(
		'toneka-description-toggle',
		get_template_directory_uri() . '/js/description-toggle.js',
		array(),
		'1.0.0',
		true
	);

	// Skrypt dla selektora wariantów
	wp_enqueue_script(
		'toneka-variation-selector',
		get_template_directory_uri() . '/js/variation-selector.js',
		array(),
		'1.0.0',
		true
	);
	
	// Skrypt dla playera audio/wideo
	wp_enqueue_script(
		'toneka-player',
		get_template_directory_uri() . '/js/toneka-player.js',
		array(),
		'1.0.0',
		true
	);
	
	// Skrypt dla klikania kart produktów (na wszystkich stronach z produktami)
	wp_enqueue_script(
		'toneka-product-cards',
		get_template_directory_uri() . '/js/product-cards.js',
		array(),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'toneka_theme_scripts' );

/**
 * =================================================================================
 * System stron twórców
 * =================================================================================
 */

/**
 * Rejestruje Custom Post Type dla Twórców.
 */
function toneka_register_creator_post_type() {
    register_post_type('creator', array(
        'labels' => array(
            'name' => 'Twórcy',
            'singular_name' => 'Twórca',
            'add_new' => 'Dodaj twórcę',
            'add_new_item' => 'Dodaj nowego twórcę',
            'edit_item' => 'Edytuj twórcę',
            'new_item' => 'Nowy twórca',
            'view_item' => 'Zobacz twórcę',
            'search_items' => 'Szukaj twórców',
            'not_found' => 'Nie znaleziono twórców',
            'not_found_in_trash' => 'Nie znaleziono twórców w koszu',
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'tworca'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-groups',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
        'can_export' => true,
    ));
}
add_action('init', 'toneka_register_creator_post_type');

/**
 * Rejestruje meta pola dla Twórców.
 */
function toneka_register_creator_meta() {
    register_post_meta('creator', '_creator_role', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ));
    
    register_post_meta('creator', '_creator_bio', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ));
    
    register_post_meta('creator', '_creator_title', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
    ));
}
add_action('init', 'toneka_register_creator_meta');

/**
 * =================================================================================
 * Pola meta dla produktów WooCommerce
 * =================================================================================
 */

/**
 * Rejestruje wszystkie niestandardowe pola meta dla produktów.
 */
function toneka_register_product_meta() {
	$meta_fields = [
		'_rok_produkcji', '_czas_trwania', '_autor_title', '_autors', 
		'_obsada_title', '_obsada', '_rezyser_title', '_rezyserzy', 
		'_muzyka_title', '_muzycy', '_tlumacz_title', '_tlumacze', 
		'_adaptacja_title', '_adaptatorzy', '_wydawca_title', '_wydawcy', 
		'_grafika_title', '_grafika'
	];

	foreach ($meta_fields as $field) {
		register_post_meta('product', $field, [
			'show_in_rest' => true,
			'single' => true,
			'type' => is_string($field) && (strpos($field, 'title') === false) ? 'array' : 'string',
			'auth_callback' => function() { return current_user_can('edit_posts'); }
		]);
	}
}
add_action('init', 'toneka_register_product_meta');

/**
 * Dodaje niestandardowe pola do panelu edycji produktu WooCommerce.
 */
function toneka_add_custom_product_fields() {
	global $post;

	// Dodaj style dla dynamicznych pól
	echo '<style>
		.toneka-person-group {
			border: 1px solid #ddd;
			padding: 10px;
			margin: 10px 0;
			background: #f9f9f9;
			border-radius: 4px;
		}
		.toneka-remove-person {
			background: #dc3232 !important;
			border-color: #dc3232 !important;
			color: #fff !important;
			margin-top: 10px;
		}
		.toneka-add-person {
			background: #0073aa !important;
			border-color: #0073aa !important;
			color: #fff !important;
			margin: 10px 0;
		}
	</style>';

	echo '<div class="options_group">';
	
	// Rok produkcji i Czas trwania
	woocommerce_wp_text_input([
		'id' => '_rok_produkcji',
		'label' => __('Rok produkcji', 'tonekatheme'),
		'type' => 'number',
	]);
	woocommerce_wp_text_input([
		'id' => '_czas_trwania',
		'label' => __('Czas trwania (min)', 'tonekatheme'),
		'type' => 'number',
	]);

	echo '</div>';

	// Pola typu "repeater" dla twórców
	$creator_fields = [
		'autor' => __('Autorzy', 'tonekatheme'),
		'obsada' => __('Obsada', 'tonekatheme'),
		'rezyser' => __('Reżyseria', 'tonekatheme'),
		'muzyk' => __('Muzyka', 'tonekatheme'),
		'tlumacz' => __('Tłumaczenie', 'tonekatheme'),
		'adaptator' => __('Adaptacja', 'tonekatheme'),
		'wydawca' => __('Wydawca', 'tonekatheme'),
		'grafika' => __('Grafika', 'tonekatheme'),
	];

	foreach ($creator_fields as $key => $label) {
		// Mapowanie kluczy zgodnie z tym co jest w funkcji zapisywania
		$field_mapping = [
			'autor' => '_autors',
			'obsada' => '_obsadas', 
			'rezyser' => '_rezyserzy',
			'muzyk' => '_muzycy',
			'tlumacz' => '_tlumacze',
			'adaptator' => '_adaptatorzy',
			'wydawca' => '_wydawcy',
			'grafika' => '_grafika'
		];
		$field_id = $field_mapping[$key];
		$title_id = '_' . $key . '_title';

		echo '<div class="options_group">';
		woocommerce_wp_text_input([
			'id' => $title_id,
			'label' => __('Tytuł sekcji', 'tonekatheme') . ' ' . $label,
			'value' => get_post_meta($post->ID, $title_id, true) ?: $label,
		]);

		$items = get_post_meta($post->ID, $field_id, true);
		
		
		// Migracja danych ze starego formatu (string) do nowego (array)
		if (!empty($items) && !is_array($items)) {
			// Stary format - string z nazwiskami oddzielonymi przecinkami
			$names = array_map('trim', explode(',', $items));
			$migrated_items = [];
			foreach ($names as $name) {
				if (!empty($name)) {
					$migrated_items[] = ['imie_nazwisko' => $name, 'rola' => ''];
				}
			}
			$items = $migrated_items;
			// Zapisz w nowym formacie
			update_post_meta($post->ID, $field_id, $items);
		} elseif (is_array($items) && !empty($items)) {
			// Sprawdź czy tablica ma właściwą strukturę
			foreach ($items as $index => $item) {
				if (is_string($item)) {
					// Stary format - array stringów
					$items[$index] = ['imie_nazwisko' => $item, 'rola' => ''];
				} elseif (!isset($item['imie_nazwisko'])) {
					// Nieprawidłowa struktura
					$items[$index] = ['imie_nazwisko' => '', 'rola' => ''];
				}
			}
			// Zapisz poprawioną strukturę
			update_post_meta($post->ID, $field_id, $items);
		}
		
		if (empty($items)) $items = [['imie_nazwisko' => '', 'rola' => '']];
		
		echo '<div id="' . $key . '_repeater" class="toneka-repeater">';
		foreach ($items as $index => $item) {
			echo '<div class="' . $key . '_group toneka-person-group" data-index="' . $index . '">';
			woocommerce_wp_text_input([
				'id' => $field_id . '[' . $index . '][imie_nazwisko]',
				'label' => __('Imię i nazwisko', 'tonekatheme'),
				'value' => $item['imie_nazwisko'] ?? '',
			]);
			if ($key === 'obsada') {
				woocommerce_wp_text_input([
					'id' => $field_id . '[' . $index . '][rola]',
					'label' => __('Rola', 'tonekatheme'),
					'value' => $item['rola'] ?? '',
				]);
			}
			echo '<button type="button" class="button toneka-remove-person" data-field="' . $key . '">Usuń</button>';
			echo '</div>';
		}
		echo '</div>'; // Repeater end
		echo '<button type="button" class="button toneka-add-person" data-field="' . $key . '" data-field-id="' . $field_id . '">Dodaj ' . strtolower($label) . '</button>';
		echo '</div>'; // Options group end
	}
}
add_action('woocommerce_product_options_general_product_data', 'toneka_add_custom_product_fields');

/**
 * Zapisuje wartości niestandardowych pól produktu.
 */
function toneka_save_custom_product_fields($post_id) {
	// Sprawdź uprawnienia i nonce
	if (!current_user_can('edit_post', $post_id)) {
		return;
	}
	
	// Debug logging można włączyć w razie potrzeby
	// error_log('TONEKA DEBUG - Saving product fields for post_id: ' . $post_id);
	
	// Zapisz proste pola tekstowe
	$simple_fields = ['_rok_produkcji', '_czas_trwania'];
	foreach ($simple_fields as $field) {
		if (isset($_POST[$field])) {
			$value = sanitize_text_field($_POST[$field]);
			update_post_meta($post_id, $field, $value);
		}
	}
	
	// Zapisz tytuły sekcji
	$title_fields = [
		'_autor_title', '_obsada_title', '_rezyser_title', '_muzyka_title', 
		'_tlumacz_title', '_adaptacja_title', '_wydawca_title', '_grafika_title'
	];
	foreach ($title_fields as $field) {
		if (isset($_POST[$field])) {
			$value = sanitize_text_field($_POST[$field]);
			update_post_meta($post_id, $field, $value);
		}
	}
	
	// Zapisz dynamiczne pola osób (zagnieżdżone tablice)
	$person_fields = [
		'_autors', '_obsadas', '_rezyserzy', '_muzycy', 
		'_tlumacze', '_adaptatorzy', '_wydawcy', '_grafika'
	];
	
	foreach ($person_fields as $field) {
		if (isset($_POST[$field]) && is_array($_POST[$field])) {
			$sanitized_data = [];
			
			foreach ($_POST[$field] as $index => $person) {
				if (is_array($person)) {
					$sanitized_person = [];
					
					// Sanityzuj imię i nazwisko
					if (isset($person['imie_nazwisko']) && !empty($person['imie_nazwisko'])) {
						$sanitized_person['imie_nazwisko'] = sanitize_text_field($person['imie_nazwisko']);
					}
					
					// Sanityzuj rolę (tylko dla obsady)
					if (isset($person['rola'])) {
						$sanitized_person['rola'] = sanitize_text_field($person['rola']);
					}
					
					// Dodaj tylko jeśli imię i nazwisko nie jest puste
					if (!empty($sanitized_person['imie_nazwisko'])) {
						$sanitized_data[] = $sanitized_person;
					}
				}
			}
			
			// Zapisz dane lub usuń meta jeśli puste
			if (!empty($sanitized_data)) {
				update_post_meta($post_id, $field, $sanitized_data);
			} else {
				delete_post_meta($post_id, $field);
			}
		}
	}
}
add_action('woocommerce_process_product_meta', 'toneka_save_custom_product_fields');

/**
 * =================================================================================
 * Automatyczne tworzenie stron twórców
 * =================================================================================
 */

/**
 * Tworzy "slug" (przyjazny URL) z imienia i nazwiska.
 */
function toneka_create_creator_slug($name) {
    $slug = sanitize_title($name);
    return $slug;
}

/**
 * Sprawdza, czy strona twórcy istnieje. Jeśli nie, tworzy ją.
 */
function toneka_get_or_create_creator_page($name, $role = '') {
    $slug = toneka_create_creator_slug($name);
    
    // Sprawdź czy twórca już istnieje
    $existing = get_posts(array(
        'post_type' => 'creator',
        'name' => $slug,
        'post_status' => 'publish',
        'numberposts' => 1
    ));
    
    if (!empty($existing)) {
        return $existing[0]->ID;
    }
    
    // Utwórz nowego twórcę
    $creator_id = wp_insert_post(array(
        'post_title' => $name,
        'post_name' => $slug,
        'post_type' => 'creator',
        'post_status' => 'publish',
        'post_content' => 'Biografia twórcy będzie dostępna wkrótce.',
    ));
    
    if ($creator_id && !is_wp_error($creator_id)) {
        if ($role) {
            update_post_meta($creator_id, '_creator_role', $role);
        }
        return $creator_id;
    }
    
    return false;
}

/**
 * Główna funkcja automatyzacji, uruchamiana przy zapisie posta.
 */
function toneka_auto_create_creator_pages($post_id) {
    // Upewnij się, że to produkt
    if (get_post_type($post_id) !== 'product') {
        return;
    }
    
    // Lista pól meta z twórcami i ich domyślne role
    $creator_types = array(
        '_autors' => 'Autor',
        '_obsada' => 'Aktor/Aktorka',
        '_rezyserzy' => 'Reżyser',
        '_muzycy' => 'Muzyk',
        '_tlumacze' => 'Tłumacz',
        '_adaptatorzy' => 'Adaptator',
        '_wydawcy' => 'Wydawca',
        '_grafika' => 'Grafik'
    );
    
    foreach ($creator_types as $meta_key => $role) {
        $creators = get_post_meta($post_id, $meta_key, true);
        
        if (!empty($creators) && is_array($creators)) {
            foreach ($creators as $creator) {
                if (isset($creator['imie_nazwisko']) && !empty($creator['imie_nazwisko'])) {
                    toneka_get_or_create_creator_page($creator['imie_nazwisko'], $role);
                }
            }
        }
    }
}
add_action('save_post', 'toneka_auto_create_creator_pages');

/**
 * =================================================================================
 * Niestandardowy selektor wariantów produktu
 * =================================================================================
 */

/**
 * Główna funkcja wyświetlająca niestandardowy selektor wariantów.
 * Zastępuje domyślny formularz WooCommerce.
 */
function toneka_output_variable_product_selector() {
    global $product;

    if (!$product) {
        return;
    }

    // Obsługa produktów prostych (nie-wariantowych)
    if (!$product->is_type('variable')) {
        ?>
        <div class="toneka-carrier-selection-widget" data-mode="simple">
            <div class="toneka-variation-info-container">
                <div class="toneka-variation-price-display">
                    <?php echo $product->get_price_html(); ?>
                </div>
            </div>
            
            <div class="toneka-cart-section">
                <form class="toneka-cart-form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                    <div class="toneka-quantity-section">
                        <div class="toneka-quantity-wrapper">
                            <button type="button" class="toneka-quantity-minus">−</button>
                            <input type="number" id="toneka-quantity" class="toneka-quantity-input" name="quantity" value="1" min="1" />
                            <button type="button" class="toneka-quantity-plus">+</button>
                        </div>
                    </div>
                    <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="toneka-add-to-cart-button">
                        DODAJ DO KOSZYKA
                    </button>
                    <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                </form>
            </div>
        </div>
        <?php
        return;
    }

    // Sprawdź czy $product to obiekt i czy jest typu variable
    if (!is_object($product) || !$product->is_type('variable') || !method_exists($product, 'get_available_variations')) {
        return;
    }

    $available_variations = $product->get_available_variations();
    
    if (empty($available_variations)) {
        return;
    }

    // Dodajemy dane, które były wcześniej w widgecie
    foreach ($available_variations as $key => $variation_data) {
        $variation = wc_get_product($variation_data['variation_id']);
        if ($variation && is_object($variation) && method_exists($variation, 'get_variation_attributes')) {
            $available_variations[$key]['variation_description'] = $variation->get_description();
            
            // Pobierz atrybuty wariantu
            $attributes = $variation->get_variation_attributes();
            $display_parts = array();
            
            foreach ($attributes as $attribute_name => $attribute_value) {
                if (!empty($attribute_value)) {
                    // Usuń prefiks 'attribute_' z nazwy atrybutu
                    $clean_attribute_name = str_replace('attribute_', '', $attribute_name);
                    
                    // Konwertuj slug na czytelną nazwę
                    $display_value = $attribute_value;
                    
                    // Mapowanie popularnych wartości na czytelne nazwy
                    $value_mapping = array(
                        'cd' => 'CD',
                        'kaseta-magnetofonowa' => 'Kaseta magnetofonowa',
                        'plyta-winylowa' => 'Płyta winylowa',
                        'pliki-cyfrowe' => 'Pliki cyfrowe',
                        'digital' => 'Pliki cyfrowe',
                        'vinyl' => 'Płyta winylowa',
                        'cassette' => 'Kaseta magnetofonowa'
                    );
                    
                    if (isset($value_mapping[strtolower($display_value)])) {
                        $display_value = $value_mapping[strtolower($display_value)];
                    } else {
                        // Zamień myślniki na spacje i skapitalizuj
                        $display_value = ucwords(str_replace('-', ' ', $display_value));
                    }
                    
                    $display_parts[] = $display_value;
                }
            }
            
            $available_variations[$key]['variation_display_name'] = !empty($display_parts) 
                ? implode(' / ', $display_parts) 
                : $variation->get_name();
        }
    }

    ?>
    <div class="toneka-carrier-selection-widget" data-mode="all-variations">
        <h3 class="toneka-carrier-title"><?php esc_html_e('Wybierz:', 'tonekatheme'); ?></h3>
        <div class="toneka-carrier-options">
            <?php 
            $first = true;
            foreach ($available_variations as $variation_data): 
            ?>
            <label class="toneka-carrier-option">
                <input type="radio" 
                       name="toneka_variation_selection" 
                       value="<?php echo esc_attr($variation_data['variation_id']); ?>" 
                       class="toneka-carrier-radio" 
                       data-variation-id="<?php echo esc_attr($variation_data['variation_id']); ?>"
                       data-variation-data="<?php echo esc_attr(json_encode($variation_data)); ?>"
                       <?php echo $first ? 'checked' : ''; ?>>
                <span class="toneka-carrier-radio-custom"></span>
                <span class="toneka-carrier-label"><?php echo esc_html($variation_data['variation_display_name']); ?></span>
            </label>
            <?php 
                $first = false;
            endforeach; 
            ?>
        </div>
        
        <div class="toneka-variation-info-container">
            <div class="toneka-variation-description-display"></div>
            <div class="toneka-variation-price-display"></div>
        </div>
        
        <script type="application/json" class="toneka-variations-data">
            <?php echo json_encode($available_variations); ?>
        </script>
        
        <div class="toneka-cart-section">
            <form class="toneka-cart-form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype='multipart/form-data'>
                <div class="toneka-quantity-section">
                    <div class="toneka-quantity-wrapper">
                        <button type="button" class="toneka-quantity-minus">−</button>
                        <input type="number" id="toneka-quantity" class="toneka-quantity-input" name="quantity" value="1" min="1" />
                        <button type="button" class="toneka-quantity-plus">+</button>
                    </div>
                </div>
                <button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="toneka-add-to-cart-button">
                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                </button>
                <input type="hidden" name="product_id" value="<?php echo esc_attr($product->get_id()); ?>">
                <input type="hidden" name="variation_id" class="variation_id" value="" />
            </form>
        </div>
    </div>
    <?php
}

/**
 * Zastępuje domyślny formularz WooCommerce naszym niestandardowym selektorem.
 */
function toneka_replace_variable_add_to_cart() {
    remove_action('woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30);
    add_action('woocommerce_variable_add_to_cart', 'toneka_output_variable_product_selector', 30);
}
add_action('woocommerce_before_single_product', 'toneka_replace_variable_add_to_cart');


/**
 * Ładuje skrypty JS dla selektora wariantów.
 */
function toneka_enqueue_variation_selector_assets() {
    error_log('TONEKA ENQUEUE: Funkcja wywołana');
    error_log('TONEKA ENQUEUE: Funkcja wywołana, is_product(): ' . (is_product() ? 'YES' : 'NO'));
    error_log('TONEKA ENQUEUE: get_post_type(): ' . get_post_type());
    error_log('TONEKA ENQUEUE: is_singular(): ' . (is_singular() ? 'YES' : 'NO'));
    if (is_product()) {
        global $product;
        
        // Jeśli globalny $product nie jest dostępny, pobierz go z aktualnego posta
        if (!$product || !is_object($product)) {
            $product = wc_get_product(get_the_ID());
        }
        
        error_log('TONEKA ENQUEUE: Ładuję carrier-selection-new.js');
        wp_enqueue_script(
            'toneka-carrier-selection', 
            get_template_directory_uri() . '/js/carrier-selection-new.js',
            array('jquery', 'toneka-minicart'), '1.0.0', true
        );
        
        wp_localize_script('toneka-carrier-selection', 'toneka_ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('toneka_ajax_nonce')
        ));
        
        // Dodaj wc_add_to_cart_params dla carrier-selection-new.js
        wp_localize_script('toneka-carrier-selection', 'wc_add_to_cart_params', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'wc_ajax_nonce' => wp_create_nonce('wc_add_to_cart_nonce'),
            'checkout_url' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout/'),
            'product_id' => ($product && is_object($product)) ? $product->get_id() : get_the_ID()
        ));
        
        // Dodaj skrypt zapewniający otwieranie minicart (fallback)
        add_action('wp_footer', function() {
            ?>
            <script type="text/javascript">
            jQuery(document).ready(function($) {
                // Fallback event listener for minicart opening
                $(document.body).on('added_to_cart', function(event, fragments, cart_hash, button) {
                    console.log('TONEKA FALLBACK: added_to_cart event received');
                    setTimeout(function() {
                        const minicart = $('.toneka-minicart');
                        const overlay = $('.toneka-minicart-overlay');
                        if (minicart.length && overlay.length && !minicart.hasClass('is-active')) {
                            console.log('TONEKA FALLBACK: Opening minicart');
                            minicart.addClass('is-active');
                            overlay.addClass('is-active');
                            $('body').addClass('minicart-open');
                        }
                    }, 200);
                });
            });
            </script>
            <?php
        }, 25); // Lower priority to run after minicart.js
    }
}
add_action('wp_enqueue_scripts', 'toneka_enqueue_variation_selector_assets', 15); // Load after toneka_theme_scripts

/**
 * Obsługuje dodawanie do koszyka przez AJAX.
 */
function toneka_ajax_add_to_cart() {
    error_log('TONEKA AJAX: Handler called');
    
    check_ajax_referer('wc_add_to_cart_nonce', 'security');
    
    error_log('TONEKA AJAX: Nonce sprawdzony');
    
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
    $variation_id = isset($_POST['variation_id']) ? absint($_POST['variation_id']) : 0;
    
    error_log('TONEKA AJAX: Product ID: ' . $product_id . ', Quantity: ' . $quantity . ', Variation ID: ' . $variation_id);
    
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    
    error_log('TONEKA AJAX: Validation passed: ' . ($passed_validation ? 'YES' : 'NO'));
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {
        error_log('TONEKA AJAX: Product added to cart successfully');
        
        // Trigger WooCommerce hooks
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        
        // Create fragments manually to ensure they work
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Get minicart HTML
        ob_start();
        try {
            toneka_display_custom_minicart();
            $minicart_html = ob_get_clean();
        } catch (Exception $e) {
            ob_end_clean();
            error_log('TONEKA AJAX: ERROR getting minicart HTML: ' . $e->getMessage());
            $minicart_html = '<div>Error loading minicart</div>';
        }
        
        $fragments = array();
        
        // Standard WooCommerce fragments
        $fragments['.cart-count'] = '<span class="cart-count">' . $cart_count . '</span>';
        $fragments['.toneka-minicart'] = '<div class="toneka-minicart">' . $minicart_html . '</div>';
        
        $response_data = array(
            'fragments' => $fragments,
            'cart_hash' => WC()->cart->get_cart_hash(),
            'cart_count' => $cart_count
        );
        
        error_log('TONEKA AJAX: Sending success response with fragments: ' . json_encode(array_keys($fragments)));
        
        // Send successful response with fragments
        wp_send_json_success($response_data);
    } else {
        error_log('TONEKA AJAX: Failed to add product to cart');
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)
        );
        wp_send_json($data);
    }
}
add_action('wp_ajax_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');
add_action('wp_ajax_nopriv_toneka_ajax_add_to_cart', 'toneka_ajax_add_to_cart');

/**
 * =================================================================================
 * Niestandardowa galeria produktu
 * =================================================================================
 */

/**
 * Wyświetla niestandardową galerię zdjęć produktu.
 */
function toneka_show_product_images_custom() {
    global $product;

    if (!$product) {
        return;
    }

    $attachment_ids = $product->get_gallery_image_ids();
    $main_image_id = $product->get_image_id();
    
    // Zbierz wszystkie obrazy (główny + galeria)
    $all_images = array();
    
    if ( $main_image_id ) {
        $all_images[] = $main_image_id;
    }
    
    if ( $attachment_ids ) {
        $all_images = array_merge( $all_images, $attachment_ids );
    }

    echo '<div class="toneka-custom-gallery" data-images="' . count($all_images) . '">';
    
    if ( count($all_images) > 1 ) {
        // Galeria slideshow - więcej niż jeden obraz
        echo '<div class="toneka-gallery-slideshow">';
        
        foreach ( $all_images as $index => $image_id ) {
            $image_url = wp_get_attachment_image_url( $image_id, 'full' );
            $active_class = $index === 0 ? ' active' : '';
            echo '<div class="gallery-slide' . $active_class . '"><img src="' . esc_url( $image_url ) . '" alt=""></div>';
        }
        
        // Dodaj wskaźniki/dots dla slideshow
        if ( count($all_images) > 1 ) {
            echo '<div class="gallery-dots">';
            for ( $i = 0; $i < count($all_images); $i++ ) {
                $active_class = $i === 0 ? ' active' : '';
                echo '<span class="gallery-dot' . $active_class . '" data-slide="' . $i . '"></span>';
            }
            echo '</div>';
        }
        
        echo '</div>';
    } else if ( count($all_images) === 1 ) {
        // Pojedynczy obraz wyróżniający
        $image_url = wp_get_attachment_image_url( $all_images[0], 'full' );
        echo '<div class="toneka-featured-image"><img src="' . esc_url( $image_url ) . '" alt=""></div>';
    } else {
        // Brak obrazów - placeholder
        echo '<div class="toneka-no-image"><span>Brak obrazu</span></div>';
    }

    echo '</div>';
}
// Usuwamy domyślną galerię i dodajemy naszą
remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
add_action( 'woocommerce_before_single_product_summary', 'toneka_show_product_images_custom', 20 );

/**
 * =================================================================================
 * Funkcjonalność próbek audio/wideo
 * =================================================================================
 */

/**
 * Rejestruje meta pole dla próbek.
 */
function toneka_register_product_samples_meta() {
	register_post_meta('product', '_product_samples', [
		'show_in_rest' => true,
		'single' => true,
		'type' => 'array',
        'auth_callback' => function() { return current_user_can('edit_posts'); }
	]);
}
add_action('init', 'toneka_register_product_samples_meta');

/**
 * Dodaje zakładkę "Próbki" do panelu produktu.
 */
function toneka_add_product_samples_tab($tabs) {
	$tabs['samples'] = [
		'label'    => __('Próbki audio/wideo', 'tonekatheme'),
		'target'   => 'product_samples_data',
		'class'    => [],
		'priority' => 65,
	];
	return $tabs;
}
add_filter('woocommerce_product_data_tabs', 'toneka_add_product_samples_tab');

/**
 * Wyświetla panel z próbkami w adminie.
 */
function toneka_product_samples_data_panel() {
	global $post;
	?>
	<div id="product_samples_data" class="panel woocommerce_options_panel">
		<div class="options_group">
			<div class="form-field downloadable_files">
				<label><?php _e('Próbki audio/wideo', 'tonekatheme'); ?></label>
				<table class="widefat">
					<thead>
						<tr>
							<th><?php _e('Nazwa', 'tonekatheme'); ?></th>
							<th><?php _e('Adres URL pliku', 'tonekatheme'); ?></th>
							<th>&nbsp;</th>
						</tr>
					</thead>
					<tbody class="toneka-samples-tbody">
						<?php
						$product_samples = get_post_meta($post->ID, '_product_samples', true);
						if (!empty($product_samples) && is_array($product_samples)) {
							foreach ($product_samples as $key => $file) {
                                toneka_render_sample_row($key, $file);
							}
						}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="3">
								<a href="#" class="button insert-sample-row"><?php _e('Dodaj plik', 'tonekatheme'); ?></a>
							</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
	<?php
}
add_action('woocommerce_product_data_panels', 'toneka_product_samples_data_panel');

/**
 * Renderuje pojedynczy wiersz w tabeli próbek w adminie.
 */
function toneka_render_sample_row($key, $file) {
    ?>
    <tr class="sample-row">
        <td>
            <input type="text" class="input_text" placeholder="<?php esc_attr_e('Nazwa próbki', 'tonekatheme'); ?>" name="_product_sample_names[]" value="<?php echo esc_attr($file['name'] ?? ''); ?>">
        </td>
        <td class="file_url_choose">
            <input type="text" class="input_text" placeholder="https://" name="_product_sample_files[]" value="<?php echo esc_attr($file['file'] ?? ''); ?>">
            <button type="button" class="button upload_sample_file_button"><?php echo esc_html__("Wybierz plik", "tonekatheme"); ?></button>
        </td>
        <td>
            <a href="#" class="delete button"><?php _e('Usuń', 'tonekatheme'); ?></a>
        </td>
    </tr>
    <?php
}

/**
 * Zapisuje dane próbek.
 */
function toneka_save_product_samples($post_id) {
	$samples = [];
	if (isset($_POST['_product_sample_files']) && isset($_POST['_product_sample_names'])) {
		$sample_names = $_POST['_product_sample_names'];
		$sample_files = $_POST['_product_sample_files'];

		for ($i = 0; $i < count($sample_files); $i++) {
			if (!empty($sample_files[$i])) {
				$samples[] = [
					'name' => wc_clean($sample_names[$i]),
					'file' => wc_clean($sample_files[$i]),
				];
			}
		}
	}
	update_post_meta($post_id, '_product_samples', $samples);
}
add_action('woocommerce_process_product_meta', 'toneka_save_product_samples');

/**
 * Ładuje skrypty JS dla panelu admina.
 */
function toneka_enqueue_admin_assets($hook) {
    global $post;

    if ($hook == 'post.php' && isset($post->post_type) && $post->post_type == 'product') {
        wp_enqueue_media();
        wp_enqueue_script(
            'toneka-admin-samples',
            get_template_directory_uri() . '/js/admin-samples.js',
            ['jquery'], '1.0.0', true
        );
        wp_enqueue_script(
            'toneka-admin-dynamic-fields',
            get_template_directory_uri() . '/js/admin-dynamic-fields.js',
            ['jquery'], '1.0.0', true
        );
    }
}
add_action('admin_enqueue_scripts', 'toneka_enqueue_admin_assets');


/**
 * Wyświetla player próbek na stronie produktu zgodny z designem Figma.
 */
function toneka_display_product_samples_player() {
    global $product;
    $samples = get_post_meta($product->get_id(), '_product_samples', true);

    if (empty($samples) || !is_array($samples)) {
        return;
    }

    // Sprawdź czy KTÓRYKOLWIEK z plików jest wideo
    $video_extensions = ['mp4', 'mov', 'webm', 'avi'];
    $has_video = false;
    $first_video_sample = null;
    $first_sample = $samples[0];
    
    foreach ($samples as $sample) {
        $sample_ext = strtolower(pathinfo($sample['file'], PATHINFO_EXTENSION));
        if (in_array($sample_ext, $video_extensions)) {
            $has_video = true;
            if ($first_video_sample === null) {
                $first_video_sample = $sample;
            }
        }
    }
    
    // Jeśli mamy wideo, użyj pierwszego pliku wideo jako głównego
    if ($has_video && $first_video_sample) {
        $first_sample = $first_video_sample;
    }
    
    $file_extension = strtolower(pathinfo($first_sample['file'], PATHINFO_EXTENSION));
    $is_video = in_array($file_extension, $video_extensions);
    
    
    // Pobierz obraz produktu jako tło
    $product_image_id = $product->get_image_id();
    $background_image = $product_image_id ? wp_get_attachment_image_url($product_image_id, 'large') : '';
    
    // Unikalny ID dla tego playera
    $player_id = 'toneka-player-' . $product->get_id();
    ?>
    <div class="toneka-figma-player" id="<?php echo esc_attr($player_id); ?>" data-current-track="0" data-total-tracks="<?php echo count($samples); ?>">
        <!-- Tło wideo/obrazu -->
        <div class="toneka-player-background">
            <!-- Element video (zawsze obecny, JavaScript decyduje o widoczności) -->
            <video class="toneka-background-video" muted loop autoplay playsinline style="display: none;">
                <source src="<?php echo esc_url($first_sample['file']); ?>" type="video/mp4">
                Twoja przeglądarka nie obsługuje odtwarzania wideo.
            </video>
            
            <!-- Obraz produktu (zawsze obecny, JavaScript decyduje o widoczności) -->
            <div class="toneka-background-image" style="background-image: url('<?php echo esc_url($background_image); ?>')"></div>
            
        </div>

        <!-- Główne kontrolki w centrum -->
        <div class="toneka-main-controls">
            <button class="toneka-skip-button toneka-skip-backward" data-skip="-15">
                <div class="toneka-button-bg"></div>
                <svg width="28" height="29" viewBox="0 0 28 29" fill="none">
                    <path d="M11.5486 20.0043V11.5776L9.86328 13.1576" stroke="white" stroke-opacity="0.7" stroke-width="1.264" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14.9189 19.4778C15.3591 19.8084 15.9061 20.0043 16.4989 20.0043C17.9533 20.0043 19.1323 18.8253 19.1323 17.371C19.1323 15.9166 17.9533 14.7376 16.4989 14.7376C15.9061 14.7376 15.3591 14.9335 14.9189 15.2641L15.4456 11.5776H19.1323" stroke="white" stroke-opacity="0.7" stroke-width="1.264" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M6.02998 6.87592C1.26487 11.2903 1.00419 18.7063 5.44772 23.4401C9.89126 28.1739 17.3563 28.4329 22.1214 24.0185C26.8866 19.6042 27.1472 12.1882 22.7037 7.45436C20.1762 4.76176 16.6711 3.51693 13.2414 3.7564M14.9184 1.04443L11.9136 3.89073L14.9184 6.9431" stroke="white" stroke-opacity="0.7" stroke-width="2.10667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            
            <button class="toneka-play-pause-button">
                <div class="toneka-main-button-bg"></div>
                <svg class="toneka-play-icon" width="49" height="50" viewBox="0 0 49 50" fill="none">
                    <path d="M13.5703 12.8604C13.5706 10.9151 15.6761 9.69816 17.3613 10.6699L39.2676 23.3057C40.9541 24.2785 40.9541 26.7127 39.2676 27.6855L17.3613 40.3213C15.676 41.2934 13.5704 40.0773 13.5703 38.1318V12.8604Z" fill="white"/>
                </svg>
                <svg class="toneka-pause-icon" width="28" height="28" viewBox="0 0 24 24" fill="white" style="display: none;">
                    <path d="M6 4h4v16H6V4zm8 0h4v16h-4V4z"/>
                </svg>
            </button>
            
            <button class="toneka-skip-button toneka-skip-forward" data-skip="15">
                <div class="toneka-button-bg"></div>
                <svg width="28" height="29" viewBox="0 0 28 29" fill="none">
                    <path d="M11.3748 20.039V11.6123L9.68945 13.1923" stroke="white" stroke-opacity="0.7" stroke-width="1.264" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M14.7461 19.5125C15.1862 19.8431 15.7333 20.039 16.3261 20.039C17.7804 20.039 18.9594 18.86 18.9594 17.4056C18.9594 15.9513 17.7804 14.7723 16.3261 14.7723C15.7333 14.7723 15.1862 14.9682 14.7461 15.2988L15.2728 11.6123H18.9594" stroke="white" stroke-opacity="0.7" stroke-width="1.264" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M21.9485 6.9101C26.7136 11.3244 26.9743 18.7405 22.5308 23.4743C18.0873 28.2081 10.6222 28.4671 5.85707 24.0527C1.09196 19.6384 0.831277 12.2223 5.27481 7.48854C7.8023 4.79594 11.3074 3.55111 14.7371 3.79058M13.0601 1.07861L16.0649 3.92491L13.0601 6.97728" stroke="white" stroke-opacity="0.7" stroke-width="2.10667" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <!-- Dolny panel z informacjami i kontrolkami -->
        <div class="toneka-bottom-panel">
            <!-- Informacje o utworze -->
            <div class="toneka-track-info">
                <div class="toneka-track-details">
                    <h3 class="toneka-track-title"><?php echo esc_html($product->get_name()); ?></h3>
                    <p class="toneka-track-type"><?php 
                        if ($is_video && in_array($file_extension, ['mp4', 'mov', 'webm', 'avi'])) {
                            echo 'Video';
                        } else {
                            echo 'Audio';
                        }
                    ?></p>
                </div>
                <div class="toneka-track-time">
                    <span class="toneka-current-time">0:00</span> / <span class="toneka-total-time">0:00</span>
                </div>
            </div>
            
            <!-- Progress bar -->
            <div class="toneka-progress-container">
                <div class="toneka-progress-background"></div>
                <div class="toneka-progress-bar"></div>
                <div class="toneka-progress-handle"></div>
            </div>
            
            <!-- Kontrolki -->
            <div class="toneka-bottom-controls">
                <div class="toneka-left-controls">
                    <button class="toneka-control-btn toneka-prev">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
                            <path d="M6 6h2v12H6zm3.5 6l8.5 6V6z"/>
                        </svg>
                    </button>
                    <button class="toneka-control-btn toneka-next">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z"/>
                        </svg>
                    </button>
                </div>
                
                <div class="toneka-right-controls">
                    <button class="toneka-control-btn toneka-playlist">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
                            <path d="M15 6H3v2h12V6zm0 4H3v2h12v-2zM3 16h8v-2H3v2zM17 6v8.18c-.31-.11-.65-.18-1-.18-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3V8h3V6h-5z"/>
                        </svg>
                    </button>
                    <button class="toneka-control-btn toneka-volume">
                        <svg class="toneka-volume-on" width="17" height="17" viewBox="0 0 24 24" fill="white">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/>
                        </svg>
                        <svg class="toneka-volume-off" width="17" height="17" viewBox="0 0 24 24" fill="white" style="display: none;">
                            <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/>
                        </svg>
                    </button>
                    <button class="toneka-control-btn toneka-fullscreen">
                        <svg width="17" height="17" viewBox="0 0 24 24" fill="white">
                            <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Ukryty HTML5 element audio/video -->
        <?php if ($is_video): ?>
            <video class="toneka-hidden-player" preload="metadata" style="display: none;">
                <source src="<?php echo esc_url($first_sample['file']); ?>" type="video/<?php echo $file_extension; ?>">
            </video>
        <?php else: ?>
            <audio class="toneka-hidden-player" preload="metadata" style="display: none;">
                <source src="<?php echo esc_url($first_sample['file']); ?>" type="audio/<?php echo $file_extension; ?>">
            </audio>
        <?php endif; ?>

        <!-- Dane dla JavaScript -->
        <script type="application/json" class="toneka-player-data">
            <?php echo json_encode([
                'samples' => $samples,
                'isVideo' => $is_video,
                'productName' => $product->get_name(),
                'backgroundImage' => $background_image
            ]); ?>
        </script>
    </div>
    <?php
}

/**
 * =================================================================================
 * Wyświetlanie metadanych produktu
 * =================================================================================
 */

/**
 * Konwertuje dane z różnych formatów do nowego formatu array z kluczem 'imie_nazwisko'
 */

/**
 * Generuje link do strony twórcy na podstawie imienia i nazwiska
 */
function toneka_get_creator_link($name) {
    // Szukaj twórcy po nazwie (title)
    $creators = get_posts(array(
        'post_type' => 'creator',
        'title' => $name,
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($creators)) {
        return get_permalink($creators[0]->ID);
    }
    
    // Jeśli nie znaleziono dokładnego dopasowania, spróbuj wyszukać po części nazwy
    $creators = get_posts(array(
        'post_type' => 'creator',
        's' => $name,
        'posts_per_page' => 1,
        'post_status' => 'publish'
    ));
    
    if (!empty($creators)) {
        return get_permalink($creators[0]->ID);
    }
    
    return false;
}

/**
 * Generuje linki dla listy osób
 */
function toneka_generate_person_links($persons, $show_roles = false) {
    $person_links = array();
    foreach ($persons as $person) {
        $name = $person['imie_nazwisko'];
        $creator_link = toneka_get_creator_link($name);
        
        $display_name = $name;
        if ($show_roles && !empty($person['rola'])) {
            $display_name .= ' - <span class="toneka-role">' . esc_html(strtoupper($person['rola'])) . '</span>';
        }
        
        if ($creator_link) {
            $person_links[] = '<a href="' . esc_url($creator_link) . '" class="toneka-creator-link">' . $display_name . '</a>';
        } else {
            $person_links[] = $display_name;
        }
    }
    return implode(', ', $person_links);
}

function toneka_normalize_person_data($data) {
    if (empty($data)) {
        return [];
    }
    
    // Jeśli to już właściwy format (array z kluczami)
    if (is_array($data) && isset($data[0]['imie_nazwisko'])) {
        return $data;
    }
    
    // Jeśli to string - rozdziel po przecinkach
    if (is_string($data)) {
        $names = array_map('trim', explode(',', $data));
        $result = [];
        foreach ($names as $name) {
            if (!empty($name)) {
                $result[] = ['imie_nazwisko' => $name, 'rola' => ''];
            }
        }
        return $result;
    }
    
    // Jeśli to array stringów
    if (is_array($data)) {
        $result = [];
        foreach ($data as $item) {
            if (is_string($item) && !empty($item)) {
                $result[] = ['imie_nazwisko' => $item, 'rola' => ''];
            } elseif (is_array($item) && isset($item['imie_nazwisko'])) {
                $result[] = $item;
            }
        }
        return $result;
    }
    
    return [];
}

/**
 * Wyświetla metadane produktu w sekcji informacji
 */
function toneka_display_product_metadata() {
    global $product;
    
    if (!$product) return;
    
    $product_id = $product->get_id();
    
    // Pobierz metadane
    $rok_produkcji = get_post_meta($product_id, '_rok_produkcji', true);
    $czas_trwania = get_post_meta($product_id, '_czas_trwania', true);
    // Pobierz i znormalizuj dane osób
    $autors = toneka_normalize_person_data(get_post_meta($product_id, '_autors', true));
    $tlumacze = toneka_normalize_person_data(get_post_meta($product_id, '_tlumacze', true));
    $adaptatorzy = toneka_normalize_person_data(get_post_meta($product_id, '_adaptatorzy', true));
    $rezyserzy = toneka_normalize_person_data(get_post_meta($product_id, '_rezyserzy', true));
    $obsada = toneka_normalize_person_data(get_post_meta($product_id, '_obsadas', true));
    $muzycy = toneka_normalize_person_data(get_post_meta($product_id, '_muzycy', true));
    $wydawcy = toneka_normalize_person_data(get_post_meta($product_id, '_wydawcy', true));
    $grafika = toneka_normalize_person_data(get_post_meta($product_id, '_grafika', true));
    
    ?>
    <div class="toneka-product-metadata">
        <!-- Tytuł produktu z czasem trwania -->
        <div class="toneka-title-row">
            <h1><?php the_title(); ?></h1>
            <?php if ($czas_trwania): ?>
                <span class="toneka-duration"><?php echo esc_html($czas_trwania); ?> MIN</span>
            <?php endif; ?>
        </div>
        
        <!-- Kategorie produktu z czasem trwania -->
        <?php 
        $product_categories = get_the_terms(get_the_ID(), 'product_cat');
        $category_links = array();
        if ($product_categories && !is_wp_error($product_categories)) {
            foreach ($product_categories as $category) {
                if ($category->slug !== 'uncategorized') {
                    $category_url = get_term_link($category);
                    $category_links[] = '<a href="' . esc_url($category_url) . '" class="toneka-category-link">' . strtoupper($category->name) . '</a>';
                }
            }
        }
        ?>
        <?php if (!empty($category_links)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label"><?php echo implode(', ', $category_links); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($autors)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">autor:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($autors); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($tlumacze)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">tłumaczenie:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($tlumacze); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($adaptatorzy)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">adaptacja tekstu:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($adaptatorzy); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($rezyserzy)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">reżyseria:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($rezyserzy); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($obsada)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">obsada:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($obsada, true); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($muzycy)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">muzyka:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($muzycy); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($grafika)): ?>
        <div class="toneka-meta-row">
            <span class="toneka-meta-label">grafika:</span>
            <span class="toneka-meta-value"><?php echo toneka_generate_person_links($grafika); ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($wydawcy) || $rok_produkcji): ?>
        <div class="toneka-meta-row toneka-meta-row-split">
            <?php if (!empty($wydawcy)): ?>
            <div class="toneka-meta-half">
                <span class="toneka-meta-label">wydawca:</span>
                <span class="toneka-meta-value"><?php echo toneka_generate_person_links($wydawcy); ?></span>
            </div>
            <?php endif; ?>
            <?php if ($rok_produkcji): ?>
            <div class="toneka-meta-half">
                <span class="toneka-meta-label">rok wydania:</span>
                <span class="toneka-meta-value"><?php echo esc_html($rok_produkcji); ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Opis produktu -->
        <?php if ($product->get_short_description()): ?>
        <div class="toneka-description-row">
            <div class="toneka-product-description">
                <p><span class="toneka-meta-label">Opis:</span> <?php echo wp_kses_post($product->get_short_description()); ?></p>
            </div>
            
            <!-- Główny opis (ukryty domyślnie) -->
            <?php if ($product->get_description()): ?>
            <div class="toneka-full-description" style="display: none;">
                <div class="toneka-full-description-content">
                    <?php echo wp_kses_post($product->get_description()); ?>
                </div>
            </div>
            
            <div class="toneka-toggle-description animated-arrow-button" data-state="collapsed">
                <span class="toggle-text">WIĘCEJ</span>
                <div class="button-arrow">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
                        <path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/>
                        <path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <!-- Tagi -->
        <?php 
        $tags = get_the_terms($product_id, 'product_tag');
        if ($tags && !is_wp_error($tags)): 
        ?>
        <div class="toneka-meta-row toneka-tags-row">
            <span class="toneka-meta-label">tagi:</span>
            <span class="toneka-meta-value">
                <?php 
                $tag_links = array();
                foreach ($tags as $tag) {
                    $tag_links[] = '<a href="' . esc_url(get_term_link($tag)) . '" class="toneka-tag-link">' . esc_html(strtoupper($tag->name)) . '</a>';
                }
                echo implode(', ', $tag_links);
                ?>
            </span>
        </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * =================================================================================
 * Produkty powiązane
 * =================================================================================
 */

/**
 * Wyświetla sugerowany merch dla słuchowiska
 */
function toneka_display_suggested_merch() {
    global $product;
    
    if (!$product) return;
    
    // Pobierz produkty merch (kategorie: merch, gadżety, ubrania)
    $merch_products = wc_get_products(array(
        'limit' => 3,
        'orderby' => 'rand',
        'status' => 'publish',
        'category' => array('merch', 'gadżety', 'ubrania'),
        'exclude' => array($product->get_id())
    ));
    
    // Jeśli brak kategorii merch, szukaj produktów z "merch" w nazwie
    if (empty($merch_products)) {
        $merch_products = wc_get_products(array(
            'limit' => 3,
            'orderby' => 'rand',
            'status' => 'publish',
            'search' => 'merch',
            'exclude' => array($product->get_id())
        ));
    }
    
    // Fallback - losowe produkty
    if (empty($merch_products)) {
        $merch_products = wc_get_products(array(
            'limit' => 3,
            'orderby' => 'rand',
            'status' => 'publish',
            'exclude' => array($product->get_id())
        ));
    }
    
    if (empty($merch_products)) return;
    
    echo '<div class="toneka-suggested-section">';
    echo '<div class="toneka-category-title">';
    echo '<h2>PROPONOWANY MERCH</h2>';
    echo '</div>';
    
    echo '<div class="toneka-products-grid toneka-category-products-grid toneka-suggested-merch-grid">';
    
    foreach ($merch_products as $merch_product) {
        $product_id = $merch_product->get_id();
        $image_url = get_the_post_thumbnail_url($product_id, 'full');
        
        // Get creator name
        if (function_exists('toneka_get_product_creator_name')) {
            $creator_name = toneka_get_product_creator_name($product_id);
        } else {
            $creator_name = 'TONEKA';
        }
        ?>
        
        <div class="toneka-product-card" data-url="<?php echo esc_url($merch_product->get_permalink()); ?>">
            <div class="toneka-product-author">
                <?php echo esc_html($creator_name); ?>
            </div>
            
            <div class="toneka-product-image">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($merch_product->get_name()); ?>">
                <?php else: ?>
                    <div class="toneka-product-placeholder">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                            <rect width="200" height="200" fill="#333"/>
                            <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">BRAK ZDJĘCIA</text>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <div class="toneka-product-footer">
                    <div class="toneka-product-title">
                        <a href="<?php echo esc_url($merch_product->get_permalink()); ?>"><?php echo esc_html(strtoupper($merch_product->get_name())); ?></a>
                    </div>
                    <div class="toneka-product-price">
                        <?php echo $merch_product->get_price_html(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Wyświetla sugerowane słuchowiska
 */
function toneka_display_suggested_audio() {
    global $product;
    
    if (!$product) return;
    
    // Pobierz produkty audio (kategorie: audio, słuchowiska, muzyka)
    $audio_products = wc_get_products(array(
        'limit' => 6, // Pobierz więcej żeby mieć z czego wybierać
        'orderby' => 'rand',
        'status' => 'publish',
        'category' => array('audio', 'słuchowiska', 'muzyka'),
        'exclude' => array($product->get_id())
    ));
    
    // Jeśli brak kategorii audio, szukaj produktów z "audio" w nazwie
    if (empty($audio_products)) {
        $audio_products = wc_get_products(array(
            'limit' => 6,
            'orderby' => 'rand',
            'status' => 'publish',
            'search' => 'audio',
            'exclude' => array($product->get_id())
        ));
    }
    
    // Fallback - losowe produkty
    if (empty($audio_products)) {
        $audio_products = wc_get_products(array(
            'limit' => 6,
            'orderby' => 'rand',
            'status' => 'publish',
            'exclude' => array($product->get_id())
        ));
    }
    
    if (empty($audio_products)) return;
    
    // Usuń duplikaty i weź dokładnie 3 unikalne produkty
    $unique_audio = array();
    $seen_ids = array();
    foreach ($audio_products as $audio_product) {
        $id = $audio_product->get_id();
        if (!in_array($id, $seen_ids) && count($unique_audio) < 3) {
            $unique_audio[] = $audio_product;
            $seen_ids[] = $id;
        }
    }
    $audio_products = $unique_audio;
    
    // Debug: sprawdź ile produktów mamy
    error_log('TONEKA DEBUG: toneka_display_suggested_audio - po filtrowaniu: ' . count($audio_products) . ' produktów');
    error_log('TONEKA DEBUG: IDs produktów: ' . implode(', ', $seen_ids));
    
    if (empty($audio_products)) return;
    
    echo '<div class="toneka-suggested-section">';
    echo '<div class="toneka-category-title">';
    echo '<h2>PROPONOWANE SŁUCHOWISKA</h2>';
    echo '</div>';
    
    // Dodaj klasę CSS w zależności od liczby produktów
    $grid_class = 'toneka-products-grid toneka-category-products-grid toneka-suggested-audio-grid';
    if (count($audio_products) == 2) {
        $grid_class .= ' toneka-grid-2-products';
    } elseif (count($audio_products) == 1) {
        $grid_class .= ' toneka-grid-1-product';
    }
    
    echo '<div class="' . $grid_class . '">';
    
    foreach ($audio_products as $audio_product) {
        $product_id = $audio_product->get_id();
        $image_url = get_the_post_thumbnail_url($product_id, 'full');
        
        // Get creator name
        if (function_exists('toneka_get_product_creator_name')) {
            $creator_name = toneka_get_product_creator_name($product_id);
        } else {
            $creator_name = 'TONEKA';
        }
        ?>
        
        <div class="toneka-product-card" data-url="<?php echo esc_url($audio_product->get_permalink()); ?>">
            <div class="toneka-product-author">
                <?php echo esc_html($creator_name); ?>
            </div>
            
            <div class="toneka-product-image">
                <?php if ($image_url): ?>
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($audio_product->get_name()); ?>">
                <?php else: ?>
                    <div class="toneka-product-placeholder">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                            <rect width="200" height="200" fill="#333"/>
                            <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">BRAK ZDJĘCIA</text>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <div class="toneka-product-footer">
                    <div class="toneka-product-title">
                        <a href="<?php echo esc_url($audio_product->get_permalink()); ?>"><?php echo esc_html(strtoupper($audio_product->get_name())); ?></a>
                    </div>
                    <div class="toneka-product-price">
                        <?php echo $audio_product->get_price_html(); ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Wyświetla produkty powiązane w siatce 3x2
 */
function toneka_display_related_products() {
    global $product;
    
    if (!$product) return;
    
    // Pobierz produkty powiązane
    $related_ids = wc_get_related_products($product->get_id(), 6);
    
    if (empty($related_ids)) {
        // Jeśli brak produktów powiązanych, pokaż losowe produkty
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 6,
            'orderby' => 'rand',
            'post__not_in' => array($product->get_id()),
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock'
                )
            )
        );
        $related_products = get_posts($args);
    } else {
        $related_products = array_map('wc_get_product', $related_ids);
    }
    
    if (empty($related_products)) return;
    
    ?>
    <div class="toneka-products-grid">
        <?php 
        // Ograniczamy do 6 produktów i wyświetlamy w siatce 3x2
        $displayed = 0;
        foreach ($related_products as $related_product) {
            if ($displayed >= 6) break;
            
            if (is_object($related_product)) {
                $product_obj = $related_product;
            } else {
                $product_obj = wc_get_product($related_product->ID);
            }
            
            if (!$product_obj) continue;
            
            // Get creator name based on product category (author for słuchowiska, graphic for merch)
            $creator_name = strtoupper(toneka_get_product_creator_name($product_obj->get_id()));
            
            // Pobierz główny obraz produktu
            $image_id = $product_obj->get_image_id();
            $image_url = wp_get_attachment_image_url($image_id, 'large');
            
            ?>
            <div class="toneka-product-card" data-url="<?php echo esc_url($product_obj->get_permalink()); ?>">
                <div class="toneka-product-author">
                    <?php echo esc_html($creator_name); ?>
                </div>
                
                <div class="toneka-product-image">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_obj->get_name()); ?>">
                    <?php else: ?>
                        <div class="toneka-product-placeholder">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                                <rect width="200" height="200" fill="#333"/>
                                <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">BRAK ZDJĘCIA</text>
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="toneka-product-footer">
                        <div class="toneka-product-title">
                            <a href="<?php echo esc_url($product_obj->get_permalink()); ?>"><?php echo esc_html(strtoupper($product_obj->get_name())); ?></a>
                        </div>
                        <div class="toneka-product-price">
                            <?php echo $product_obj->get_price_html(); ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $displayed++;
        }
        ?>
    </div>
    <?php
}

/**
 * =================================================================================
 * AJAX funkcje dla header'a
 * =================================================================================
 */

/**
 * Zwraca aktualną liczbę produktów w koszyku
 */
function toneka_get_cart_count() {
    if (class_exists('WooCommerce')) {
        wp_send_json_success(array(
            'count' => WC()->cart->get_cart_contents_count()
        ));
    } else {
        wp_send_json_error('WooCommerce not active');
    }
}
add_action('wp_ajax_toneka_get_cart_count', 'toneka_get_cart_count');
add_action('wp_ajax_nopriv_toneka_get_cart_count', 'toneka_get_cart_count');

// AJAX handler for category filtering
add_action('wp_ajax_toneka_filter_category', 'toneka_ajax_filter_category');
add_action('wp_ajax_nopriv_toneka_filter_category', 'toneka_ajax_filter_category');

/**
 * AJAX handler for category filtering
 */
function toneka_ajax_filter_category() {
    // Security check
    if (!wp_verify_nonce($_POST['nonce'], 'toneka_category_filter_nonce')) {
        wp_send_json_error('Security check failed');
        return;
    }
    
    $category_id = intval($_POST['category_id']);
    $paged = isset($_POST['paged']) ? intval($_POST['paged']) : 1;
    
    // Handle special case: category_id = 0 means "WSZYSTKO" (all products)
    if ($category_id === 0) {
        // Get all products
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => get_option('posts_per_page', 12),
            'paged' => $paged
        );
        
        $category_name = 'WSZYSTKO';
        $is_shop_page = true;
        $category = null;
    } else {
        // Get category
        $category = get_term($category_id, 'product_cat');
        if (is_wp_error($category) || !$category) {
            wp_send_json_error('Category not found');
            return;
        }
        
        // Query products in this category
        $args = array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => get_option('posts_per_page', 12),
            'paged' => $paged,
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_id,
                ),
            )
            // Removed problematic meta_query for _visibility
        );
        
        $category_name = $category->name;
        $is_shop_page = false;
    }
    
    $products_query = new WP_Query($args);
    
    // Generate products HTML
    ob_start();
    
    if ($products_query->have_posts()) {
        echo '<div class="toneka-products-grid toneka-category-products-grid">';
        
        while ($products_query->have_posts()) {
            $products_query->the_post();
            global $product;
            
            // Get product data
            $product_id = get_the_ID();
            $product_obj = wc_get_product($product_id);
            $image_url = get_the_post_thumbnail_url($product_id, 'full');
            
            // Get creator name based on product category (author for słuchowiska, graphic for merch)
            $creator_name = toneka_get_product_creator_name($product_id);
            ?>
            
            <div class="toneka-product-card" data-url="<?php echo esc_url($product_obj->get_permalink()); ?>">
                <div class="toneka-product-author">
                    <?php echo esc_html($creator_name); ?>
                </div>
                
                <div class="toneka-product-image">
                    <?php if ($image_url): ?>
                        <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product_obj->get_name()); ?>">
                    <?php else: ?>
                        <div class="toneka-product-placeholder">
                            <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                                <rect width="200" height="200" fill="#333"/>
                                <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">BRAK ZDJĘCIA</text>
                            </svg>
                        </div>
                    <?php endif; ?>
                    
                    <div class="toneka-product-footer">
                        <div class="toneka-product-title">
                            <a href="<?php echo esc_url($product_obj->get_permalink()); ?>"><?php echo esc_html(strtoupper($product_obj->get_name())); ?></a>
                        </div>
                        <div class="toneka-product-price">
                            <?php echo $product_obj->get_price_html(); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
        }
        
        echo '</div>';
        
        // Pagination
        if ($products_query->max_num_pages > 1) {
            echo '<div class="toneka-ajax-pagination" data-max-pages="' . $products_query->max_num_pages . '" data-current-page="' . $paged . '">';
            
            for ($i = 1; $i <= $products_query->max_num_pages; $i++) {
                $class = ($i == $paged) ? 'page-numbers current' : 'page-numbers';
                echo '<a href="#" class="' . $class . '" data-page="' . $i . '">' . $i . '</a>';
            }
            
            echo '</div>';
        }
        
    } else {
        echo '<div class="toneka-no-products"><p>Brak produktów w tej kategorii.</p></div>';
    }
    
    $products_html = ob_get_clean();
    wp_reset_postdata();
    
    // Get category filter HTML
    ob_start();
    
    // Get categories for filter display based on universal logic
    $filter_categories = array();
    $all_display_categories = array();
    
    // Always start with "WSZYSTKO"
    $all_display_categories[] = array(
        'category' => (object) array('term_id' => 0, 'name' => 'WSZYSTKO'),
        'is_current' => ($category_id === 0),
        'is_parent' => false
    );
    
    if ($category_id === 0) {
        // WSZYSTKO page - show only top level categories (Słuchowiska, Merch)
        $top_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => 0,
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        // Add top level categories
        if (!empty($top_categories) && !is_wp_error($top_categories)) {
            foreach ($top_categories as $cat) {
                $all_display_categories[] = array(
                    'category' => $cat,
                    'is_current' => false,
                    'is_parent' => false
                );
            }
        }
        
    } else {
        // Category page logic
        // Check if current category has children
        $child_categories = get_terms(array(
            'taxonomy' => 'product_cat',
            'parent' => $category_id,
            'hide_empty' => true,
            'orderby' => 'name',
            'order' => 'ASC'
        ));
        
        if (!empty($child_categories) && !is_wp_error($child_categories)) {
            // Current category has children - show current + children
            $filter_categories = array_merge(array($category), $child_categories);
            // Sort children only, keep current category first
            $current_cat = array_shift($filter_categories);
            usort($filter_categories, function($a, $b) {
                return strcmp($a->name, $b->name);
            });
            array_unshift($filter_categories, $current_cat);
            
            // Add current category as active
            $all_display_categories[] = array(
                'category' => $current_cat,
                'is_current' => true,
                'is_parent' => false
            );
            
            // Add children
            foreach ($filter_categories as $i => $cat) {
                if ($i === 0) continue; // Skip current category (already added)
                $all_display_categories[] = array(
                    'category' => $cat,
                    'is_current' => false,
                    'is_parent' => false
                );
            }
            
        } elseif ($category->parent != 0) {
            // Current category has no children, show parent + siblings
            $parent_category = get_term($category->parent, 'product_cat');
            $sibling_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => $category->parent,
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            // Add parent category
            if ($parent_category && !is_wp_error($parent_category)) {
                $all_display_categories[] = array(
                    'category' => $parent_category,
                    'is_current' => false,
                    'is_parent' => true
                );
            }
            
            // Add siblings (including current)
            if (!empty($sibling_categories) && !is_wp_error($sibling_categories)) {
                foreach ($sibling_categories as $cat) {
                    $all_display_categories[] = array(
                        'category' => $cat,
                        'is_current' => ($cat->term_id == $category_id),
                        'is_parent' => false
                    );
                }
            }
            
        } else {
            // Top level category - show current category + its children (if any)
            $child_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => $category_id,
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            // Add current category as active
            $all_display_categories[] = array(
                'category' => $category,
                'is_current' => true,
                'is_parent' => false
            );
            
            // Add children if any
            if (!empty($child_categories) && !is_wp_error($child_categories)) {
                foreach ($child_categories as $cat) {
                    $all_display_categories[] = array(
                        'category' => $cat,
                        'is_current' => false,
                        'is_parent' => false
                    );
                }
            }
        }
    }
    
    echo '<div class="toneka-category-filter-container">';
    
    $total_categories = count($all_display_categories);
    $current_index = 0;
    
    foreach ($all_display_categories as $cat_data) {
        $cat = $cat_data['category'];
        $is_current = $cat_data['is_current'];
        
        if ($is_current) {
            echo '<div class="toneka-category-filter-item toneka-category-filter-active">';
            echo '<span>' . esc_html(strtoupper($cat->name)) . '</span>';
            echo '</div>';
        } else {
            echo '<div class="toneka-category-filter-item">';
            echo '<a href="#" data-category-id="' . $cat->term_id . '">';
            echo esc_html(strtoupper($cat->name));
            echo '</a>';
            echo '</div>';
        }
        
        // Add separator (/) between categories, but not after the last one
        $current_index++;
        if ($current_index < $total_categories) {
            echo '<div class="toneka-category-filter-separator">/</div>';
        }
    }
    
    echo '</div>';
    
    $filter_html = ob_get_clean();
    
    // Send response
    wp_send_json_success(array(
        'products_html' => $products_html,
        'filter_html' => $filter_html,
        'category_name' => $category_name,
        'category_id' => $category_id
    ));
}

/**
 * Aktualizuje licznik koszyka po dodaniu produktu
 */
function toneka_update_cart_count_fragment($fragments) {
    if (class_exists('WooCommerce')) {
        $cart_count = WC()->cart->get_cart_contents_count();
        $fragments['.cart-count'] = '<span class="cart-count">' . esc_html($cart_count) . '</span>';
    }
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'toneka_update_cart_count_fragment');

/**
 * =================================================================================
 * Usuwanie domyślnych WooCommerce elementów
 * =================================================================================
 */

// Usuń domyślne WooCommerce wrappers
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

// Usuń breadcrumbs
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );

// Usuń wyświetlanie kategorii produktu nad breadcrumbami
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_before_single_product', 'woocommerce_show_product_categories' );
remove_action( 'woocommerce_before_main_content', 'woocommerce_show_product_categories' );

// Customowe breadcrumbs - zmień "Strona główna" na "WSZYSTKO"
function custom_woocommerce_breadcrumbs() {
    woocommerce_breadcrumb(array(
        'delimiter' => ' / ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb">',
        'wrap_after' => '</nav>',
        'before' => '',
        'after' => '',
        'home' => 'WSZYSTKO'
    ));
}

// Filtr do zmiany linku home w breadcrumbs na stronę sklepu
add_filter('woocommerce_breadcrumb_home_url', function($url) {
    return wc_get_page_permalink('shop');
});

// Filtr do zmiany tekstu home w breadcrumbs
add_filter('woocommerce_breadcrumb_defaults', function($defaults) {
    $defaults['home'] = 'WSZYSTKO';
    return $defaults;
});

// Usuń domyślne zakładki produktu
add_filter( 'woocommerce_product_tabs', '__return_empty_array' );

// Usuń domyślne informacje o produkcie
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );

/**
 * =================================================================================
 * Uniwersalna funkcja pobierania nazwy twórcy produktu
 * =================================================================================
 */

/**
 * Pobiera nazwę twórcy produktu w zależności od kategorii:
 * - MERCH/PLAKATY: grafik
 * - SŁUCHOWISKA: autor
 */
function toneka_get_product_creator_name($product_id) {
    // Get product categories
    $categories = get_the_terms($product_id, 'product_cat');
    $is_merch = false;
    
    if ($categories && !is_wp_error($categories)) {
        foreach ($categories as $category) {
            // Check if product is in MERCH category or its subcategories
            if (strtolower($category->name) === 'merch' || strtolower($category->slug) === 'merch') {
                $is_merch = true;
                break;
            }
            
            // Check if parent category is MERCH
            if ($category->parent != 0) {
                $parent_category = get_term($category->parent, 'product_cat');
                if ($parent_category && !is_wp_error($parent_category)) {
                    if (strtolower($parent_category->name) === 'merch' || strtolower($parent_category->slug) === 'merch') {
                        $is_merch = true;
                        break;
                    }
                }
            }
        }
    }
    
    $creator_name = $is_merch ? 'GRAFIK' : 'AUTOR';
    
    if ($is_merch) {
        // For MERCH products, try to get graphic designer name
        $grafika_meta = get_post_meta($product_id, '_grafika', true);
        
        if (!empty($grafika_meta) && is_array($grafika_meta)) {
            $first_graphic = reset($grafika_meta);
            if (isset($first_graphic['imie_nazwisko']) && !empty($first_graphic['imie_nazwisko'])) {
                $creator_name = $first_graphic['imie_nazwisko'];
            }
        } else if (!empty($grafika_meta) && is_string($grafika_meta)) {
            $creator_name = $grafika_meta;
        }
        
    } else {
        // For SŁUCHOWISKA products, try to get author name
        $autors_meta = get_post_meta($product_id, '_autors', true);
        
        if (!empty($autors_meta) && is_array($autors_meta)) {
            $first_author = reset($autors_meta);
            if (isset($first_author['imie_nazwisko']) && !empty($first_author['imie_nazwisko'])) {
                $creator_name = $first_author['imie_nazwisko'];
            }
        } else {
            $autor_meta = get_post_meta($product_id, 'autor', true);
            if (!empty($autor_meta)) {
                $creator_name = $autor_meta;
            } else {
                $product_obj = wc_get_product($product_id);
                if ($product_obj) {
                    $autor_attribute = $product_obj->get_attribute('autor');
                    if (!empty($autor_attribute)) {
                        $creator_name = $autor_attribute;
                    } else {
                        // Fallback to category name
                        if ($categories && !is_wp_error($categories)) {
                            $cat = reset($categories);
                            $creator_name = $cat->name;
                        }
                    }
                }
            }
        }
    }
    
    return $creator_name;
}

/**
 * Dodaje meta boxy dla post type 'creator'
 */
function toneka_add_creator_meta_boxes() {
    add_meta_box(
        'creator_details',
        'Szczegóły Twórcy',
        'toneka_creator_meta_box_callback',
        'creator',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'toneka_add_creator_meta_boxes');

/**
 * Callback dla meta box twórcy
 */
function toneka_creator_meta_box_callback($post) {
    wp_nonce_field('toneka_save_creator_meta', 'toneka_creator_meta_nonce');
    
    $creator_title = get_post_meta($post->ID, '_creator_title', true);
    ?>
    
    <table class="form-table">
        <tr>
            <th><label for="creator_title">Tytuł/Funkcja (wyświetlana obok nazwiska):</label></th>
            <td>
                <input type="text" id="creator_title" name="creator_title" value="<?php echo esc_attr($creator_title); ?>" style="width: 100%;" />
                <p class="description">Np. "MUZYK, REŻYSER, CEO" - będzie wyświetlane obok nazwiska w tytule</p>
            </td>
        </tr>
    </table>
    
    <?php
}

/**
 * Zapisuje meta dane twórcy
 */
function toneka_save_creator_meta($post_id) {
    if (!isset($_POST['toneka_creator_meta_nonce']) || !wp_verify_nonce($_POST['toneka_creator_meta_nonce'], 'toneka_save_creator_meta')) {
        return;
    }

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    if (isset($_POST['creator_title'])) {
        update_post_meta($post_id, '_creator_title', sanitize_text_field($_POST['creator_title']));
    }
}
add_action('save_post', 'toneka_save_creator_meta');

/**
 * AJAX handler for loading more creators (infinity scroll)
 */
function toneka_load_more_creators() {
    // Set proper charset for Polish characters
    header('Content-Type: application/json; charset=UTF-8');
    
    // Verify nonce
    if (!wp_verify_nonce($_POST['nonce'], 'toneka_load_creators_nonce')) {
        wp_die('Security check failed');
    }
    
    // Set locale for proper character handling
    setlocale(LC_CTYPE, 'pl_PL.UTF-8', 'Polish_Poland.1250', 'polish');
    
    $page = intval($_POST['page']);
    $posts_per_page = intval($_POST['posts_per_page']);
    
    $creators_query = new WP_Query(array(
        'post_type' => 'creator',
        'posts_per_page' => $posts_per_page,
        'paged' => $page,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    
    $html = '';
    
    if ($creators_query->have_posts()) {
        while ($creators_query->have_posts()) {
            $creators_query->the_post();
            $creator_title = get_post_meta(get_the_ID(), '_creator_title', true);
            $creator_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30, '...');
            
            ob_start();
            ?>
            <div class="toneka-creator-item toneka-fade-in" data-creator-id="<?php echo get_the_ID(); ?>">
                <div class="toneka-creator-content">
                    <div class="toneka-creator-header">
                        <h2 class="toneka-creator-name">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo toneka_strtoupper_polish(get_the_title()); ?>
                            </a>
                            <?php if (!empty($creator_title)): ?>
                                <span class="toneka-creator-function"><?php echo toneka_strtoupper_polish($creator_title); ?></span>
                            <?php endif; ?>
                        </h2>
                    </div>
                    <div class="toneka-creator-excerpt">
                        <?php echo wpautop($creator_excerpt); ?>
                    </div>
                </div>
            </div>
            <?php
            $html .= ob_get_clean();
        }
        wp_reset_postdata();
        
        // Ensure UTF-8 encoding for Polish characters
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');
        
        wp_send_json_success(array(
            'html' => $html,
            'has_more' => $page < $creators_query->max_num_pages,
            'current_page' => $page,
            'max_pages' => $creators_query->max_num_pages
        ));
    } else {
        wp_send_json_success(array(
            'html' => '',
            'has_more' => false,
            'current_page' => $page,
            'max_pages' => 0
        ));
    }
}
add_action('wp_ajax_toneka_load_more_creators', 'toneka_load_more_creators');
add_action('wp_ajax_nopriv_toneka_load_more_creators', 'toneka_load_more_creators');

/**
 * Helper function for proper Polish character uppercase conversion
 */
function toneka_strtoupper_polish($string) {
    // First try mb_strtoupper if available
    if (function_exists('mb_strtoupper')) {
        return mb_strtoupper($string, 'UTF-8');
    }
    
    // Fallback with Polish character mapping
    $polish_chars = array(
        'ą' => 'Ą', 'ć' => 'Ć', 'ę' => 'Ę', 'ł' => 'Ł', 'ń' => 'Ń',
        'ó' => 'Ó', 'ś' => 'Ś', 'ź' => 'Ź', 'ż' => 'Ż'
    );
    
    $string = strtr($string, $polish_chars);
    return strtoupper($string);
}

