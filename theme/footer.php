<?php
/**
 * Site footer.
 *
 * @package DelnavazanTheme
 */
?>
<footer class="site-footer" role="contentinfo">
	<div class="dzn-container site-footer__top">
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
			<p class="site-footer__description"><?php esc_html_e( 'برای آغاز مسیر آموزش یا پرسش دربارهٔ ثبت‌نام، از راه‌های رسمی دلنوازان با ما در ارتباط باشید.', 'delnavazan-theme' ); ?></p>
		</div>
		<div class="site-footer__actions" aria-label="<?php esc_attr_e( 'راه‌های تماس با دلنوازان', 'delnavazan-theme' ); ?>">
			<a class="site-footer__action site-footer__action--phone" href="tel:+61413413004" aria-label="<?php esc_attr_e( 'تماس تلفنی با دلنوازان: 0413 413 004', 'delnavazan-theme' ); ?>">
				<span class="site-footer__contact-mark" aria-hidden="true">☎</span>
				<span><small><?php esc_html_e( 'تلفن', 'delnavazan-theme' ); ?></small><bdi dir="ltr">0413 413 004</bdi></span>
			</a>
			<a class="site-footer__action" href="mailto:delnavazan@mail.com" aria-label="<?php esc_attr_e( 'ارسال ایمیل به دلنوازان', 'delnavazan-theme' ); ?>">
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/contact-email.webp' ) ); ?>" alt="" width="249" height="300" loading="lazy" decoding="async">
				<span><small><?php esc_html_e( 'ایمیل', 'delnavazan-theme' ); ?></small><bdi dir="ltr">delnavazan@mail.com</bdi></span>
			</a>
			<a class="site-footer__action" href="https://www.instagram.com/insta.delnavazan/" aria-label="<?php esc_attr_e( 'اینستاگرام دلنوازان', 'delnavazan-theme' ); ?>">
				<img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/contact-instagram.webp' ) ); ?>" alt="" width="242" height="303" loading="lazy" decoding="async">
				<span><small><?php esc_html_e( 'اینستاگرام', 'delnavazan-theme' ); ?></small><bdi dir="ltr">@insta.delnavazan</bdi></span>
			</a>
		</div>
	</div>
	<div class="dzn-container site-footer__bottom">
		<nav class="site-footer__navigation" aria-label="<?php esc_attr_e( 'دسترسی سریع پایین صفحه', 'delnavazan-theme' ); ?>">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => false,
				'menu_class'     => 'site-footer__menu',
				'fallback_cb'    => false,
			) );
			?>
		</nav>
		<div class="site-footer__legal">
			<p><?php echo esc_html( sprintf( __( '© %1$s %2$s. همهٔ حقوق محفوظ است.', 'delnavazan-theme' ), wp_date( 'Y' ), get_bloginfo( 'name' ) ) ); ?></p>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
