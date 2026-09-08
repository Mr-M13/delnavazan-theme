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
			<p class="site-footer__eyebrow"><?php esc_html_e( 'آکادمی آنلاین موسیقی ایرانی', 'delnavazan-theme' ); ?></p>
			<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-footer__description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
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
