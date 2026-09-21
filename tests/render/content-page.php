<?php
/**
 * Dependency-free behavioral checks for the Single Content Page V1 document system.
 *
 * The suite exercises the pure anchor/outline/reading-time contract and the rendered table of
 * contents in both variants. It boots no WordPress and performs no writes.
 */

define( 'ABSPATH', __DIR__ );
define( 'DZN_TEST_THEME', dirname( __DIR__, 2 ) . '/theme/' );

function esc_html( $value ) { return htmlspecialchars( (string) $value, ENT_QUOTES, 'UTF-8' ); }
function esc_attr( $value ) { return esc_html( $value ); }
function esc_html_e( $value ) { echo esc_html( $value ); }
function esc_attr_e( $value ) { echo esc_attr( $value ); }
function add_filter() {}
function wp_strip_all_tags( $value, $remove_breaks = false ) {
	$value = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', (string) $value );
	$value = strip_tags( (string) $value );
	return $remove_breaks ? trim( preg_replace( '/[\r\n\t ]+/', ' ', $value ) ) : trim( $value );
}
function number_format_i18n( $number ) { return (string) $number; }
function __( $text ) { return $text; }

require DZN_TEST_THEME . 'inc/content-page.php';

function dzn_test_assert( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
}

// Deterministic, Persian-aware anchors that preserve authored ids.
$content = '<h1>عنوان اصلی</h1><p>متن</p><h2 id="khodem">قواعد صنفی</h2><p>متن</p><h3>قواعد صنفی</h3><h2></h2><h4>سرفصل فرعی</h4>';
$result  = dzn_theme_content_page_anchor_content( $content );

dzn_test_assert( 3 === count( $result['sections'] ), 'Only H2/H3 headings may join the document outline.' );
dzn_test_assert( 'khodem' === $result['sections'][0]['anchor'], 'An authored heading id must be preserved verbatim.' );
dzn_test_assert( true === $result['sections'][0]['authored'], 'An authored heading id must be reported as authored.' );
dzn_test_assert( 'قواعد-صنفی' === $result['sections'][1]['anchor'], 'A Persian heading must receive a stable Persian anchor: ' . $result['sections'][1]['anchor'] );
dzn_test_assert( 'قواعد صنفی' === $result['sections'][1]['text'], 'The outline must record the heading text.' );
dzn_test_assert( 'section-3' === $result['sections'][2]['anchor'], 'An empty heading must fall back to its position: ' . $result['sections'][2]['anchor'] );
dzn_test_assert( false !== strpos( $result['content'], 'id="khodem"' ), 'The authored anchor must be rendered.' );
dzn_test_assert( false !== strpos( $result['content'], 'id="قواعد-صنفی"' ), 'The derived Persian anchor must be rendered.' );
dzn_test_assert( false !== strpos( $result['content'], 'id="section-3"' ), 'The positional fallback must be rendered.' );
dzn_test_assert( false !== strpos( $result['content'], '<h1>عنوان اصلی</h1>' ), 'H1 must remain untouched.' );
dzn_test_assert( false !== strpos( $result['content'], '<h4>سرفصل فرعی</h4>' ), 'Headings outside the outline must remain untouched.' );

// An empty heading receives a positional, still-deterministic anchor.
$empty = dzn_theme_content_page_anchor_content( '<h2></h2><h2>   </h2>' );
dzn_test_assert( 'section-1' === $empty['sections'][0]['anchor'], 'An empty heading must fall back to its position.' );
dzn_test_assert( 'section-2' === $empty['sections'][1]['anchor'], 'A whitespace-only heading must fall back to its position.' );

// Idempotence: anchoring anchored content must not drift the outline.
$again = dzn_theme_content_page_anchor_content( $result['content'] );
dzn_test_assert( $again['content'] === $result['content'], 'Anchoring must be idempotent.' );
dzn_test_assert(
	array_column( $again['sections'], 'anchor' ) === array_column( $result['sections'], 'anchor' ),
	'The outline anchors must be identical on a second pass.'
);
dzn_test_assert(
	array_column( $again['sections'], 'text' ) === array_column( $result['sections'], 'text' ),
	'The outline text must be identical on a second pass.'
);

// Explicit duplicate authored ids resolve predictably instead of colliding.
$duplicate_ids = dzn_theme_content_page_anchor_content( '<h2 id="rules">الف</h2><h2 id="rules">ب</h2>' );
dzn_test_assert( 'rules' === $duplicate_ids['sections'][0]['anchor'] && 'rules-2' === $duplicate_ids['sections'][1]['anchor'], 'Duplicate authored ids must be resolved in document order.' );

// Reading time is presentation-only, deterministic and never zero for readable text.
dzn_test_assert( 0 === dzn_theme_content_page_reading_minutes( '' ), 'Empty content has no reading time.' );
dzn_test_assert( 1 === dzn_theme_content_page_reading_minutes( implode( ' ', array_fill( 0, 200, 'واژه' ) ) ), 'Two hundred words is one minute.' );
dzn_test_assert( 5 === dzn_theme_content_page_reading_minutes( implode( ' ', array_fill( 0, 1000, 'واژه' ) ) ), 'One thousand words is five minutes.' );
dzn_test_assert( 1 === dzn_theme_content_page_reading_minutes( '<p>کوتاه</p>' ), 'A short text still reads as one minute.' );

// The outline gate keeps short pages free of a table of contents.
dzn_test_assert( 3 === dzn_theme_content_page_toc_minimum(), 'The table of contents gate is three sections.' );

// Mode contracts.
$article = dzn_theme_content_page_presentation( 'article' );
$policy  = dzn_theme_content_page_presentation( 'policy' );
$general = dzn_theme_content_page_presentation( 'general' );
$unknown = dzn_theme_content_page_presentation( 'not-a-mode' );

dzn_test_assert( 'dzn-document--article' === $article['document_class'], 'Article mode must own its document class.' );
foreach ( array( 'show_categories', 'show_publication', 'show_reading_time', 'show_featured_image', 'show_related', 'show_previous_next' ) as $flag ) {
	dzn_test_assert( true === $article[ $flag ], "Article mode must enable {$flag}." );
}
dzn_test_assert( false === $policy['show_categories'] && false === $policy['show_related'] && false === $policy['show_reading_time'] && false === $policy['show_featured_image'], 'Policy mode must stay restrained.' );
dzn_test_assert( true === $policy['show_publication'] && true === $policy['show_print_hint'], 'Policy mode must keep restrained metadata and print support.' );
foreach ( array( 'show_categories', 'show_publication', 'show_reading_time', 'show_featured_image', 'show_related', 'show_previous_next', 'show_print_hint' ) as $flag ) {
	dzn_test_assert( false === $general[ $flag ], "General mode must stay neutral: {$flag}." );
}
dzn_test_assert( $general === $unknown, 'An unknown mode must fall back to General.' );

// Rendered table of contents: desktop navigation and the native mobile disclosure.
$sections = $result['sections'];

$args    = array( 'sections' => $sections, 'variant' => 'desktop' );
ob_start();
require DZN_TEST_THEME . 'template-parts/content/table-of-contents.php';
$desktop = ob_get_clean();

$args   = array( 'sections' => $sections, 'variant' => 'mobile' );
ob_start();
require DZN_TEST_THEME . 'template-parts/content/table-of-contents.php';
$mobile = ob_get_clean();

dzn_test_assert( false !== strpos( $desktop, 'dzn-toc--desktop' ), 'The desktop outline must render its variant.' );
dzn_test_assert( false !== strpos( $desktop, 'aria-labelledby="dzn-toc-title-desktop"' ), 'The desktop outline must be labelled.' );
dzn_test_assert( false !== strpos( $mobile, '<details' ) && false !== strpos( $mobile, '<summary' ), 'The mobile outline must be a native disclosure.' );
dzn_test_assert( false === strpos( $mobile, 'aria-modal' ), 'The mobile disclosure must not pretend to be a modal dialog.' );

foreach ( $sections as $section ) {
	$href = 'href="#' . $section['anchor'] . '"';
	dzn_test_assert( false !== strpos( $desktop, $href ), "The desktop outline must link {$href}." );
	dzn_test_assert( false !== strpos( $mobile, $href ), "The mobile outline must link {$href}." );
}

$args = array( 'sections' => array(), 'variant' => 'desktop' );
ob_start();
require DZN_TEST_THEME . 'template-parts/content/table-of-contents.php';
dzn_test_assert( '' === trim( (string) ob_get_clean() ), 'An empty outline must render nothing.' );


// ---------------------------------------------------------------------------
// Correction round 1 — adversarial parsing and complete ID collision avoidance.
// ---------------------------------------------------------------------------

// A legal ">" or "<" inside a quoted attribute must never corrupt the tag, the anchor or the text.
$tricky = dzn_theme_content_page_anchor_content(
	'<h2 title="a > b" data-note="x < y" class="has-arrow">عنوان الف</h2>' .
	"<h2 title='c > d'>عنوان ب</h2>" .
	'<h3 data-label="a > b < c">عنوان پ</h3>'
);
dzn_test_assert( 3 === count( $tricky['sections'] ), 'Quoted attribute values must not truncate headings: ' . json_encode( array_column( $tricky['sections'], 'text' ), JSON_UNESCAPED_UNICODE ) );
dzn_test_assert( 'عنوان الف' === $tricky['sections'][0]['text'], 'The outline text must come from the heading inner content, not from an attribute fragment: ' . $tricky['sections'][0]['text'] );
dzn_test_assert( 'عنوان-الف' === $tricky['sections'][0]['anchor'], 'A heading with a ">" attribute must still receive its text anchor: ' . $tricky['sections'][0]['anchor'] );
dzn_test_assert( 'عنوان-ب' === $tricky['sections'][1]['anchor'], 'Single-quoted attributes with ">" must parse correctly.' );
dzn_test_assert( false !== strpos( $tricky['content'], 'title="a > b"' ) && false !== strpos( $tricky['content'], 'data-note="x < y"' ), 'Authored attributes must be preserved verbatim.' );
dzn_test_assert( false !== strpos( $tricky['content'], 'id="عنوان-ال ف"' ) || false !== strpos( $tricky['content'], 'id="عنوان-الف"' ), 'The anchor must be rendered on the heading tag.' );

// Entities, nested inline markup and mixed scripts.
$markup = dzn_theme_content_page_anchor_content(
	'<h2>هزینه&nbsp;ها &amp; شرایط</h2><h2>عنوان <em>مهم</em> <strong>و</strong> پررنگ</h2><h2>بخش 2 — Intro Section</h2>'
);
dzn_test_assert( 'هزینه ها & شرایط' === $markup['sections'][0]['text'], 'Entities must be decoded for the outline text: ' . $markup['sections'][0]['text'] );
dzn_test_assert( 'هزینه-ها-شرایط' === $markup['sections'][0]['anchor'], 'A Persian anchor must stay slug-safe: ' . $markup['sections'][0]['anchor'] );
dzn_test_assert( 'عنوان مهم و پررنگ' === $markup['sections'][1]['text'], 'Nested inline markup must not leak into the outline text.' );
dzn_test_assert( false !== strpos( $markup['content'], '<em>مهم</em>' ) && false !== strpos( $markup['content'], '<strong>و</strong>' ), 'Nested inline markup must survive anchoring.' );
dzn_test_assert( 'بخش-2-intro-section' === $markup['sections'][2]['anchor'], 'Mixed Persian/Latin/numeric headings must slug deterministically: ' . $markup['sections'][2]['anchor'] );

// Malformed markup fails safe: nothing is rewritten and nothing joins the outline.
$malformed = dzn_theme_content_page_anchor_content( '<h2 title="unterminated>عنوان</h2><p>متن</p>' );
dzn_test_assert( 0 === count( $malformed['sections'] ), 'An unterminated attribute must not produce an outline entry.' );
dzn_test_assert( false !== strpos( $malformed['content'], 'title="unterminated>عنوان</h2>' ), 'Malformed markup must be left exactly as authored.' );
$unclosed = dzn_theme_content_page_anchor_content( '<h2>بدون بستن<p>متن</p>' );
dzn_test_assert( 0 === count( $unclosed['sections'] ), 'A heading without a closing tag must be skipped.' );
dzn_test_assert( false !== strpos( $unclosed['content'], '<h2>بدون بستن' ), 'A heading without a closing tag must stay untouched.' );

// Document-wide id collisions.
$non_heading = dzn_theme_content_page_anchor_content( '<p id="rules">متن</p><h2 id="rules">قواعد</h2>' );
dzn_test_assert( 'rules-2' === $non_heading['sections'][0]['anchor'], 'A heading must never duplicate a non-heading id: ' . $non_heading['sections'][0]['anchor'] );
dzn_test_assert( false !== strpos( $non_heading['content'], '<p id="rules">' ), 'The non-heading element must keep its own id.' );

$reserved = dzn_theme_content_page_anchor_content( '<h2 id="dzn-toc-title-desktop">فهرست</h2>' );
dzn_test_assert( 'dzn-toc-title-desktop-2' === $reserved['sections'][0]['anchor'], 'A heading must never take a theme-owned id: ' . $reserved['sections'][0]['anchor'] );

$extra_reserved = dzn_theme_content_page_anchor_content( '<h2 id="custom-reserved">عنوان</h2>', array( 'custom-reserved' ) );
dzn_test_assert( 'custom-reserved-2' === $extra_reserved['sections'][0]['anchor'], 'Caller-reserved ids must be honoured.' );

$generated_collision = dzn_theme_content_page_anchor_content( '<p id="intro">متن</p><h2>Intro</h2>' );
dzn_test_assert( 'intro-2' === $generated_collision['sections'][0]['anchor'], 'A generated anchor must avoid an existing document id: ' . $generated_collision['sections'][0]['anchor'] );

$cross = dzn_theme_content_page_anchor_content( '<h2>بخش</h2><h2 id="بخش">بخش</h2>' );
dzn_test_assert( 'بخش-2' === $cross['sections'][0]['anchor'] && 'بخش' === $cross['sections'][1]['anchor'], 'A generated anchor must not steal an authored id that appears later: ' . $cross['sections'][0]['anchor'] . '/' . $cross['sections'][1]['anchor'] );
$cross_again = dzn_theme_content_page_anchor_content( $cross['content'] );
dzn_test_assert( array_column( $cross_again['sections'], 'anchor' ) === array_column( $cross['sections'], 'anchor' ), 'Collision resolution must stay idempotent.' );

// The reserved-id contract itself is stable and covers the feature's own ids.
foreach ( array( 'dzn-toc-title-desktop', 'dzn-toc-title-mobile', 'dzn-related-title' ) as $owned ) {
	dzn_test_assert( in_array( $owned, dzn_theme_content_page_reserved_ids(), true ), "Theme-owned id must be reserved: {$owned}" );
}

// The outline must target the final, unique ids for both variants.
$args   = array( 'sections' => $cross['sections'], 'variant' => 'mobile' );
ob_start();
require DZN_TEST_THEME . 'template-parts/content/table-of-contents.php';
$mobile_collision = ob_get_clean();
dzn_test_assert( false !== strpos( $mobile_collision, 'href="#بخش-2"' ) && false !== strpos( $mobile_collision, 'href="#بخش"' ), 'The outline must link the resolved unique anchors.' );
dzn_test_assert( false !== strpos( $mobile_collision, 'id="dzn-toc-title-mobile"' ), 'The mobile outline panel must expose its reserved id.' );

echo "Content page render tests passed.\n";
