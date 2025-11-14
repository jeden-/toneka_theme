<?php
/**
 * The template for displaying single blog posts
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

<main id="main" class="site-main">
    <div class="toneka-content-container">
        <div class="toneka-post-single-wrapper">
            <?php if ( is_active_sidebar( 'post-sidebar' ) ) : ?>
                <aside class="toneka-post-sidebar">
                    <?php dynamic_sidebar( 'post-sidebar' ); ?>
                </aside>
            <?php endif; ?>
            
            <div class="toneka-post-single-main">
                <?php
                while ( have_posts() ) :
                    the_post();
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('toneka-post-single'); ?>>
                        
                        <header class="toneka-post-single-header">
                            <?php
                            the_title( '<h1 class="toneka-post-single-title">', '</h1>' );
                            
                            if ( 'post' === get_post_type() ) {
                                ?>
                                <div class="toneka-post-single-meta">
                                    <span class="toneka-post-date"><?php echo get_the_date(); ?></span>
                                    <?php
                                    $categories = get_the_category();
                                    if ( ! empty( $categories ) ) {
                                        echo ' / <span class="toneka-post-categories">';
                                        foreach ( $categories as $category ) {
                                            echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a> ';
                                        }
                                        echo '</span>';
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>
                        </header>
                        
                        <?php
                        // Featured image
                        if ( has_post_thumbnail() ) {
                            ?>
                            <div class="toneka-post-single-featured-image">
                                <?php the_post_thumbnail( 'full', array( 'class' => 'toneka-post-featured-img' ) ); ?>
                            </div>
                            <?php
                        }
                        ?>
                        
                        <div class="toneka-post-single-content">
                            <?php
                            the_content();
                            
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'toneka-theme' ),
                                'after'  => '</div>',
                            ) );
                            ?>
                        </div>
                        
                        <?php
                        // Tags
                        $tags = get_the_tags();
                        if ( $tags && ! is_wp_error( $tags ) ) {
                            ?>
                            <footer class="toneka-post-single-footer">
                                <div class="toneka-post-tags">
                                    <span class="toneka-tags-label">Tagi:</span>
                                    <?php
                                    foreach ( $tags as $tag ) {
                                        echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="toneka-tag-link">' . esc_html( $tag->name ) . '</a>';
                                    }
                                    ?>
                                </div>
                            </footer>
                            <?php
                        }
                        ?>
                        
                    </article>
                    
                    <?php
                    // Navigation to previous/next post
                    the_post_navigation( array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Poprzedni:', 'toneka-theme' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Następny:', 'toneka-theme' ) . '</span> <span class="nav-title">%title</span>',
                    ) );
                    
                endwhile; // End of the loop.
                ?>
            </div>
        </div>
    </div>
</main><!-- .site-main -->

<?php
get_footer();
