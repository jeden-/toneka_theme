<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="toneka-content-container">
        <?php
        if ( have_posts() ) {
            
            // Sprawdź czy to strona główna bloga lub archiwum
            if ( is_home() && ! is_front_page() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title">Blog</h1>
                </header>
                <?php
            } elseif ( is_category() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title"><?php single_cat_title(); ?></h1>
                </header>
                <?php
            } elseif ( is_tag() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title"><?php single_tag_title(); ?></h1>
                </header>
                <?php
            } elseif ( is_author() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title"><?php echo get_the_author(); ?></h1>
                </header>
                <?php
            } elseif ( is_date() ) {
                ?>
                <header class="toneka-archive-header">
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
                </header>
                <?php
            } elseif ( is_archive() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title"><?php the_archive_title(); ?></h1>
                </header>
                <?php
            }
            
            // Sprawdź czy to pojedynczy post (singular) czy lista
            if ( is_singular() ) {
                // Dla pojedynczych postów - stary layout
                while ( have_posts() ) {
                    the_post();
                    ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('toneka-post-content'); ?>>
                        
                        <header class="toneka-post-header">
                            <?php
                            the_title( '<h1 class="toneka-post-title">', '</h1>' );
                            
                            if ( 'post' === get_post_type() ) {
                                ?>
                                <div class="toneka-post-meta">
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
                        
                        <div class="toneka-post-content-wrapper">
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
                }
            } else {
                // Dla listy postów - nowy layout z siatką i sidebarem
                ?>
                <div class="toneka-archive-wrapper">
                    <?php if ( is_active_sidebar( 'blog-archive-sidebar' ) ) : ?>
                        <aside class="toneka-post-sidebar">
                            <?php dynamic_sidebar( 'blog-archive-sidebar' ); ?>
                        </aside>
                    <?php endif; ?>
                    
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
                </div>
                <?php
            }
            
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
