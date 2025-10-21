<?php
/**
 * The template for displaying archive pages for the 'creator' post type.
 *
 * @package Toneka_Theme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); 

// Get customizer settings for creators archive
$hero_image_url = get_theme_mod('creators_archive_hero_image', '');
$hero_title = get_theme_mod('creators_archive_hero_title', 'TWÓRCY');
$hero_description = get_theme_mod('creators_archive_hero_description', 'Poznaj twórców, którzy współtworzą nasz świat dźwięków i obrazów. Artyści, muzycy, graficy i reżyserzy, którzy nadają kształt naszym produkcjom.');
?>

<!-- Hero Section -->
<div class="toneka-hero-section toneka-creators-archive-hero">
    <div class="toneka-hero-left">
        <div class="toneka-hero-content">
            
            <!-- Page Title -->
            <h1 class="toneka-product-title toneka-creators-archive-title"><?php echo esc_html($hero_title); ?></h1>
            
            <!-- Description -->
            <div class="toneka-creators-archive-description">
                <?php echo wp_kses_post(wpautop($hero_description)); ?>
            </div>
            
            <!-- Scroll Button -->
            <a href="#creators-section" class="toneka-listen-button toneka-scroll-button animated-arrow-button">
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
        <?php if (!empty($hero_image_url)): ?>
            <img src="<?php echo esc_url($hero_image_url); ?>" alt="Twórcy" class="toneka-hero-image">
        <?php else: ?>
            <!-- Placeholder if no image -->
            <div class="toneka-hero-placeholder">
                <div class="toneka-placeholder-content">
                    <span class="toneka-placeholder-text">TWÓRCY</span>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Creators List Section -->
<div class="toneka-content-section toneka-creators-archive-content" id="creators-section">
    <div class="toneka-content-container">
        
        <!-- Creators List -->
        <div class="toneka-creators-list" id="creators-list-container">
            <?php
            // Set up query for initial creators (first 5)
            $creators_query = new WP_Query(array(
                'post_type' => 'creator',
                'posts_per_page' => 5,
                'paged' => 1,
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC'
            ));

            if ($creators_query->have_posts()) :
                while ($creators_query->have_posts()) : $creators_query->the_post();
                    $creator_title = get_post_meta(get_the_ID(), '_creator_title', true);
                    $creator_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30, '...');
                    ?>
                    <div class="toneka-creator-item" data-creator-id="<?php echo get_the_ID(); ?>">
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
                endwhile;
                wp_reset_postdata();
            else :
                ?>
                <div class="toneka-no-creators">
                    <p>Brak twórców do wyświetlenia.</p>
                </div>
                <?php
            endif;
            ?>
        </div>
        
        <!-- Loading indicator for infinity scroll -->
        <div class="toneka-creators-loading" id="creators-loading" style="display: none;">
            <div class="toneka-loading-spinner">
                <div class="toneka-spinner"></div>
                <span>Ładowanie kolejnych twórców...</span>
            </div>
        </div>
        
        <!-- End marker for infinity scroll -->
        <div class="toneka-creators-end" id="creators-end" style="display: none;">
            <p>To już wszyscy nasi twórcy!</p>
        </div>
        
    </div>
</div>

<!-- Pass data to JavaScript -->
<script type="text/javascript">
    window.toneka_creators_data = {
        ajax_url: '<?php echo admin_url('admin-ajax.php'); ?>',
        nonce: '<?php echo wp_create_nonce('toneka_load_creators_nonce'); ?>',
        current_page: 1,
        max_pages: <?php echo $creators_query->max_num_pages; ?>,
        posts_per_page: 5
    };
</script>

<?php get_footer(); ?>
