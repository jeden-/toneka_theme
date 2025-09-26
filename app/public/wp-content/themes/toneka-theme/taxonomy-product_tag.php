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
        
        <!-- Filter Section -->
        <div class="toneka-category-filter-section">
            <div class="toneka-category-filter-container">
                <!-- Bieżący tag -->
                <div class="toneka-category-filter-item toneka-category-filter-active">
                    <span><?php echo esc_html(strtoupper($tag_name)); ?></span>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="toneka-products-grid" id="toneka-products-container">
            <?php
            if (woocommerce_product_loop()) {
                woocommerce_product_loop_start();

                if (wc_get_loop_prop('is_shortcode')) {
                    $columns = absint(wc_get_loop_prop('columns'));
                } else {
                    $columns = wc_get_default_products_per_row();
                }

                while (have_posts()) {
                    the_post();
                    global $product;
                    ?>
                    <div class="toneka-product-card">
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="toneka-product-link">
                            <!-- Product Image -->
                            <div class="toneka-product-image-container">
                                <?php if (has_post_thumbnail()): ?>
                                    <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'full')); ?>" 
                                         alt="<?php echo esc_attr(get_the_title()); ?>" 
                                         class="toneka-product-image">
                                <?php else: ?>
                                    <div class="toneka-product-placeholder">
                                        <span class="toneka-placeholder-text">BRAK ZDJĘCIA</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="toneka-product-info">
                                <!-- Author/Creator Info -->
                                <?php
                                // Get product categories to determine if it's MERCH or SŁUCHOWISKA
                                $product_categories = wp_get_post_terms(get_the_ID(), 'product_cat');
                                $is_merch = false;
                                $is_sluchowiska = false;

                                foreach ($product_categories as $cat) {
                                    if (strtoupper($cat->name) === 'MERCH') {
                                        $is_merch = true;
                                        break;
                                    }
                                    if (strtoupper($cat->name) === 'SŁUCHOWISKA') {
                                        $is_sluchowiska = true;
                                        break;
                                    }
                                }

                                // Display appropriate creator
                                if ($is_merch) {
                                    $grafika = get_post_meta(get_the_ID(), '_grafika', true);
                                    if (!empty($grafika)) {
                                        echo '<div class="toneka-product-author">' . esc_html(strtoupper($grafika)) . '</div>';
                                    }
                                } elseif ($is_sluchowiska) {
                                    $autors = get_post_meta(get_the_ID(), '_autors', true);
                                    if (!empty($autors)) {
                                        echo '<div class="toneka-product-author">' . esc_html(strtoupper($autors)) . '</div>';
                                    }
                                }
                                ?>
                                
                                <!-- Product Title -->
                                <h3 class="toneka-product-title"><?php echo esc_html(strtoupper(get_the_title())); ?></h3>
                                
                                <!-- Product Price -->
                                <div class="toneka-product-price">
                                    <?php echo $product->get_price_html(); ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                }

                woocommerce_product_loop_end();
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

