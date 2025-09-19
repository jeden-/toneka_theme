<?php
/**
 * The template for displaying the footer
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="toneka-footer">
		<!-- Scrolling text section - first in footer -->
		<?php if ( get_theme_mod( 'toneka_scrolling_text_enabled', true ) ) : 
			$scrolling_text = get_theme_mod( 'toneka_scrolling_text', 'CZYM JEST MUZYKA ? NIE WIEM. MOŻE PO PROSTU NIEBEM. Z NUTAMI ZAMIAST GWIAZD.' );
		?>
		<div class="toneka-scrolling-text-section">
			<div class="toneka-scrolling-text-container">
				<div class="toneka-scrolling-text" data-speed="<?php echo esc_attr( get_theme_mod( 'toneka_scrolling_speed', 50 ) ); ?>">
					<span class="toneka-scrolling-text-content"><?php echo esc_html( $scrolling_text ); ?></span>
					<span class="toneka-scrolling-text-content"><?php echo esc_html( $scrolling_text ); ?></span>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="toneka-footer-main">
			<div class="toneka-footer-container">
				
				<!-- Pierwsza kolumna - hardcoded -->
				<div class="toneka-footer-column toneka-footer-left">
					<div class="toneka-footer-logo">
						<?php if ( has_custom_logo() ) : ?>
							<?php the_custom_logo(); ?>
						<?php else : ?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<svg width="140" height="18" viewBox="0 0 140 18">
									<text x="0" y="14" font-family="Arial" font-size="14">TONEKA</text>
								</svg>
							</a>
						<?php endif; ?>
					</div>
					
					<div class="toneka-footer-content">
						<h3 class="toneka-footer-title">Chcesz z nami się<br>czymś podzielić</h3>
						
						<a href="mailto:kontakt@toneka.pl" class="toneka-footer-button animated-arrow-button">
							<span class="button-text">NAPISZ</span>
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

				<!-- Druga kolumna - widget area -->
				<div class="toneka-footer-column toneka-footer-center">
					<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-widget-2' ); ?>
					<?php endif; ?>
				</div>

				<!-- Trzecia kolumna - widget area + dane kontaktowe -->
				<div class="toneka-footer-column toneka-footer-right">
					<div class="toneka-footer-contact">
						<p>Toneka, Kraków, Szewska 21</p>
						<p>555 555 555</p>
					</div>
					
					<?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-widget-3' ); ?>
					<?php endif; ?>
				</div>

			</div>
		</div>

		<!-- Back to top -->
		<div class="toneka-footer-bottom">
			<div class="toneka-back-to-top">
				<button class="toneka-back-to-top-btn animated-arrow-button" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
					<span class="button-text">BACK TO TOP</span>
					<div class="button-arrow">
						<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 10.8364L11 0.969819" stroke="white" stroke-linecap="round"/>
							<path d="M11 9.67383L11 0.836618" stroke="white" stroke-linecap="round"/>
							<path d="M11 0.836426L2.04334 0.836427" stroke="white" stroke-linecap="round"/>
						</svg>
					</div>
				</button>
			</div>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>