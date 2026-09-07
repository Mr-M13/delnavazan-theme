<?php
/**
 * Front page compatibility template.
 *
 * Existing block content is rendered unchanged during migration.
 *
 * @package DelnavazanTheme
 */

get_header();
?>
<main id="main-content" class="site-main site-main--front" tabindex="-1">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'entry entry--front' ); ?>>
			<div class="entry-content">
				<?php the_content(); ?>
			</div>
		</article>
	<?php endwhile; ?>
</main>
<?php
get_footer();
