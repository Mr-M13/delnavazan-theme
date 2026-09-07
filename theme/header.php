<?php
/**
 * Site header.
 *
 * @package DelnavazanTheme
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'رفتن به محتوای اصلی', 'delnavazan-theme' ); ?></a>
<header class="site-header" role="banner">
	<div class="site-header__accent" aria-hidden="true"></div>
	<div class="dzn-container site-header__inner">
		<div class="site-branding">
			<div class="site-branding__identity">
				<?php if ( has_custom_logo() ) : ?>
					<?php
					$custom_logo = get_custom_logo();
					if ( false === strpos( $custom_logo, 'aria-label=' ) ) {
						$custom_logo = str_replace(
							'<a ',
							'<a aria-label="' . esc_attr__( 'خانهٔ دلنوازان', 'delnavazan-theme' ) . '" ',
							$custom_logo
						);
					}
					echo $custom_logo; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Core-generated logo HTML with one escaped attribute.
					?>
				<?php else : ?>
					<a class="site-branding__name" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
				<?php endif; ?>
			</div>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-branding__description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>
		<button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" hidden>
			<span class="menu-toggle__label"><?php esc_html_e( 'فهرست اصلی', 'delnavazan-theme' ); ?></span>
			<span class="menu-toggle__icon" aria-hidden="true"></span>
		</button>
		<nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'فهرست اصلی', 'delnavazan-theme' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'primary-navigation__list',
				'fallback_cb'    => false,
			) );
			?>
		</nav>
	</div>
</header>
