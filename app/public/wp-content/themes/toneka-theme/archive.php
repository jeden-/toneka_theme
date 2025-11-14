<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="toneka-content-container">
        <?php
        if ( have_posts() ) {
            
            // Nagłówek archiwum
            ?>
            <header class="toneka-archive-header">
                <?php
                if ( is_category() ) {
                    ?>
                    <h1 class="toneka-archive-title"><?php single_cat_title(); ?></h1>
                    <?php
                } elseif ( is_tag() ) {
                    ?>
                    <h1 class="toneka-archive-title"><?php single_tag_title(); ?></h1>
                    <?php
                } elseif ( is_author() ) {
                    ?>
                    <h1 class="toneka-archive-title"><?php echo get_the_author(); ?></h1>
                    <?php
                } elseif ( is_date() ) {
                    ?>
                    <h1 class="toneka-archive-title">
                        <?php
                        if ( is_year() ) {
                            echo get_the_date( 'Y' );
                        } elseif ( is_month() ) {
                            echo get_the_date( 'F Y' );
                        } elseif ( is_day() ) {
                            echo get_the_date();
                        }
                        ?>
                    </h1>
                    <?php
                } elseif ( is_home() && ! is_front_page() ) {
                    ?>
                    <h1 class="toneka-archive-title">Blog</h1>
                    <?php
                } else {
                    ?>
                    <h1 class="toneka-archive-title"><?php the_archive_title(); ?></h1>
                    <?php
                }
                ?>
            </header>
            
            <div class="toneka-archive-wrapper">
                <div class="toneka-archive-main">
                    <!-- Posts Grid -->
                    <div class="toneka-posts-grid">
                        <?php
                        while ( have_posts() ) {
                            the_post();
                            echo toneka_render_post_card( get_the_ID() );
                        }
                        ?>
                    </div>
                    
                    <?php
                    // Nawigacja stron
                    the_posts_navigation( array(
                        'prev_text' => '← Starsze wpisy',
                        'next_text' => 'Nowsze wpisy →',
                    ) );
                    ?>
                </div>
                
                <?php if ( is_active_sidebar( 'blog-archive-sidebar' ) ) : ?>
                    <aside class="toneka-post-sidebar">
                        <?php dynamic_sidebar( 'blog-archive-sidebar' ); ?>
                    </aside>
                <?php endif; ?>
            </div>
            
        } else {
            ?>
            <div class="toneka-no-content">
                <h1>Brak treści</h1>
                <p>Nie znaleziono żadnych wpisów do wyświetlenia.</p>
            </div>
            <?php
        }
        ?>
    </div>
</main><!-- .site-main -->

<?php get_footer(); ?>

