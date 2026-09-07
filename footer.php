<?php
/**
 * Site footer.
 *
 * @package DelnavazanTheme
 */
?>
<footer class="site-footer" role="contentinfo">
	<div class="dzn-container site-footer__grid">
		<div>
			<p class="site-footer__name"><?php bloginfo( 'name' ); ?></p>
			<?php if ( get_bloginfo( 'description' ) ) : ?>
				<p class="site-footer__description"><?php bloginfo( 'description' ); ?></p>
			<?php endif; ?>
		</div>
		<nav aria-label="<?php esc_attr_e( 'فهرست پایین صفحه', 'delnavazan-theme' ); ?>">
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
</footer>
<?php wp_footer(); ?>
</body>
</html>
