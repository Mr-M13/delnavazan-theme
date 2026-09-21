<?php
/**
 * Reusable Persian RTL document presentation: Article, Policy and General/Help pages.
 *
 * The system is presentation-only. It derives deterministic heading anchors, a table of contents,
 * reading time and publication metadata from authored content and existing WordPress data. It stores
 * nothing, queries no domain service and performs no protected reads.
 *
 * @package DelnavazanTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The content presentation modes.
 *
 * @return string[]
 */
function dzn_theme_content_page_modes() {
	return array( 'article', 'policy', 'general' );
}

/**
 * The reusable presentation contract for one mode.
 *
 * @param string $mode Presentation mode.
 * @return array
 */
function dzn_theme_content_page_presentation( $mode ) {
	$presentations = array(
		'article' => array(
			'mode'                => 'article',
			'document_class'      => 'dzn-document--article',
			'label'               => 'مقاله',
			'show_categories'     => true,
			'show_publication'    => true,
			'show_reading_time'   => true,
			'show_featured_image' => true,
			'show_related'        => true,
			'show_previous_next'  => true,
			'show_print_hint'     => false,
		),
		'policy'  => array(
			'mode'                => 'policy',
			'document_class'      => 'dzn-document--policy',
			'label'               => 'سند سیاست',
			'show_categories'     => false,
			'show_publication'    => true,
			'show_reading_time'   => false,
			'show_featured_image' => false,
			'show_related'        => false,
			'show_previous_next'  => false,
			'show_print_hint'     => true,
		),
		'general' => array(
			'mode'                => 'general',
			'document_class'      => 'dzn-document--general',
			'label'               => 'راهنما',
			'show_categories'     => false,
			'show_publication'    => false,
			'show_reading_time'   => false,
			'show_featured_image' => false,
			'show_related'        => false,
			'show_previous_next'  => false,
			'show_print_hint'     => false,
		),
	);

	$mode = in_array( $mode, dzn_theme_content_page_modes(), true ) ? $mode : 'general';

	return $presentations[ $mode ];
}

/**
 * Headings that participate in the document outline.
 *
 * @return int[]
 */
function dzn_theme_content_page_heading_levels() {
	return array( 2, 3 );
}

/**
 * How many outline sections justify a table of contents.
 *
 * @return int
 */
function dzn_theme_content_page_toc_minimum() {
	return 3;
}

/**
 * Reading speed used for the presentation-only reading time, in words per minute.
 *
 * @return int
 */
function dzn_theme_content_page_words_per_minute() {
	return 200;
}

/**
 * Persian-aware, deterministic anchor base for one heading.
 *
 * Accents and punctuation collapse to single dashes, the zero-width non-joiner is dropped so the
 * anchor stays URL-stable, and non-letter/non-number scripts (including Persian) are preserved.
 *
 * @param string $text Heading text.
 * @return string
 */
function dzn_theme_content_page_slug( $text ) {
	$text = html_entity_decode( wp_strip_all_tags( (string) $text ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$text = str_replace( array( "\xE2\x80\x8C", "\xE2\x80\x8D" ), '', $text );
	$text = preg_replace( '/[^\p{L}\p{N}]+/u', '-', $text );
	if ( ! is_string( $text ) ) {
		return '';
	}
	$text = trim( $text, '-' );
	$text = function_exists( 'mb_strtolower' ) ? mb_strtolower( $text, 'UTF-8' ) : strtolower( $text );

	return $text;
}

/**
 * Resolve one anchor id deterministically against the ids already used in this document.
 *
 * Authored ids are preserved verbatim; a repeated id (authored or derived) receives the next free
 * numeric suffix in document order, so the result never depends on hash order or on the request.
 *
 * @param string $candidate Preferred anchor.
 * @param array  $used      Already assigned anchors, keyed by anchor.
 * @param int    $position  One-based heading position, used for the empty fallback.
 * @return string
 */
function dzn_theme_content_page_unique_anchor( $candidate, array &$used, $position ) {
	$candidate = trim( (string) $candidate );

	if ( '' === $candidate ) {
		$candidate = 'section-' . (int) $position;
	}

	$anchor = $candidate;
	$suffix = 2;

	while ( isset( $used[ $anchor ] ) ) {
		$anchor = $candidate . '-' . $suffix;
		$suffix++;
	}

	$used[ $anchor ] = true;

	return $anchor;
}

/**
 * Insert or replace the id attribute of one heading tag.
 *
 * @param string $tag_name   Lower-case heading tag name.
 * @param string $attributes Existing attribute string without the tag name.
 * @param string $anchor     Resolved anchor id.
 * @return string
 */
function dzn_theme_content_page_heading_attributes( $tag_name, $attributes, $anchor ) {
	$escaped = esc_attr( $anchor );
	$pattern = '/\bid\s*=\s*(["\']).*?\1/i';

	if ( preg_match( $pattern, (string) $attributes ) ) {
		return preg_replace( $pattern, 'id="' . $escaped . '"', (string) $attributes, 1 );
	}

	return ' id="' . $escaped . '"' . (string) $attributes;
}

/**
 * Anchor every outline heading in rendered content and describe the document outline.
 *
 * The function is pure and idempotent: anchoring already-anchored content keeps the same ids and
 * produces the same outline, so a re-run can never drift from the table of contents.
 *
 * @param string $content Rendered post content.
 * @return array{content:string,sections:array<int,array<string,mixed>>}
 */
function dzn_theme_content_page_anchor_content( $content ) {
	$content  = (string) $content;
	$levels   = dzn_theme_content_page_heading_levels();
	$sections = array();
	$used     = array();
	$position = 0;
	$pattern  = '#<(h[1-6])\b([^>]*)>(.*?)</\1>#is';

	$anchored = preg_replace_callback(
		$pattern,
		static function ( $matches ) use ( &$sections, &$used, &$position, $levels ) {
			$tag_name = strtolower( $matches[1] );
			$level    = (int) substr( $tag_name, 1 );

			if ( ! in_array( $level, $levels, true ) ) {
				return $matches[0];
			}

			$position++;
			$attributes = (string) $matches[2];
			$inner      = (string) $matches[3];
			$text       = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( $inner ) ) );
			$authored   = '';

			if ( preg_match( '/\bid\s*=\s*(["\'])(.*?)\1/i', $attributes, $id_match ) ) {
				$authored = trim( (string) $id_match[2] );
			}

			$candidate = '' !== $authored ? $authored : dzn_theme_content_page_slug( $text );
			$anchor    = dzn_theme_content_page_unique_anchor( $candidate, $used, $position );

			$sections[] = array(
				'level'    => $level,
				'anchor'   => $anchor,
				'text'     => $text,
				'authored' => '' !== $authored,
			);

			return '<' . $tag_name . dzn_theme_content_page_heading_attributes( $tag_name, $attributes, $anchor ) . '>' . $inner . '</' . $tag_name . '>';
		},
		$content
	);

	if ( ! is_string( $anchored ) ) {
		$anchored = $content;
		$sections = array();
	}

	return array(
		'content'  => $anchored,
		'sections' => $sections,
	);
}

/**
 * Presentation-only reading time in whole minutes (never a domain fact).
 *
 * @param string $content Rendered or authored content.
 * @return int Zero for content without readable text, otherwise whole minutes, at least one.
 */
function dzn_theme_content_page_reading_minutes( $content ) {
	$text = trim( preg_replace( '/\s+/u', ' ', wp_strip_all_tags( (string) $content ) ) );

	if ( '' === $text ) {
		return 0;
	}

	$words = preg_split( '/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY );
	$count = is_array( $words ) ? count( $words ) : 0;

	if ( $count < 1 ) {
		return 0;
	}

	return max( 1, (int) ceil( $count / dzn_theme_content_page_words_per_minute() ) );
}

/**
 * Resolve the presentation mode for a post.
 *
 * Posts are always Articles. Pages are General/Help unless they explicitly select the Policy page
 * template, which keeps authoring inside the standard WordPress page-template mechanism.
 *
 * @param int|WP_Post|null $post Post object or ID. Defaults to the current post.
 * @return string
 */
function dzn_theme_content_page_mode( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return 'general';
	}

	if ( 'post' === $post->post_type ) {
		return 'article';
	}

	$template = get_post_meta( (int) $post->ID, '_wp_page_template', true );

	if ( is_string( $template ) && 'page-templates/content-policy.php' === $template ) {
		return 'policy';
	}

	return 'general';
}

/**
 * Anchor one rendered content string once per post and remember its document outline.
 *
 * @param WP_Post|null $post    Post the content belongs to.
 * @param string       $content Rendered content.
 * @return array{content:string,sections:array<int,array<string,mixed>>,minutes:int}
 */
function dzn_theme_content_page_document( $post, $content ) {
	static $documents = array();

	$key = ( $post ? (int) $post->ID : 0 ) . ':' . md5( (string) $content );

	if ( ! isset( $documents[ $key ] ) ) {
		$anchored = dzn_theme_content_page_anchor_content( (string) $content );

		$documents[ $key ] = array(
			'content'  => $anchored['content'],
			'sections' => $anchored['sections'],
			'minutes'  => dzn_theme_content_page_reading_minutes( $content ),
		);
	}

	return $documents[ $key ];
}

/**
 * Anchor the rendered body exactly as the table of contents describes it.
 *
 * Idempotent and deterministic: the anchors a reader follows are the anchors the outline lists, and
 * re-rendering the same content produces the same ids. Paginated posts are left to core so page
 * splitting keeps working unchanged.
 *
 * @param string $content Rendered content.
 * @return string
 */
function dzn_theme_content_page_filter_content( $content ) {
	$post = get_post();

	if ( ! $post || false !== strpos( (string) $post->post_content, '<!--nextpage-->' ) ) {
		return $content;
	}

	return dzn_theme_content_page_document( $post, $content )['content'];
}
add_filter( 'the_content', 'dzn_theme_content_page_filter_content', 20 );

/**
 * The document contract for one post: anchored content, outline, reading time and table of contents.
 *
 * @param int|WP_Post|null $post Post object or ID. Defaults to the current post.
 * @return array
 */
function dzn_theme_content_page_data( $post = null ) {
	$post = get_post( $post );

	if ( ! $post ) {
		return array(
			'content'      => '',
			'sections'     => array(),
			'minutes'      => 0,
			'paginated'    => false,
			'requires_toc' => false,
		);
	}

	if ( false !== strpos( (string) $post->post_content, '<!--nextpage-->' ) ) {
		return array(
			'content'      => '',
			'sections'     => array(),
			'minutes'      => dzn_theme_content_page_reading_minutes( $post->post_content ),
			'paginated'    => true,
			'requires_toc' => false,
		);
	}

	// The canonical pipeline anchors the body through this module's own filter, so the outline below
	// is always the outline that was rendered.
	$rendered  = apply_filters( 'the_content', $post->post_content );
	$document  = dzn_theme_content_page_document( $post, $rendered );
	$sections  = $document['sections'];

	return array(
		'content'      => $document['content'],
		'sections'     => $sections,
		'minutes'      => $document['minutes'],
		'paginated'    => false,
		'requires_toc' => count( $sections ) >= dzn_theme_content_page_toc_minimum(),
	);
}
