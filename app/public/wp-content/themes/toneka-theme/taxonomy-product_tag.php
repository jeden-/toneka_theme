<?php
/**
 * The Template for displaying product tag archives
 *
 * @package Toneka
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<!-- DEBUG: TONEKA CUSTOM TAG TEMPLATE LOADED -->
<?php
// Get current tag
$current_tag = get_queried_object();
$tag_id = $current_tag->term_id;
$tag_name = $current_tag->name;
$tag_description = $current_tag->description;

// Get tag image (if exists)
$tag_image_id = get_term_meta($tag_id, 'thumbnail_id', true);
$tag_image_url = $tag_image_id ? wp_get_attachment_image_url($tag_image_id, 'full') : '';

// Fallback image if no tag image
if (!$tag_image_url) {
    $tag_image_url = get_template_directory_uri() . '/images/default-tag-hero.jpg';
}
?>

<!-- Hero Section - Idealne kwadraty 50/50 jak na stronie produktu -->
<div class="toneka-hero-section toneka-tag-hero">
    <div class="toneka-hero-left">
        <!-- Główna zawartość hero -->
        <div class="toneka-hero-content">
            
            <?php woocommerce_breadcrumb(); ?>
            
            <!-- Tytuł tagu -->
            <h1 class="toneka-product-title toneka-tag-title"><?php echo esc_html(strtoupper($tag_name)); ?></h1>
            
            <!-- Opis tagu -->
            <?php if ($tag_description): ?>
                <div class="toneka-tag-description">
                    <?php echo wp_kses_post($tag_description); ?>
                </div>
            <?php endif; ?>
            
            <!-- Przycisk zobacz -->
            <a href="#products-section" class="toneka-listen-button toneka-filter-button animated-arrow-button">
                <span class="button-text">ZOBACZ</span>
                <div class="button-arrow">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
                        <path d="M1.13636 1L11 0.969819" stroke="white" stroke-linecap="round"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
    
    <div class="toneka-hero-right">
        <!-- Losowy wyróżniony produkt z tagu -->
        <?php toneka_display_tag_hero_product($tag_id); ?>
    </div>
</div>

<!-- Products Section -->
<div class="toneka-content-section toneka-tag-products" id="products-section">
    <div class="toneka-content-container">
        
        <!-- Section Title -->
        <div class="toneka-category-title">
            <h2><?php echo esc_html(strtoupper($tag_name)); ?></h2>
        </div>
        
        <!-- Products Grid -->
        <div class="toneka-products-grid toneka-category-products-grid">
            <?php
            if (woocommerce_product_loop()) {
                while (have_posts()) :
                    the_post();
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
                
                <?php endwhile;
            } else {
                ?>
                <div class="toneka-no-products">
                    <p>Brak produktów z tym tagiem.</p>
                </div>
                <?php
            }
            ?>
        </div>
        
        <!-- Pagination -->
        <?php
        woocommerce_pagination();
        ?>
    </div>
</div>

<?php get_footer(); ?>

