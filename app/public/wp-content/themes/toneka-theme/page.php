<?php
/**
 * Template for displaying pages
 */

get_header(); ?>

<main id="main" class="site-main">
    <div class="toneka-page-container">
        <?php
        while ( have_posts() ) :
            the_post();
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class('toneka-page-content'); ?>>
                
                <header class="toneka-page-header">
                    <h1 class="toneka-page-title"><?php the_title(); ?></h1>
                </header>
                
                <div class="toneka-page-content-wrapper">
                    <?php
                    the_content();

                    wp_link_pages( array(
                        'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'toneka-theme' ),
                        'after'  => '</div>',
                    ) );
                    ?>
                </div>
                
            </article>
            
            <?php
        endwhile;
        ?>
    </div>
</main>

<?php get_footer(); ?>

