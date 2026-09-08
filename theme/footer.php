<?php
/**
 * Site footer.
 *
 * @package DelnavazanTheme
 */
?>
<footer class="site-footer" role="contentinfo">
	<div class="dzn-container site-footer__grid">
		<div class="site-footer__identity">
			<?php if ( has_custom_logo() ) : ?>
				<div class="site-footer__logo-frame">
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
				</div>
			<?php else : ?>
				<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
			<?php endif; ?>
			<p class="site-footer__eyebrow"><?php esc_html_e( 'آکادمی آنلاین موسیقی ایرانی', 'delnavazan-theme' ); ?></p>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-footer__description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
			<p class="site-footer__contact">
				<a href="tel:+61413413004"><bdi dir="ltr">0413 413 004</bdi></a>
				<span aria-hidden="true"> · </span>
				<a href="mailto:delnavazan@mail.com"><bdi dir="ltr">delnavazan@mail.com</bdi></a>
				<span aria-hidden="true"> · </span>
				<a href="https://www.instagram.com/insta.delnavazan/"><bdi dir="ltr">@insta.delnavazan</bdi></a>
			</p>
		</div>
		<nav class="site-footer__navigation" aria-label="<?php esc_attr_e( 'فهرست پایین صفحه', 'delnavazan-theme' ); ?>">
			<p class="site-footer__heading"><?php esc_html_e( 'دسترسی سریع', 'delnavazan-theme' ); ?></p>
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__menu',
				'fallback_cb'    => false,
			) );
			?>
		</nav>
	</div>
	<div class="dzn-container site-footer__legal">
		<p><?php echo esc_html( sprintf( __( '© %1$s %2$s. همهٔ حقوق محفوظ است.', 'delnavazan-theme' ), wp_date( 'Y' ), get_bloginfo( 'name' ) ) ); ?></p>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
