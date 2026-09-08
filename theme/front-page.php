<?php
/**
 * Front page template.
 *
 * The homepage composition remains Gutenberg-managed. The theme supplies the
 * editorial homepage pattern and presentation system, while this template
 * deliberately preserves the authored block tree and stable page identity.
 *
 * @package DelnavazanTheme
 */

get_header();
?>
<main id="main-content" class="site-main site-main--front" tabindex="-1">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--front dzn-home' ); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
