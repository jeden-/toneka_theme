<?php
/**
 * The template for displaying a single 'creator' post type.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Toneka_Theme
 */

get_header();
?>

<main id="main" class="site-main">

	<?php
	// Start the Loop.
	while ( have_posts() ) :
		the_post();
		?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="entry-header">
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<div class="entry-content">
				<?php
				the_content();
				?>
			</div><!-- .entry-content -->

			<div class="creator-portfolio">
				<h2><?php esc_html_e( 'Portfolio', 'tonekatheme' ); ?></h2>
				
				<?php
				$creator_name = get_the_title();

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
					echo '<ul>';
					while ($related_products->have_posts()) : $related_products->the_post();
						?>
						<li>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</li>
						<?php
					endwhile;
					echo '</ul>';
					wp_reset_postdata();
				else :
					echo '<p>' . esc_html__('No related products found.', 'tonekatheme') . '</p>';
				endif;
				?>

			</div>

		</article><!-- #post-<?php the_ID(); ?> -->

		<?php
	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer();
