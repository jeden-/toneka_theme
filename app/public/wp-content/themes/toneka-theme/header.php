<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Mini-cart overlay -->
<div class="toneka-minicart-overlay"></div>

<!-- Mini-cart -->
<div class="toneka-minicart">
    <?php if (function_exists('toneka_display_custom_minicart')) {
        toneka_display_custom_minicart();
    } ?>
</div>

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'tonekatheme' ); ?></a>

	<header id="masthead" class="site-header">
		<!-- Header dla wszystkich stron, na stronie produktu jest przeźroczysty -->
		<div class="toneka-main-header <?php echo (is_product() || is_product_category() || is_shop() || is_product_tag() || is_singular('creator') || is_post_type_archive('creator') || is_front_page()) ? 'product-page-header' : ''; ?>">
			<div class="toneka-header-container">
				<div class="toneka-header-left">
					<div class="site-branding">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
						} else {
							?>
							<div class="toneka-default-logo">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
									<svg width="140" height="18" viewBox="0 0 140 18">
										<text x="0" y="14" font-family="Arial" font-size="14">TONEKA</text>
									</svg>
								</a>
							</div>
							<?php
						}
						?>
					</div>
				</div>

				<div class="toneka-header-right">
					<nav id="site-navigation" class="main-navigation">
						<button class="menu-toggle mobile-hamburger" aria-controls="primary-menu" aria-expanded="false">
							<?php include get_template_directory() . '/img/mobile1.svg'; ?>
							<?php include get_template_directory() . '/img/mobile_hover.svg'; ?>
							<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'tonekatheme' ); ?></span>
						</button>

						<?php
						if ( has_nav_menu( 'primary' ) ) {
							wp_nav_menu(
								array(
									'theme_location' => 'primary',
									'menu_id'        => 'primary-menu',
									'menu_class'     => 'toneka-primary-menu',
									'container'      => false,
									'fallback_cb'    => false,
								)
							);
						} else {
							// Fallback menu jeśli nie ma ustawionego menu
							?>
							<ul class="toneka-primary-menu">
								<li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a></li>
								<li class="menu-item"><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">SŁUCHOWISKA</a></li>
								<li class="menu-item"><a href="#">DESIGN</a></li>
								<li class="menu-item"><a href="#">MUZYKA</a></li>
								<li class="menu-item"><a href="#">KONTAKT</a></li>
								<?php if ( is_user_logged_in() ) : ?>
									<li class="menu-item"><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">WYLOGUJ SIĘ</a></li>
								<?php else : ?>
									<li class="menu-item"><a href="<?php echo esc_url( wp_login_url() ); ?>">ZALOGUJ SIĘ</a></li>
								<?php endif; ?>
							</ul>
							<?php
						}
						?>
					</nav>

					<?php if ( class_exists( 'WooCommerce' ) ) : ?>
					<div class="toneka-header-actions">
						<div class="toneka-user-account">
							<a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="toneka-account-link">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
									<circle cx="8" cy="8" r="7" fill="none"/>
									<circle cx="8" cy="6" r="2" fill="none"/>
									<path d="M3 12c0-2.5 2.5-4 5-4s5 1.5 5 4" fill="none"/>
								</svg>
							</a>
						</div>

						<div class="toneka-cart-icon">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="toneka-cart-link">
								<svg width="16" height="16" viewBox="0 0 16 16" fill="none">
									<path d="M2 2h2.5l1.5 8h7l2-6H5" fill="none"/>
									<circle cx="8" cy="13" r="1" fill="none"/>
									<circle cx="12" cy="13" r="1" fill="none"/>
								</svg>
								<span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
							</a>
						</div>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</header><!-- #masthead -->

	<!-- Mobile Menu Overlay -->
	<div class="mobile-menu-overlay" id="mobile-menu-overlay">
		<div class="mobile-menu-header">
			<div class="site-branding">
				<?php
				if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
					the_custom_logo();
				} else {
					?>
					<div class="toneka-default-logo">
						<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
							<svg width="140" height="18" viewBox="0 0 140 18">
								<text x="0" y="14" font-family="Arial" font-size="14">TONEKA</text>
							</svg>
						</a>
					</div>
					<?php
				}
				?>
			</div>
			<button class="menu-close mobile-close" aria-label="Zamknij menu">
				<?php include get_template_directory() . '/img/close1.svg'; ?>
				<?php include get_template_directory() . '/img/close_hover.svg'; ?>
			</button>
		</div>
		
		<nav class="mobile-menu-nav">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'menu_id'        => 'mobile-primary-menu',
						'menu_class'     => 'mobile-primary-menu',
						'container'      => false,
						'fallback_cb'    => false,
					)
				);
			} else {
				?>
				<ul class="mobile-primary-menu">
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOME</a></li>
					<li class="menu-item"><a href="<?php echo esc_url( home_url( '/shop/' ) ); ?>">SŁUCHOWISKA</a></li>
					<li class="menu-item"><a href="#">DESIGN</a></li>
					<li class="menu-item"><a href="#">MUZYKA</a></li>
					<li class="menu-item"><a href="#">KONTAKT</a></li>
					<?php if ( is_user_logged_in() ) : ?>
						<li class="menu-item"><a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>">WYLOGUJ SIĘ</a></li>
					<?php else : ?>
						<li class="menu-item"><a href="<?php echo esc_url( wp_login_url() ); ?>">ZALOGUJ SIĘ</a></li>
					<?php endif; ?>
				</ul>
				<?php
			}
			?>
		</nav>
		
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<div class="mobile-menu-cart">
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="mobile-cart-link">
				<?php include get_template_directory() . '/img/shop_bag.svg'; ?>
				<span class="mobile-cart-text">KOSZYK</span>
				(<?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>)
			</a>
		</div>
		
		<div class="mobile-menu-social">
			<a href="#" class="mobile-social-link">INSTAGRAM</a>
			<a href="#" class="mobile-social-link">FACEBOOK</a>
			<a href="#" class="mobile-social-link">YOUTUBE</a>
		</div>
		<?php endif; ?>
	</div>

	<div id="content" class="site-content">
