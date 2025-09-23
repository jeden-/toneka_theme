<?php get_header(); ?>

<main id="main" class="site-main">
    <div class="toneka-content-container">
        <?php
        if ( have_posts() ) {
            
            // Sprawdź czy to strona główna bloga
            if ( is_home() && ! is_front_page() ) {
                ?>
                <header class="toneka-archive-header">
                    <h1 class="toneka-archive-title">Blog</h1>
                </header>
                <?php
            }
            
            // Pętla wpisów
            while ( have_posts() ) {
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('toneka-post-content'); ?>>
                    
                    <header class="toneka-post-header">
                        <?php
                        if ( is_singular() ) {
                            the_title( '<h1 class="toneka-post-title">', '</h1>' );
                        } else {
                            the_title( '<h2 class="toneka-post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
                        }
                        
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
                        if ( is_singular() ) {
                            the_content();
                            
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'toneka-theme' ),
                                'after'  => '</div>',
                            ) );
                        } else {
                            the_excerpt();
                            ?>
                            <p><a href="<?php echo esc_url( get_permalink() ); ?>" class="toneka-read-more">Czytaj więcej</a></p>
                            <?php
                        }
                        ?>
                    </div>
                    
                </article>
                
                <?php
            }
            
            // Nawigacja stron
            the_posts_navigation( array(
                'prev_text' => '← Starsze wpisy',
                'next_text' => 'Nowsze wpisy →',
            ) );
            
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
