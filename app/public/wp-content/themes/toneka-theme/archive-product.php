<?php
/**
 * The Template for displaying product archives (shop page)
 *
 * @package Toneka
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<!-- DEBUG: TONEKA CUSTOM SHOP TEMPLATE LOADED -->

<!-- Hero Section - Shop page -->
<div class="toneka-hero-section toneka-category-hero">
    <div class="toneka-hero-left">
        <!-- Główna zawartość hero -->
        <div class="toneka-hero-content">
            
            <!-- Tytuł sklepu -->
            <h1 class="toneka-product-title toneka-category-title">SKLEP</h1>
            
            <!-- Opis sklepu -->
            <div class="toneka-category-description">
                <p>Odkryj nasze produkty - słuchowiska i merchandising</p>
            </div>
            
            <!-- Przycisk zobacz/filtr -->
            <a href="#products-section" class="toneka-listen-button toneka-filter-button animated-arrow-button">
                <span class="button-text">ZOBACZ</span>
                <div class="button-arrow">
                    <svg viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
                        <path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/>
                        <path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    
    <div class="toneka-hero-right">
        <!-- Zdjęcie strony sklepu -->
        <div class="toneka-category-image">
            <?php
            // Get shop page ID and featured image
            $shop_page_id = wc_get_page_id('shop');
            $shop_image_url = get_the_post_thumbnail_url($shop_page_id, 'full');
            
            if ($shop_image_url): ?>
                <img src="<?php echo esc_url($shop_image_url); ?>" alt="Sklep">
            <?php else: ?>
                <!-- Placeholder jeśli brak zdjęcia -->
                <div class="toneka-category-placeholder">
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                        <rect width="200" height="200" fill="#333"/>
                        <text x="100" y="100" text-anchor="middle" fill="white" font-size="14">SKLEP</text>
                    </svg>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Products Section -->
<div class="toneka-category-products-section" id="products-section">
    <div class="toneka-category-products-container">
        
        <!-- Category Filter Section -->
        <div class="toneka-category-filter-section">
            <?php
            // Universal filter logic for shop page (WSZYSTKO active)
            $all_display_categories = array();
            
            // Always start with "WSZYSTKO" - active on shop page
            $all_display_categories[] = array(
                'category' => (object) array('term_id' => 0, 'name' => 'WSZYSTKO'),
                'is_current' => true,
                'is_parent' => false
            );
            
            // Get top level categories (Słuchowiska, Merch)
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
            ?>
            
            <div class="toneka-category-filter-container">
                <?php
                $total_categories = count($all_display_categories);
                $current_index = 0;
                
                foreach ($all_display_categories as $cat_data):
                    $category = $cat_data['category'];
                    $is_current = $cat_data['is_current'];
                    ?>
                    
                    <?php if ($is_current): ?>
                        <!-- Current Category (Active) -->
                        <div class="toneka-category-filter-item toneka-category-filter-active">
                            <span><?php echo esc_html(strtoupper($category->name)); ?></span>
                        </div>
                    <?php else: ?>
                        <!-- Other categories -->
                        <div class="toneka-category-filter-item">
                            <a href="#" data-category-id="<?php echo esc_attr($category->term_id); ?>" data-category-url="<?php echo $category->term_id === 0 ? esc_url(wc_get_page_permalink('shop')) : esc_url(get_term_link($category)); ?>">
                                <?php echo esc_html(strtoupper($category->name)); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                    // Add separator (/) between categories, but not after the last one
                    $current_index++;
                    if ($current_index < $total_categories): ?>
                        <div class="toneka-category-filter-separator">/</div>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            </div>
        </div>
        
        <?php if (have_posts()) : ?>
            
            <!-- Products Grid -->
            <div class="toneka-products-grid toneka-category-products-grid">
                <?php
                while (have_posts()) :
                    the_post();
                    global $product;
                    
                    // Use new product card function
                    echo toneka_render_product_card(get_the_ID());
                ?>
                
                <?php endwhile; ?>
            </div>
            
            <?php
            // Pagination
            woocommerce_pagination();
            ?>
            
        <?php else : ?>
            <div class="toneka-no-products">
                <p><?php _e('Brak produktów w sklepie.', 'woocommerce'); ?></p>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>
