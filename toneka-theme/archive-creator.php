<?php
/**
 * The template for displaying archive pages for the 'creator' post type.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toneka_Theme
 */

get_header();
?>

<main id="main" class="site-main">

	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title"><?php post_type_archive_title(); ?></h1>
		</header><!-- .page-header -->

		<div class="creators-grid">
			<?php
			// Start the Loop.
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<header class="entry-header">
						<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
					</header><!-- .entry-header -->
				</article><!-- #post-<?php the_ID(); ?> -->
				<?php
			endwhile;
			?>
		</div><!-- .creators-grid -->

		<?php
		// Previous/next page navigation.
		the_posts_pagination(
			array(
				'prev_text'          => __( 'Previous page', 'tonekatheme' ),
				'next_text'          => __( 'Next page', 'tonekatheme' ),
				'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'tonekatheme' ) . ' </span>',
			)
		);

	else :
		// If no content, include the "No posts found" template.
		echo '<p>' . esc_html__( 'No creators found.', 'tonekatheme' ) . '</p>';

	endif;
	?>

</main><!-- #main -->

<?php
get_footer();
