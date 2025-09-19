<?php
/**
 * The Template for displaying product category archives
 *
 * @package Toneka
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<!-- DEBUG: TONEKA CUSTOM CATEGORY TEMPLATE LOADED -->
<?php
// Get current category
$current_category = get_queried_object();
$category_id = $current_category->term_id;
$category_name = $current_category->name;
$category_description = $current_category->description;

// Get category image
$category_image_id = get_term_meta($category_id, 'thumbnail_id', true);
$category_image_url = $category_image_id ? wp_get_attachment_image_url($category_image_id, 'full') : '';
?>

<!-- Hero Section - Idealne kwadraty 50/50 jak na stronie produktu -->
<div class="toneka-hero-section toneka-category-hero">
    <div class="toneka-hero-left">
        <!-- Główna zawartość hero -->
        <div class="toneka-hero-content">
            
            <?php woocommerce_breadcrumb(); ?>
            
            <!-- Tytuł kategorii -->
            <h1 class="toneka-product-title toneka-category-title"><?php echo esc_html(strtoupper($category_name)); ?></h1>
            
            <!-- Opis kategorii -->
            <?php if ($category_description): ?>
                <div class="toneka-category-description">
                    <?php echo wp_kses_post($category_description); ?>
                </div>
            <?php endif; ?>
            
            <!-- Przycisk zobacz/filtr -->
            <a href="#products-section" class="toneka-listen-button toneka-filter-button animated-arrow-button">
                <span class="button-text">ZOBACZ</span>
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
        <!-- Zdjęcie kategorii -->
        <div class="toneka-category-image">
            <?php if ($category_image_url): ?>
                <img src="<?php echo esc_url($category_image_url); ?>" alt="<?php echo esc_attr($category_name); ?>">
            <?php else: ?>
                <!-- Placeholder jeśli brak zdjęcia -->
                <div class="toneka-category-placeholder">
                    <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                        <rect width="200" height="200" fill="#333"/>
                        <text x="100" y="100" text-anchor="middle" fill="white" font-size="14"><?php echo esc_html(strtoupper($category_name)); ?></text>
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
            // Get current category
            $current_category = get_queried_object();
            $current_category_id = $current_category->term_id;
            
            // Get categories for filter display - universal logic with WSZYSTKO
            $filter_categories = array();
            $all_display_categories = array();
            
            // Always start with "WSZYSTKO"
            $all_display_categories[] = array(
                'category' => (object) array('term_id' => 0, 'name' => 'WSZYSTKO'),
                'is_current' => false,
                'is_parent' => false
            );
            
            // Check if current category has children
            $child_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'parent' => $current_category_id,
                'hide_empty' => true,
                'orderby' => 'name',
                'order' => 'ASC'
            ));
            
            if (!empty($child_categories) && !is_wp_error($child_categories)) {
                // Current category has children - show current + children
                $filter_categories = array_merge(array($current_category), $child_categories);
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
                
            } elseif ($current_category->parent != 0) {
                // Current category has no children, show parent + siblings
                $parent_category = get_term($current_category->parent, 'product_cat');
                $sibling_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $current_category->parent,
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
                            'is_current' => ($cat->term_id == $current_category_id),
                            'is_parent' => false
                        );
                    }
                }
                
            } else {
                // Top level category - show current category + its children (if any)
                $child_categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => $current_category_id,
                    'hide_empty' => true,
                    'orderby' => 'name',
                    'order' => 'ASC'
                ));
                
                // Add current category as active
                $all_display_categories[] = array(
                    'category' => $current_category,
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
                
                <?php endwhile; ?>
            </div>
            
            <!-- Pagination -->
            <?php
            woocommerce_pagination();
            ?>
            
        <?php else : ?>
            
            <div class="toneka-no-products">
                <p><?php esc_html_e('Brak produktów w tej kategorii.', 'toneka'); ?></p>
            </div>
            
        <?php endif; ?>
        
    </div>
</div>

<?php get_footer(); ?>
