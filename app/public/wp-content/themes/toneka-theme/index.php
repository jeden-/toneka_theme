<?php get_header(); ?>

<main id="main" class="site-main">

	<?php
	if ( have_posts() ) {

		// Load posts loop.
		while ( have_posts() ) {
			the_post();
			
			// a. Wyświetl tytuł wpisu.
			the_title( '<h1>', '</h1>' );

			// b. Wyświetl treść wpisu.
			the_content();
		}
	} else {
		// c. Jeśli nie ma wpisów, wyświetl komunikat.
		echo '<p>Brak wpisów do wyświetlenia.</p>';
	}
	?>

</main><!-- .site-main -->

<?php get_footer(); ?>
