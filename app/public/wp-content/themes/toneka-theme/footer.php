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
					<span class="toneka-scrolling-text-content"><?php echo esc_html( $scrolling_text ); ?></span>
					<span class="toneka-scrolling-text-content"><?php echo esc_html( $scrolling_text ); ?></span>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="toneka-footer-main">
			<div class="toneka-footer-container">
				
				<!-- Pierwsza kolumna - widget area -->
				<div class="toneka-footer-column toneka-footer-left">
					<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
						<?php dynamic_sidebar( 'footer-widget-2' ); ?>
					<?php endif; ?>
				</div>

				<!-- Druga kolumna - widget area -->
				<div class="toneka-footer-column toneka-footer-right">
					<?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
						<?php dynamic_sidebar( 'footer-widget-3' ); ?>
					<?php endif; ?>
				</div>

			</div>
		</div>

		<!-- Footer bottom with social media and copyright -->
		<div class="toneka-footer-bottom">
			<div class="toneka-footer-bottom-content">
				<!-- Social media links -->
				<div class="toneka-social-links">
					<?php 
					$instagram_url = get_theme_mod('toneka_instagram_url', 'https://instagram.com/toneka');
					$instagram_text = get_theme_mod('toneka_instagram_text', 'INSTAGRAM');
					$facebook_url = get_theme_mod('toneka_facebook_url', 'https://facebook.com/toneka');
					$facebook_text = get_theme_mod('toneka_facebook_text', 'FACEBOOK');
					$youtube_url = get_theme_mod('toneka_youtube_url', 'https://youtube.com/toneka');
					$youtube_text = get_theme_mod('toneka_youtube_text', 'YOUTUBE');
					?>
					
					<?php if ($instagram_url && $instagram_url !== '#') : ?>
					<a href="<?php echo esc_url($instagram_url); ?>" target="_blank" rel="noopener" class="toneka-social-link">
						<span class="social-text"><?php echo esc_html($instagram_text); ?></span>
						<div class="social-arrow">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1 9L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 8L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 1L2 1" stroke="white" stroke-linecap="round"/>
							</svg>
						</div>
					</a>
					<?php endif; ?>
					
					<?php if ($facebook_url && $facebook_url !== '#') : ?>
					<a href="<?php echo esc_url($facebook_url); ?>" target="_blank" rel="noopener" class="toneka-social-link">
						<span class="social-text"><?php echo esc_html($facebook_text); ?></span>
						<div class="social-arrow">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1 9L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 8L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 1L2 1" stroke="white" stroke-linecap="round"/>
							</svg>
						</div>
					</a>
					<?php endif; ?>
					
					<?php if ($youtube_url && $youtube_url !== '#') : ?>
					<a href="<?php echo esc_url($youtube_url); ?>" target="_blank" rel="noopener" class="toneka-social-link">
						<span class="social-text"><?php echo esc_html($youtube_text); ?></span>
						<div class="social-arrow">
							<svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M1 9L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 8L9 1" stroke="white" stroke-linecap="round"/>
								<path d="M9 1L2 1" stroke="white" stroke-linecap="round"/>
							</svg>
						</div>
					</a>
					<?php endif; ?>
				</div>
				
				<!-- Copyright -->
				<div class="toneka-copyright">
					<span>Copyright © 2024-<?php echo date('Y'); ?> Toneka</span>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>