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

<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'tonekatheme' ); ?></a>

	<header id="masthead" class="site-header">
		<!-- Header dla wszystkich stron, na stronie produktu jest przeźroczysty -->
		<div class="toneka-main-header <?php echo is_product() ? 'product-page-header' : ''; ?>">
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
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<span class="hamburger-line"></span>
							<span class="hamburger-line"></span>
							<span class="hamburger-line"></span>
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
								<svg width="16" height="16" viewBox="0 0 14 14" fill="none">
									<circle cx="7" cy="7" r="7" fill="none"/>
									<circle cx="7" cy="5" r="2" fill="none"/>
									<path d="M2 11c0-2.5 2.5-4 5-4s5 1.5 5 4" fill="none"/>
								</svg>
							</a>
						</div>

						<div class="toneka-cart-icon">
							<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="toneka-cart-link">
								<svg width="16" height="16" viewBox="0 0 14 14" fill="none">
									<path d="M1 1h2.5l1.5 8h7l2-6H4" fill="none"/>
									<circle cx="7" cy="12" r="1" fill="none"/>
									<circle cx="11" cy="12" r="1" fill="none"/>
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

	<div id="content" class="site-content">
