<?php
/**
 * Template for displaying the front page (home page)
 */

get_header(); ?>

<!-- Hero Section - używa standardowej struktury jak inne strony -->
<div class="toneka-hero-section">
    <div class="toneka-hero-left">
        <div class="toneka-hero-content">
            
            <!-- Breadcrumb -->
            <div class="woocommerce-breadcrumb">
                <a href="<?php echo esc_url(home_url('/')); ?>">WSZYSTKO</a> / STRONA GŁÓWNA
            </div>
            
            <!-- Hero Text -->
            <div class="toneka-product-title">
                <?php
                $hero_text = get_theme_mod('toneka_homepage_hero_text', 'Melodie jak fale, co w stronę marzeń płyną, a dźwięki to słowa, co w ciszy z duszą śpiewają');
                echo '<h1>' . esc_html(strtoupper($hero_text)) . '</h1>';
                ?>
            </div>
            
            <!-- Button -->
            <?php
            $button_text = get_theme_mod('toneka_homepage_button_text', 'ostatnio dodane');
            $button_url = get_theme_mod('toneka_homepage_button_url', wc_get_page_permalink('shop'));
            ?>
            <a href="<?php echo esc_url($button_url); ?>" class="toneka-listen-button toneka-filter-button animated-arrow-button">
                <span class="button-text"><?php echo esc_html(strtoupper($button_text)); ?></span>
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
        <?php
        $hero_image = get_theme_mod('toneka_homepage_hero_image');
        if ($hero_image) {
            echo '<img src="' . esc_url($hero_image) . '" alt="Hero Image" class="toneka-hero-image" style="width: 100%; height: 100%; object-fit: cover;" />';
        } else {
            echo '<div class="toneka-hero-placeholder" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #666666; border: 2px dashed #333333;">Dodaj zdjęcie w Dostosuj → Strona Główna</div>';
        }
        ?>
    </div>
</div>
    
<!-- Main Content Area -->
<div class="toneka-category-page">
    <div class="toneka-category-content">
        
        <!-- Najnowsze Słuchowiska Section -->
        <div class="toneka-category-title">
            <h2>NAJNOWSZE SŁUCHOWISKA</h2>
        </div>
        
        <!-- Słuchowiska Grid - 3 produkty w jednym rzędzie -->
        <div class="toneka-products-grid toneka-category-products-grid toneka-homepage-audio-grid">
            <?php
            // Check if WooCommerce is active and get latest audio products
            if (function_exists('wc_get_products')) {
                $audio_products = wc_get_products(array(
                    'limit' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'category' => array('audio', 'słuchowiska', 'muzyka') // Assuming audio category exists
                ));
                
                // If no specific audio category, get latest products
                if (empty($audio_products)) {
                    $audio_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish'
                    ));
                }
                
                if ($audio_products) {
                    foreach ($audio_products as $product) {
                        $product_id = $product->get_id();
                        $product_obj = $product;
                        $image_url = get_the_post_thumbnail_url($product_id, 'full');
                        
                        // Get creator name using the same function as category pages
                        if (function_exists('toneka_get_product_creator_name')) {
                            $creator_name = toneka_get_product_creator_name($product_id);
                        } else {
                            $creator_name = 'TONEKA';
                        }
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
                } else {
                    echo '<div class="toneka-no-products"><p>Brak słuchowisk do wyświetlenia.</p></div>';
                }
            } else {
                echo '<div class="toneka-no-products"><p>WooCommerce nie jest aktywne.</p></div>';
            }
            ?>
        </div>
        
        <!-- Merch Section -->
        <div class="toneka-category-title">
            <h2>MERCH</h2>
        </div>
        
        <!-- Merch Grid - 3 produkty w jednym rzędzie -->
        <div class="toneka-products-grid toneka-category-products-grid toneka-homepage-merch-grid">
            <?php
            // Check if WooCommerce is active and get merch products
            if (function_exists('wc_get_products')) {
                $merch_products = wc_get_products(array(
                    'limit' => 3,
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'status' => 'publish',
                    'category' => array('merch', 'gadżety', 'ubrania') // Assuming merch category exists
                ));
                
                // If no specific merch category, get products with "merch" in name
                if (empty($merch_products)) {
                    $merch_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish',
                        'search' => 'merch'
                    ));
                }
                
                // If still no merch products, get latest products as fallback
                if (empty($merch_products)) {
                    $merch_products = wc_get_products(array(
                        'limit' => 3,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'status' => 'publish'
                    ));
                }
                
                if ($merch_products) {
                    foreach ($merch_products as $product) {
                        $product_id = $product->get_id();
                        $product_obj = $product;
                        $image_url = get_the_post_thumbnail_url($product_id, 'full');
                        
                        // Get creator name using the same function as category pages
                        if (function_exists('toneka_get_product_creator_name')) {
                            $creator_name = toneka_get_product_creator_name($product_id);
                        } else {
                            $creator_name = 'TONEKA';
                        }
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
                } else {
                    echo '<div class="toneka-no-products"><p>Brak produktów merch do wyświetlenia.</p></div>';
                }
            } else {
                echo '<div class="toneka-no-products"><p>WooCommerce nie jest aktywne.</p></div>';
            }
            ?>
        </div>
        
    </div>
</div>

<?php get_footer(); ?>
