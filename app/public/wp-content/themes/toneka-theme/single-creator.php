<?php
/**
 * The template for displaying a single 'creator' post type.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toneka_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<?php
// Start the Loop.
while ( have_posts() ) :
    the_post();
    
    $creator_name = get_the_title();
    $creator_title = get_post_meta(get_the_ID(), '_creator_title', true);
    $creator_description = get_the_content();
    $creator_image_url = get_the_post_thumbnail_url(get_the_ID(), 'full');
    ?>

    <!-- Hero Section - Idealne kwadraty 50/50 jak na stronie produktu -->
    <div class="toneka-hero-section toneka-creator-hero">
        <div class="toneka-hero-left">
            <!-- Główna zawartość hero -->
            <div class="toneka-hero-content">
                
                <!-- Breadcrumb -->
                <nav class="toneka-breadcrumb">
                    <a href="<?php echo get_post_type_archive_link('creator'); ?>">TWÓRCY</a>
                    <span class="breadcrumb-separator">/</span>
                    <span class="breadcrumb-current"><?php echo esc_html(strtoupper($creator_name)); ?></span>
                </nav>
                
                <!-- Imię i nazwisko twórcy -->
                <h1 class="toneka-product-title toneka-creator-title">
                    <?php echo esc_html(strtoupper($creator_name)); ?>
                </h1>
                
                <!-- Przycisk zobacz portfolio -->
                <a href="#portfolio-section" class="toneka-listen-button toneka-portfolio-button animated-arrow-button">
                    <span class="button-text">PORTFOLIO</span>
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
            <!-- Zdjęcie twórcy -->
            <div class="toneka-creator-image">
                <?php if ($creator_image_url): ?>
                    <img src="<?php echo esc_url($creator_image_url); ?>" alt="<?php echo esc_attr($creator_name); ?>">
                <?php else: ?>
                    <!-- Placeholder jeśli brak zdjęcia -->
                    <div class="toneka-creator-placeholder">
                        <svg width="200" height="200" viewBox="0 0 200 200" fill="#333">
                            <rect width="200" height="200" fill="#333"/>
                            <text x="100" y="100" text-anchor="middle" fill="white" font-size="14"><?php echo esc_html(strtoupper($creator_name)); ?></text>
                        </svg>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Creator Info Section - 50/50 jak w produkcie -->
    <div class="toneka-product-info-section" id="portfolio-section">
        <div class="toneka-product-details">
            <!-- Imię i nazwisko jako tytuł z linią -->
            <div class="toneka-title-row">
                <h1><?php echo esc_html($creator_name); ?></h1>
                <?php if ($creator_title): ?>
                    <span class="toneka-creator-title-right"><?php echo esc_html(strtoupper($creator_title)); ?></span>
                <?php endif; ?>
            </div>
            
            <!-- Opis - główny tekst -->
            <div class="toneka-creator-description">
                <?php echo wp_kses_post($creator_description); ?>
            </div>
        </div>
        
        <div class="toneka-creator-portfolio-section">
            <?php
            // Pobierz produkty związane z twórcą
            $meta_query_clauses = [];
            $creator_fields = [
                '_autors', '_obsada', '_rezyserzy', '_muzycy',
                '_tlumacze', '_adaptatorzy', '_wydawcy', '_grafika'
            ];

            foreach ($creator_fields as $field) {
                $meta_query_clauses[] = [
                    'key' => $field,
                    'value' => '"' . $creator_name . '"',
                    'compare' => 'LIKE'
                ];
            }

            $args = [
                'post_type' => 'product',
                'posts_per_page' => -1,
                'meta_query' => [
                    'relation' => 'OR',
                    ...$meta_query_clauses
                ]
            ];

            $related_products = new WP_Query($args);

            if ($related_products->have_posts()) :
                echo '<div class="toneka-portfolio-grid">';
                while ($related_products->have_posts()) : $related_products->the_post();
                    $product_image = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                    ?>
                    <div class="toneka-portfolio-item">
                        <a href="<?php the_permalink(); ?>" class="toneka-portfolio-link">
                            <?php if ($product_image): ?>
                                <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="toneka-portfolio-image">
                            <?php else: ?>
                                <div class="toneka-portfolio-placeholder">
                                    <span><?php echo esc_html(get_the_title()); ?></span>
                                </div>
                            <?php endif; ?>
                        </a>
                    </div>
                    <?php
                endwhile;
                echo '</div>';
                wp_reset_postdata();
            else :
                echo '<div class="toneka-no-portfolio">';
                echo '<p>' . esc_html__('Brak realizacji do wyświetlenia.', 'tonekatheme') . '</p>';
                echo '</div>';
            endif;
            ?>
        </div>
    </div>

    <?php
endwhile; // End of the loop.
?>

<?php
get_footer();
